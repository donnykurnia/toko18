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
 * @property Reseller_model $reseller_model
 * @property User_reseller_list_model $user_reseller_list_model
 * @property User_country_list_model $user_country_list_model
 */
class User extends MY_Controller {

  function User()
  {
    parent::MY_Controller();
    //load model
    $this->load->model('user_model');
  }

  /**
   *
   * @return void
   * @access public
   */
  function index()
  {
    //check privileges
    $redirect_to = 'user/login';
    //if not already login, redirect
    if ( ! $this->user_model->check_login() )
    {
      $this->_redirect_in_list($redirect_to);
    }
    $redirect_to = 'dashboard/index';
    //if not admin, redirect
    if ( ! $this->user_model->check_is_admin() )
    {
      $this->_redirect_in_list($redirect_to);
    }
    //handle post
    if ( count($_POST) > 0 )
    {
      $uid = intval($this->input->post('uid'));
      if ( $uid > 0 )
      {
        $result = TRUE;
        $result = $result AND $this->user_model->delete($uid);
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
    $condition = "";
    $sort = "email ASC";
    //get user list
    $user_list = $this->user_model->get_list($item_per_page, $offset, $condition, $sort, TRUE);
    $this->data['user_list'] = $user_list['rs'];
    //set pagination
    $paging_config['base_url'] = 'user/index/page/%d%s';
    $paging_config['suffix_url'] = "";
    $paging_config['cur_page'] = $current_page;
    $paging_config['per_page'] = $item_per_page;
    $paging_config['total_rows'] = $user_list['found_rows'];
    $this->load->library('paging');
    $this->paging->initialize($paging_config);
    $this->data['paging']     = $this->paging->create_links();
    $this->data['cur_page']   = $this->paging->cur_page;
    $this->data['total_page'] = $this->paging->num_page;
    //load view
    if ( $this->is_ajax_request )
    {
      //form submit
      if ( count($_POST) > 0 )
      {
        //$output['success_message']  = $this->data['success_message'];
        //$output['error_message']    = $this->data['error_message'];
        $output['new_content']      = $this->load->view('backend/user_list', $this->data, TRUE);
        //return json data
        $this->output->set_output(json_encode($output));
        return;
      }
      //not a form submit
      $this->load->view('backend/user_list', $this->data);
      return;
    }
    //not an ajax
    $this->data['in_user'] = TRUE;
    //page title
    $this->data['title'] = 'User list';
    $this->data['page_title'] = 'User list';
    //additional js file
    $this->data['additional_js'] = array(
      base_url().'assets/js/load_page.min.js',
      base_url().'assets/js/backend/pagenav.js',
      base_url().'assets/js/backend/user_list.js'
    );
    //load view
    $this->load->library('view');
    $this->view->layout = 'backend/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'backend/_header',
      'main_content' => 'backend/user_list',
      'page_footer'  => 'backend/_footer',
    ));
    $this->view->render();
  }

  /**
   *
   * @return void
   * @access public
   */
  function form()
  {
    //check privileges
    $redirect_to = 'user/login';
    //if not already login, redirect
    if ( ! $this->user_model->check_login() )
    {
      $this->_redirect_in_form($redirect_to);
    }
    //load model
    $this->load->model('user_reseller_list_model');
    $this->load->model('user_country_list_model');
    //determine the mode
    $mode = 'add';
    //get parameter
    $uid = intval($this->uri->rsegment(3));
    if ( ! $this->user_model->check_is_admin() )
    {
      $uid = $this->session->userdata('uid');
    }
    $user_detail = $this->user_model->get_detail($uid);
    if ( $user_detail )
    {
      $mode = 'edit';
    }
    else
    {
      //if not admin, redirect
      if ( ! $this->user_model->check_is_admin() )
      {
        $redirect_to = 'dashboard/index';
        $this->_redirect_in_form($redirect_to);
      }
    }
    //validation
    $result = FALSE;
    //validation rules
    $validation = array();
    $validation['first_name'] = array(
      'field' => 'first_name',
      'label' => 'First Name',
      'rules' => 'trim|max_length[50]|prep_form'
    );
    $validation['last_name'] = array(
      'field' => 'last_name',
      'label' => 'Last Name',
      'rules' => 'trim|required|max_length[50]|prep_form'
    );
    $validation['email'] = array(
      'field' => 'email',
      'label' => 'Email',
      'rules' => 'trim|required|max_length[127]|valid_email'
    );
    if ( $mode == 'add' )
    {
      $validation['password'] = array(
        'field' => 'password',
        'label' => 'Password',
        'rules' => 'trim|required'
      );
    }
    else
    {
      $validation['password'] = array(
        'field' => 'password',
        'label' => 'Password',
        'rules' => 'trim'
      );
    }
    $this->load->library('form_validation');
    $this->form_validation->set_rules($validation);
    //run validation
    if ($this->form_validation->run() == FALSE)
    {
      $this->data['error_message'] .= str_replace("\n", "", validation_errors(' ', '<br/>'));
    }
    else
    {
      //process form
      $user_data = array(
        'first_name'  => $this->input->post('first_name'),
        'last_name'   => $this->input->post('last_name'),
        'email'       => $this->input->post('email')
      );
      //handle checkbox
      if ( $this->user_model->check_is_admin() )
      {
        $user_data['is_admin'] = ( $this->input->post('is_admin') !== FALSE ) ? 1 : 0;
      }
      if ( $mode == 'add' )
      {
        $user_data['password'] = sha1($this->input->post('password'));
        $result = $this->user_model->insert($user_data);
        if ( $result )
        {
          $this->data['success_message'] = 'User Added!';
          $uid = $result;
          //save reseller and country
          $this->user_reseller_list_model->save_by_uid($uid, $this->input->post('reseller_id'));
          $this->user_country_list_model->save_by_uid($uid, $this->input->post('country_id'));
        }
      }
      else // edit
      {
        if ( trim($this->input->post('password')) !== '' )
        {
          $user_data['password'] = sha1($this->input->post('password'));
        }
        $result = $this->user_model->update($uid, $user_data);
        if ( $result )
        {
          if ( ! $this->user_model->check_is_admin() )
          {
            $this->data['success_message'] = 'Profile Updated!';
          }
          else
          {
            $this->data['success_message'] = 'User Edited!';
            //save reseller and country
            $this->user_reseller_list_model->save_by_uid($uid, $this->input->post('reseller_id'));
            $this->user_country_list_model->save_by_uid($uid, $this->input->post('country_id'));
          }
        }
      }
      //get error message
      $this->data['error_message'] .= str_replace("\n", "", $this->user_model->display_errors(' ', '<br/>'));
    }
    if ( count($_POST) > 0 )
    {
      //after process
      $redirect_to = 'user/index';
      if ( $this->is_ajax_request )
      {
        if ( $result AND $this->user_model->check_is_admin() )
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
        if ( $this->user_model->check_is_admin() )
        {
          redirect($redirect_to);
        }
      }
    }
    //set data for view
    $this->data['in_user'] = TRUE;
    //page title
    $this->data['title'] = 'Add new User';
    $this->data['page_title'] = 'Add new User';
    //default selected reseller and country
    //get selected reseller and country
    $this->data['selected_reseller'] = $this->user_reseller_list_model->get_selected_by_uid($uid);
    $this->data['selected_country'] = $this->user_country_list_model->get_selected_by_uid($uid);
    if ( $mode == 'edit' )
    {
      $this->data['title'] = 'Edit User';
      $this->data['page_title'] = 'Edit User';
    }
    $this->data['mode'] = $mode;
    $this->data['user_detail'] = $user_detail;
    //load reseller_options
    $this->load->model('reseller_model');
    $this->data['reseller_options'] = $this->reseller_model->get_for_option(FALSE, "active=1");
    $this->load->model('country_model');
    $this->data['country_options'] = $this->country_model->get_for_option(FALSE);
    //load view
    $this->load->library('view');
    $this->view->layout = 'backend/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'backend/_header',
      'main_content' => 'backend/user_form',
      'page_footer'  => 'backend/_footer',
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
    $redirect_to = 'dashboard/index';
    //already login, redirect
    if ( $this->user_model->check_login() )
    {
      $this->_redirect_in_form($redirect_to);
    }

    $result = FALSE;
    //validation rules
    $validation = array();
    $validation['email'] = array(
      'field' => 'email',
      'label' => 'Email',
      'rules' => 'trim|required|max_length[127]|valid_email'
    );
    $validation['password'] = array(
      'field' => 'password',
      'label' => 'Password',
      'rules' => 'trim|required'
    );
    $this->load->library('form_validation');
    $this->form_validation->set_rules($validation);
    //run validation
    if ($this->form_validation->run() == FALSE)
    {
      $this->data['error_message'] .= str_replace("\n", "", validation_errors(' ', '<br/>'));
    }
    else
    {
      //get form entry
      $email = $this->input->post('email');
      $password = $this->input->post('password');
      //do login
      $result = $this->user_model->login($email, $password);
      //get error message
      $this->data['error_message'] .= str_replace("\n", "", $this->user_model->display_errors(' ', '<br/>'));
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
    $this->view->layout = 'front/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'front/_header',
      'main_content' => 'front/login',
      'page_footer'  => 'front/_footer',
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
  function email_check($str)
  {
    if ($this->user_model->is_email_exist($str))
    {
      $this->form_validation->set_message('email_check', 'The email address already registered.  Please use another email address.');
      return FALSE;
    }
    return TRUE;
  }

}

/* End of file user.php */
/* Location: ./system/application/controllers/user.php */