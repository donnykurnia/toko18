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
 * @property Barang_model $barang_model
 */
class Barang extends MY_Controller {

  function Barang()
  {
    parent::MY_Controller();
    //load model
    $this->load->model('user_model');
    $this->load->model('barang_model');
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
      $barang_id = intval($this->input->post('barang_id'));
      if ( $barang_id > 0 )
      {
        $result = $this->barang_model->delete($barang_id);
        $this->data['error_message'] .= str_replace("\n", "", $this->barang_model->display_errors(' ', '<br/>'));
        if ( $result )
        {
          $this->data['success_message'] = 'Barang telah dihapus!';
        }
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
    //handle filter
    $suffix_arr = array();
    $arr_condition = array();
    $condition = implode(' AND ', $arr_condition);
    $sort = "kode_barang ASC";
    //get barang list
    $barang_list = $this->barang_model->get_list_with_username($item_per_page, $offset, $condition, $sort, TRUE);
    $this->data['barang_list'] = $barang_list['rs'];
    //set pagination
    $paging_config['base_url'] = 'barang/index/page/%d%s';
    $paging_config['suffix_url'] = count($suffix_arr) > 0 ? '?'.implode('&amp;', $suffix_arr) : '';
    $paging_config['cur_page'] = $current_page;
    $paging_config['per_page'] = $item_per_page;
    $paging_config['total_rows'] = $barang_list['found_rows'];
    $this->load->library('paging');
    $this->paging->initialize($paging_config);
    $this->data['paging']     = $this->paging->create_links();
    $this->data['cur_page']   = $this->paging->cur_page;
    $this->data['total_page'] = $this->paging->num_page;
    $this->data['start']      = $this->paging->start;
    $this->data['per_page']   = $this->paging->per_page;
    //load view
    if ( $this->is_ajax_request )
    {
      //form submit
      if ( count($_POST) > 0 )
      {
        $output['success_message']  = $this->data['success_message'];
        $output['error_message']    = $this->data['error_message'];
        $output['new_content']      = $this->load->view('default/barang_list', $this->data, TRUE);
        //return json data
        $this->output->set_output(json_encode($output));
        return;
      }
      //not a form submit
      $this->load->view('default/barang_list', $this->data);
      return;
    }
    //not an ajax
    $this->data['in_barang'] = TRUE;
    //page title
    $this->data['title'] = 'Manajemen Barang';
    $this->data['page_title'] = 'Manajemen Barang';
    //additional css file
    $this->data['additional_css'] = array(
      base_url().'assets/dataTables-1.6/media/css/demo_table.css'
    );
    //additional js file
    $this->data['additional_js'] = array(
      base_url().'assets/js/apps/barang_list.js',
      base_url().'assets/dataTables-1.6/media/js/jquery.dataTables.js'
    );
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/barang_list',
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
      $arr_condition[] = "kode_barang LIKE {$sSearch_e}";
      $arr_condition[] = "nama_barang LIKE {$sSearch_e}";
      $arr_condition[] = "spesifikasi_barang LIKE {$sSearch_e}";
      $arr_condition[] = "satuan_barang LIKE {$sSearch_e}";
      $arr_condition[] = "qty LIKE {$sSearch_e}";
    }
    $condition = implode(" OR ", $arr_condition);
    $sort_arr = array();
    $iSortingCols = intval($this->input->get('iSortingCols'));
    for ( $i = 0; $i < $iSortingCols; $i++ )
    {
      $iSortCol = intval($this->input->get("iSortCol_{$i}"));
      $sSortDir = trim($this->input->get("sSortDir_{$i}")) == 'asc' ? 'asc' : 'desc';
      $column_arr = array('','kode_barang','nama_barang','spesifikasi_barang', 'qty','satuan_barang','username', '');
      $sort_arr[] = "{$column_arr[$iSortCol]} {$sSortDir}";
    }
    $sort = implode(",", $sort_arr);
    //get barang list
    $barang_list = $this->barang_model->get_list_with_username($item_per_page, $offset, $condition, $sort, TRUE);
    //put into result array
    $result = array(
      'sEcho'                 => intval($this->input->get('sEcho')),
      'iTotalRecords'         => $this->barang_model->get_total($condition),
      'iTotalDisplayRecords'  => $barang_list['found_rows']
    );
    if ( ! $barang_list['rs'] )
    {
      $result['aaData'] = array();
    }
    else
    {
      $i = $offset + 1;
      foreach ( $barang_list['rs'] as $row )
      {
        $result['aaData'][] = array(
          $i++,
          $row['kode_barang'],
          $row['nama_barang'],
          nl2br($row['spesifikasi_barang']),
          $row['qty'],
          $row['satuan_barang'],
          $row['username'],
          '<a href="'.site_url("barang/form/{$row['id']}").'">Edit Barang</a> '.
            '|<br/><a href="javascript:;" class="delete {\'barang_id\': '.$row['id'].'}">Hapus Barang</a>'
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
    //get barang list
    $barang_list = $this->barang_model->get_list_with_username(-1, 0, "", "kode_barang ASC");
    $this->data['barang_list'] = $barang_list;
    //get csv data
    $barang_list_csv = $this->load->view('default/barang_csv', $this->data, TRUE);
    //force download
    $filename = "barang_list_".date("Y-m-d").".csv";
    $this->load->helper("download");
    force_download($filename, $barang_list_csv);
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
    $barang_id = intval($this->uri->rsegment(3));
    $barang_detail = $this->barang_model->get_detail($barang_id);
    if ( $barang_detail )
    {
      $mode = 'edit';
    }
    //validation
    $result = FALSE;
    //validation rules
    $validation = array();
    $validation[] = array(
      'field' => 'kode_barang',
      'label' => 'Kode Barang',
      'rules' => 'trim|required|max_length[20]'
    );
    $validation[] = array(
      'field' => 'nama_barang',
      'label' => 'Nama Barang',
      'rules' => 'trim|required|max_length[50]'
    );
    $validation[] = array(
      'field' => 'spesifikasi_barang',
      'label' => 'Spesifikasi Barang',
      'rules' => 'trim'
    );
    $validation[] = array(
      'field' => 'satuan_barang',
      'label' => 'Satuan Barang',
      'rules' => 'trim|required|max_length[20]'
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
      $data_barang = array(
        'user_id'             => $this->user_model->session_user_id(),
        'kode_barang'         => $this->input->post('kode_barang'),
        'nama_barang'         => $this->input->post('nama_barang'),
        'spesifikasi_barang'  => $this->input->post('spesifikasi_barang'),
        'satuan_barang'       => $this->input->post('satuan_barang')
      );
      if ( $mode == 'add' )
      {
        $result = $this->barang_model->insert($data_barang);
        if ( $result )
        {
          $this->data['success_message'] = 'Data barang telah disimpan!';
          $barang_id = $result;
        }
      }
      else // edit
      {
        $result = $this->barang_model->update($barang_id, $data_barang);
        if ( $result )
        {
          $this->data['success_message'] = 'Data barang telah diedit!';
        }
      }
      //get error message
      $this->data['error_message'] .= str_replace("\n", "", $this->barang_model->display_errors('', '<br/>'));
    }
    if ( count($_POST) > 0 )
    {
      //after process
      $redirect_to = 'barang/index';
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
    $this->data['in_barang'] = TRUE;
    //page title
    $this->data['title'] = 'Tambah data barang baru';
    $this->data['page_title'] = 'Tambah data barang baru';
    if ( $mode == 'edit' )
    {
      $this->data['title'] = 'Edit data barang';
      $this->data['page_title'] = 'Edit data barang';
    }
    $this->data['mode'] = $mode;
    $this->data['barang_detail'] = $barang_detail;
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/barang_form',
      'page_footer'  => 'default/_footer',
    ));
    $this->view->render();
  }

}

/* End of file barang.php */
/* Location: ./application/controllers/barang.php */