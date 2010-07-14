<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// $Id$
//

/**
 *
 * @author donny
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Email $email
 * @property CI_Form_validation $form_validation
 * @property CI_URI $uri
 * @property Firephp $firephp
 * @property ADOConnection $adodb
 * @property CI_DB_active_record $db
 * @property CI_DB_result $db
 * @property CI_DB_forge $dbforge
 * @property User_model $user_model
 */
class User extends MY_Controller {

  function User()
  {
    parent::MY_Controller();
    //load model
    $this->load->model('system_log_model');
  }

  /**
   *
   * @return void
   * @access public
   */
  function index()
  {
    //check privileges
    //if not already login, redirect
    if ( ! $this->user_model->check_login() )
    {
      $this->_redirect_in_list('user/login');
    }
    //if not admin, redirect
    if ( ! $this->user_model->check_is_administrator() )
    {
      $this->_redirect_in_list('dashboard/index');
    }
    //handle post
    if ( count($_POST) > 0 )
    {
      $user_id = intval($this->input->post('user_id'));
      if ( $user_id > 0 )
      {
        $result = TRUE;
        $result = $result AND $this->user_model->delete($user_id);
        if ( $result )
        {
          //insert process_log
          $log_data = array(
            'user_id'           => $this->session->userdata('user_id'),
            'ip_address'        => inet_aton($this->input->ip_address()),
            'process_timestamp' => date('Y-m-d H:i:s', time()),
            'action_type'       => 'delete',
            'action_module'     => 'user',
            'process_message'   => sprintf("Delete user with id=%s", $user_id)
          );
          $this->system_log_model->insert($log_data);
        }
        if ( $result )
        {
          $this->data['success_message'] = 'User deleted!';
        }
        $this->data['error_message'] .= str_replace("\n", "", $this->user_model->display_errors(' ', '<br/>'));
      }
      //not an ajax
      if ( ! $this->is_ajax_request )
      {
        $redirect_to = current_url();
        if ( $this->data['error_message'] )
        {
          $this->session->set_flashdata('error_message', $this->data['error_message']);
        }
        if ( $this->data['success_message'] )
        {
          $this->session->set_flashdata('success_message', $this->data['success_message']);
        }
        redirect($redirect_to);
      }
    }
    //get parameter
    $param_default = array('page');
    $param_array = $this->uri->ruri_to_assoc(3, $param_default);
    //get page param
    $current_page = 1;
    if (intval($param_array['page']) > 0)
    {
      $current_page = intval($param_array['page']);
    }
    $item_per_page = 10;
    $offset = ($current_page - 1) * $item_per_page;
    //get user_role param
    $user_role = '';
    if ( trim($this->input->get('user_role')) != '' )
    {
      $user_role = trim($this->input->get('user_role'));
    }
    //handle filter
    $suffix_arr = array();
    $arr_condition = array();
    if ( trim($user_role) != '' )
    {
      $user_role_q = $this->db->escape($user_role);
      $arr_condition[] = "user_role={$user_role_q}";
      $suffix_arr[] = "user_role={$user_role}";
    }
    $condition = implode(' AND ', $arr_condition);
    $sort = "username ASC";
    //get user list
    $user_list = $this->user_model->get_list($item_per_page, $offset, $condition, $sort, TRUE);
    $this->data['user_list'] = $user_list['rs'];
    //set pagination
    $paging_config['base_url'] = 'user/index/page/%d%s';
    $paging_config['suffix_url'] = '?'.implode('&amp;', $suffix_arr);
    $paging_config['cur_page'] = $current_page;
    $paging_config['per_page'] = $item_per_page;
    $paging_config['total_rows'] = $user_list['found_rows'];
    $this->load->library('paging');
    $this->paging->initialize($paging_config);
    $this->data['paging']     = $this->paging->create_links();
    $this->data['cur_page']   = $this->paging->cur_page;
    $this->data['total_page'] = $this->paging->num_page;
    $this->data['start']      = $this->paging->start;
    $this->data['per_page']   = $this->paging->per_page;
    //get statistic
    $this->data['total_administrator'] = $this->user_model->get_total("user_role='administrator'");
    $this->data['total_user'] = $this->user_model->get_total("user_role='user'");
    //filter selection
    $this->data['user_role'] = $user_role;
    //load options
    $user_enums = $this->user_model->get_enum_values();
    $this->data['user_role_options'] = enum_options($user_enums['user_role']);
    $this->data['user_role_options'] = array_merge(array('' => '-- All Role --'), $this->data['user_role_options']);
    //load view
    if ( $this->is_ajax_request )
    {
      //form submit
      if ( count($_POST) > 0 )
      {
        $output['success_message']  = $this->data['success_message'];
        $output['error_message']    = $this->data['error_message'];
        $output['new_content']      = $this->load->view('default/user_list', $this->data, TRUE);
        //return json data
        $this->output->set_output(json_encode($output));
        return;
      }
      //not a form submit
      $this->load->view('default/user_list', $this->data);
      return;
    }
    //not an ajax
    $this->data['in_user'] = TRUE;
    //page title
    $this->data['title'] = 'User list';
    $this->data['page_title'] = 'User list';
    //additional css file
    $this->data['additional_css'] = array(
      base_url().'assets/dataTables-1.6/media/css/demo_table.css'
    );
    //additional js file
    $this->data['additional_js'] = array(
      base_url().'assets/js/apps/user_list.js',
      base_url().'assets/dataTables-1.6/media/js/jquery.dataTables.js'
    );
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/user_list',
      'page_footer'  => 'default/_footer',
    ));
    $this->view->render();
  }

  /**
   *
   * @return json
   * @access public
   */
  function datatable()
  {
    //get page param
    $item_per_page = intval($this->input->get('iDisplayLength'));
    $offset = intval($this->input->get('iDisplayStart'));
    //build condition and sort
    $arr_condition = array();
    $sSearch = trim($this->input->get('sSearch'));
    if ( $sSearch != '' )
    {
      $sSearch_e = $this->db->escape("%{$sSearch}%");
      $arr_condition[] = "username LIKE {$sSearch_e}";
      $arr_condition[] = "user_email LIKE {$sSearch_e}";
      $arr_condition[] = "user_fullname LIKE {$sSearch_e}";
      $arr_condition[] = "user_role LIKE {$sSearch_e}";
      $arr_condition[] = "inet_ntoa(last_login_ip) LIKE {$sSearch_e}";
    }
    $condition = implode(" OR ", $arr_condition);
    $sort_arr = array();
    $iSortingCols = intval($this->input->get('iSortingCols'));
    for ( $i = 0; $i < $iSortingCols; $i++ )
    {
      $iSortCol = intval($this->input->get("iSortCol_{$i}"));
      $sSortDir = trim($this->input->get("sSortDir_{$i}")) == 'asc' ? 'asc' : 'desc';
      $column_arr = array('','username','user_email','user_fullname','user_role','last_login_datetime', '');
      $sort_arr[] = "{$column_arr[$iSortCol]} {$sSortDir}";
    }
    $sort = implode(",", $sort_arr);
    //get user list
    $user_list = $this->user_model->get_list($item_per_page, $offset, $condition, $sort, TRUE);
    //put into result array
    $result = array(
      'sEcho'                 => intval($this->input->get('sEcho')),
      'iTotalRecords'         => $this->user_model->get_total($condition),
      'iTotalDisplayRecords'  => $user_list['found_rows']
    );
    if ( ! $user_list['rs'] )
    {
      $result['aaData'] = array();
    }
    else
    {
      $i = $offset + 1;
      foreach ( $user_list['rs'] as $row )
      {
        $result['aaData'][] = array(
          $i++,
          $row['username'],
          $row['user_email'],
          $row['user_fullname'],
          ucwords(str_replace('_', ' ', $row['user_role'])),
          ( $row['last_login_datetime'] ) ? date('d M Y H:i:s', mysql_to_unix($row['last_login_datetime'])).' from '.inet_ntoa($row['last_login_ip']) : 'n/a',
          '<a href="'.site_url("user/form/{$row['id']}").'">Edit User</a>'.
          (( $this->session->userdata('user_id') != $row['id'] ) ? ' | <a href="javascript:;" class="delete {\'user_id\': '.$row['id'].'}">Delete User</a>' : '')
        );
      }
    }
    //return json data
    $this->output->set_output(json_encode($result));
    return;
  }

  /**
   *
   * @return void
   * @access public
   */
  function csv()
  {
    //get user list
    $user_list = $this->user_model->get_list(-1, 0, "", "username ASC");
    $this->data['user_list'] = $user_list;
    //get csv data
    $user_list_csv = $this->load->view('default/user_csv', $this->data, TRUE);
    //force download
    $filename = "user_list_".date("Y-m-d").".csv";
    $this->load->helper("download");
    force_download($filename, $user_list_csv);
  }

  /**
   *
   * @return void
   * @access public
   */
  function form()
  {
    //check privileges
    //if not already login, redirect
    if ( ! $this->user_model->check_login() )
    {
      $this->_redirect_in_form('user/login');
    }
    //if not admin, redirect
    if ( ! $this->user_model->check_is_administrator() )
    {
      $this->_redirect_in_form('dashboard/index');
    }
    //determine the mode
    $mode = 'add';
    //get parameter
    $user_id = intval($this->uri->rsegment(3));
    $user_detail = $this->user_model->get_detail($user_id);
    if ( $user_detail AND $this->user_model->check_is_administrator() )
    {
      $mode = 'edit';
    }
    //validation
    $result = FALSE;
    //validation rules
    $validation = array();
    if ( $mode == 'add' )
    {
      $validation['username'] = array(
        'field' => 'username',
        'label' => 'Username',
        'rules' => 'trim|required|max_length[20]|must_alpha_numeric|callback_username_check|prep_form'
      );
      $validation['user_password'] = array(
        'field' => 'user_password',
        'label' => 'Password',
        'rules' => 'trim|required|matches[user_password_2]|min_length[6]'
      );
    }
    else
    {
      $validation['user_password'] = array(
        'field' => 'user_password',
        'label' => 'Password',
        'rules' => 'trim|matches[user_password_2]'
      );
    }
    $validation['user_password_2'] = array(
      'field' => 'user_password_2',
      'label' => 'Repeat Password',
      'rules' => 'trim'
    );
    $validation['user_email'] = array(
      'field' => 'user_email',
      'label' => 'Email',
      'rules' => 'trim|required|max_length[127]|valid_email'
    );
    $validation['user_fullname'] = array(
      'field' => 'user_fullname',
      'label' => 'Fullname',
      'rules' => 'trim|required|max_length[255]|prep_form'
    );
    if ( $this->user_model->check_is_administrator() )
    {
      $validation['user_role'] = array(
        'field' => 'user_role',
        'label' => 'Role',
        'rules' => 'trim|required'
      );
    }
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('', '<br/>');
    $this->form_validation->set_rules($validation);
    //run validation
    if ($this->form_validation->run() == FALSE)
    {
      $this->data['error_message'] .= str_replace("\n", "", validation_errors());
    }
    else
    {
      //process form
      $user_data = array(
        'user_email'    => $this->input->post('user_email'),
        'user_fullname' => $this->input->post('user_fullname')
      );
      //handle user_role
      if ( $this->user_model->check_is_administrator() )
      {
        $user_data['user_role'] = $this->input->post('user_role');
      }
      if ( $mode == 'add' )
      {
        $user_data['username']      = $this->input->post('username');
        $user_data['password_salt'] = substr(random_string('unique'), 0, 8);
        $user_data['user_password'] = sha1($user_data['password_salt'].$this->input->post('user_password'));
        $result = $this->user_model->insert($user_data);
        if ( $result )
        {
          $this->data['success_message'] = 'User Added!';
          $user_id = $result;
          //insert process_log
          $log_data = array(
            'user_id'           => $this->session->userdata('user_id'),
            'ip_address'        => inet_aton($this->input->ip_address()),
            'process_timestamp' => date('Y-m-d H:i:s', time()),
            'action_type'       => 'insert',
            'action_module'     => 'user',
            'process_message'   => sprintf("Insert new user with id=%s", $user_id)
          );
          $this->system_log_model->insert($log_data);
        }
      }
      else // edit
      {
        if ( $this->input->post('user_password') !== '' )
        {
          $user_data['user_password'] = sha1($user_detail['password_salt'].$this->input->post('user_password'));
        }
        $result = $this->user_model->update($user_id, $user_data);
        if ( $result )
        {
          $this->data['success_message'] = 'User Edited!';
          //insert process_log
          $log_data = array(
            'user_id'           => $this->session->userdata('user_id'),
            'ip_address'        => inet_aton($this->input->ip_address()),
            'process_timestamp' => date('Y-m-d H:i:s', time()),
            'action_type'       => 'update',
            'action_module'     => 'user',
            'process_message'   => sprintf("Update user data with id=%s", $user_id)
          );
          $this->system_log_model->insert($log_data);
        }
      }
      //get error message
      $this->data['error_message'] .= str_replace("\n", "", $this->user_model->display_errors('', '<br/>'));
    }
    if ( count($_POST) > 0 )
    {
      //after process
      $redirect_to = 'user/index';
      if ( $this->is_ajax_request )
      {
        if ( $result )
        {
          $this->data['reload'] = site_url($redirect_to);
        }
        //return json data
        $this->output->set_output(json_encode($this->data));
        return;
      }
      //not an ajax
      if ( $result )
      {
        if ( $this->data['error_message'] )
        {
          $this->session->set_flashdata('error_message', $this->data['error_message']);
        }
        if ( $this->data['success_message'] )
        {
          $this->session->set_flashdata('success_message', $this->data['success_message']);
        }
        redirect($redirect_to);
      }
    }
    //set data for view
    $this->data['in_user'] = TRUE;
    //page title
    $this->data['title'] = 'Add new User';
    $this->data['page_title'] = 'Add new User';
    if ( $mode == 'edit' )
    {
      $this->data['title'] = 'Edit User';
      $this->data['page_title'] = 'Edit User';
    }
    $this->data['mode'] = $mode;
    $this->data['user_detail'] = $user_detail;
    //load options
    $user_enums = $this->user_model->get_enum_values();
    $this->data['user_role_options'] = $user_enums['user_role'];
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template_center';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/user_form',
      'page_footer'  => 'default/_footer',
    ));
    $this->view->render();
  }

  /**
   *
   * @return void
   * @access public
   */
  function profile()
  {
    //check privileges
    //if not already login, redirect
    if ( ! $this->user_model->check_login() )
    {
      $this->_redirect_in_form('user/login');
    }
    $mode = 'edit';
    $user_id = $this->session->userdata('user_id');
    $user_detail = $this->user_model->get_detail($user_id);
    if ( ! $user_detail )
    {
      $this->data['error_message'] = 'Cannot load user data!';
      $this->_redirect_in_form('dashboard/index');
    }
    //validation
    $result = FALSE;
    //validation rules
    $validation = array();
    $validation['user_password'] = array(
      'field' => 'user_password',
      'label' => 'Password',
      'rules' => 'trim|matches[user_password_2]'
    );
    $validation['user_password_2'] = array(
      'field' => 'user_password_2',
      'label' => 'Repeat Password',
      'rules' => 'trim'
    );
    $validation['user_email'] = array(
      'field' => 'user_email',
      'label' => 'Email',
      'rules' => 'trim|required|max_length[127]|valid_email'
    );
    $validation['user_fullname'] = array(
      'field' => 'user_fullname',
      'label' => 'Fullname',
      'rules' => 'trim|required|max_length[255]|prep_form'
    );
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('', '<br/>');
    $this->form_validation->set_rules($validation);
    //run validation
    if ($this->form_validation->run() == FALSE)
    {
      $this->data['error_message'] .= str_replace("\n", "", validation_errors());
    }
    else
    {
      //process form
      $user_data = array(
        'user_email'    => $this->input->post('user_email'),
        'user_fullname' => $this->input->post('user_fullname')
      );
      if ( $this->input->post('user_password') !== '' )
      {
        $user_data['user_password'] = sha1($user_detail['password_salt'].$this->input->post('user_password'));
      }
      $result = $this->user_model->update($user_id, $user_data);
      if ( $result )
      {
        $this->data['success_message'] = 'Profile Updated!';
        //insert process_log
        $log_data = array(
          'user_id'           => $this->session->userdata('user_id'),
          'ip_address'        => inet_aton($this->input->ip_address()),
          'process_timestamp' => date('Y-m-d H:i:s', time()),
          'action_type'       => 'update',
          'action_module'     => 'profile',
          'process_message'   => "Update profile data"
        );
        $this->system_log_model->insert($log_data);
      }
      //get error message
      $this->data['error_message'] .= str_replace("\n", "", $this->user_model->display_errors('', '<br/>'));
    }
    if ( count($_POST) > 0 )
    {
      //after process
      $redirect_to = 'user/profile';
      if ( $this->is_ajax_request )
      {
        //return json data
        $this->output->set_output(json_encode($this->data));
        return;
      }
      //not an ajax
      if ( $result )
      {
        if ( $this->data['error_message'] )
        {
          $this->session->set_flashdata('error_message', $this->data['error_message']);
        }
        if ( $this->data['success_message'] )
        {
          $this->session->set_flashdata('success_message', $this->data['success_message']);
        }
        redirect($redirect_to);
      }
    }
    //set data for view
    $this->data['in_profile'] = TRUE;
    //page title
    $this->data['title'] = 'Edit Profile';
    $this->data['page_title'] = 'Edit Profile';
    $this->data['mode'] = $mode;
    $this->data['user_detail'] = $user_detail;
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template_center';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/user_form',
      'page_footer'  => 'default/_footer',
    ));
    $this->view->render();
  }

  /**
   *
   * @return void
   * @access public
   */
  function login()
  {
    //check privileges
    //already login, redirect
    if ( $this->user_model->check_login() )
    {
      $this->_redirect_in_form('dashboard/index');
    }

    $result = FALSE;
    //validation rules
    $validation = array();
    $validation['username'] = array(
      'field' => 'username',
      'label' => 'Username',
      'rules' => 'trim|required|max_length[255]'
    );
    $validation['user_password'] = array(
      'field' => 'user_password',
      'label' => 'Password',
      'rules' => 'trim|required'
    );
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('', '<br/>');
    $this->form_validation->set_rules($validation);
    //run validation
    if ($this->form_validation->run() == FALSE)
    {
      $this->data['error_message'] .= str_replace("\n", "", validation_errors());
    }
    else
    {
      //get form entry
      $username = $this->input->post('username');
      $user_password = $this->input->post('user_password');
      //do login
      $result = $this->user_model->login($username, $user_password);
      //get error message
      $this->data['error_message'] .= str_replace("\n", "", $this->user_model->display_errors('', '<br/>'));
    }
    if ( count($_POST) > 0 )
    {
      //after process
      $redirect_to = 'dashboard/index';
      if ( $this->session->userdata('to_page') )
      {
        $redirect_to = $this->session->userdata('to_page');
        $this->session->unset_userdata('to_page');
      }
      if ( $this->is_ajax_request )
      {
        if ( $result )
        {
          $this->data['reload'] = site_url($redirect_to);
        }
        //return json data
        $this->output->set_output(json_encode($this->data));
        return;
      }
      //not an ajax
      if ( $result )
      {
        if ( $this->data['error_message'] )
        {
          $this->session->set_flashdata('error_message', $this->data['error_message']);
        }
        redirect($redirect_to);
      }
    }
    //page title
    $this->data['title'] = 'Login';
    $this->data['page_title'] = 'Login';
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template_center';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/user_login',
      'page_footer'  => 'default/_footer',
    ));
    $this->view->render();
  }

  /**
   *
   * @return void
   * @access public
   */
  function logout()
  {
    $this->user_model->logout();
    redirect('front/index');
  }

  /**
   *
   * @param $str string
   * @return boolean
   * @access public
   */
  function username_check($str)
  {
    if ($this->user_model->is_username_exist($str))
    {
      $this->form_validation->set_message('username_check', 'The username already registered.  Please use another username.');
      return FALSE;
    }
    return TRUE;
  }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */