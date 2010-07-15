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
 * @property Transaksi_model $transaksi_model
 */
class Pembelian extends MY_Controller {

  function Pembelian()
  {
    parent::MY_Controller();
    //load model
    $this->load->model('user_model');
    $this->load->model('barang_model');
    $this->load->model('transaksi_model');
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
      $pembelian_id = intval($this->input->post('pembelian_id'));
      if ( $pembelian_id > 0 )
      {
        $result = $this->transaksi_model->delete($pembelian_id);
        $this->data['error_message'] .= str_replace("\n", "", $this->transaksi_model->display_errors(' ', '<br/>'));
        if ( $result )
        {
          $this->data['success_message'] = 'Transaksi pembelian telah dihapus!';
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
    $sort = "tanggal_transaksi DESC";
    //get pembelian list
    $pembelian_list = $this->transaksi_model->get_list_with_barang_username($item_per_page, $offset, $condition, $sort, TRUE);
    $this->data['pembelian_list'] = $pembelian_list['rs'];
    //set pagination
    $paging_config['base_url'] = 'pembelian/index/page/%d%s';
    $paging_config['suffix_url'] = count($suffix_arr) > 0 ? '?'.implode('&amp;', $suffix_arr) : '';
    $paging_config['cur_page'] = $current_page;
    $paging_config['per_page'] = $item_per_page;
    $paging_config['total_rows'] = $pembelian_list['found_rows'];
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
        $output['new_content']      = $this->load->view('default/pembelian_list', $this->data, TRUE);
        //return json data
        $this->output->set_output(json_encode($output));
        return;
      }
      //not a form submit
      $this->load->view('default/pembelian_list', $this->data);
      return;
    }
    //not an ajax
    $this->data['in_pembelian'] = TRUE;
    //page title
    $this->data['title'] = 'Manajemen Pembelian Barang';
    $this->data['page_title'] = 'Manajemen Pembelian Barang';
    //additional css file
    $this->data['additional_css'] = array(
      base_url().'assets/dataTables-1.6/media/css/demo_table.css'
    );
    //additional js file
    $this->data['additional_js'] = array(
      base_url().'assets/js/apps/pembelian_list.js',
      base_url().'assets/dataTables-1.6/media/js/jquery.dataTables.js'
    );
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/pembelian_list',
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
      $arr_condition[] = "qty LIKE {$sSearch_e}";
      $arr_condition[] = "harga_satuan LIKE {$sSearch_e}";
      $arr_condition[] = "diskon LIKE {$sSearch_e}";
      $arr_condition[] = "keterangan_transaksi LIKE {$sSearch_e}";
    }
    $condition = implode(" OR ", $arr_condition);
    $sort_arr = array();
    $iSortingCols = intval($this->input->get('iSortingCols'));
    for ( $i = 0; $i < $iSortingCols; $i++ )
    {
      $iSortCol = intval($this->input->get("iSortCol_{$i}"));
      $sSortDir = trim($this->input->get("sSortDir_{$i}")) == 'asc' ? 'asc' : 'desc';
      $column_arr = array('','tanggal_transaksi','kode_barang','qty','harga_satuan','diskon','harga_total','username', '');
      $sort_arr[] = "{$column_arr[$iSortCol]} {$sSortDir}";
    }
    $sort = implode(",", $sort_arr);
    //get pembelian list
    $pembelian_list = $this->transaksi_model->get_list_with_barang_username($item_per_page, $offset, $condition, $sort, TRUE);
    //put into result array
    $result = array(
      'sEcho'                 => intval($this->input->get('sEcho')),
      'iTotalRecords'         => $this->transaksi_model->get_total_with_barang_username($condition),
      'iTotalDisplayRecords'  => $pembelian_list['found_rows']
    );
    if ( ! $pembelian_list['rs'] )
    {
      $result['aaData'] = array();
    }
    else
    {
      $i = $offset + 1;
      foreach ( $pembelian_list['rs'] as $row )
      {
        $result['aaData'][] = array(
          $i++,
          date('d M Y H:i', mysql_to_unix($row['tanggal_transaksi'])),
          '['.$row['kode_barang'].'] '.$row['nama_barang'],
          $row['qty'],
          'Rp. '.number_format($row['harga_satuan'], 2),
          'Rp. '.number_format($row['diskon'], 2),
          'Rp. '.number_format($row['harga_total'], 2),
          $row['username'],
          '<a href="'.site_url("pembelian/form/{$row['id']}").'">Edit Pembelian</a> '.
            '|<br/><a href="javascript:;" class="delete {\'pembelian_id\': '.$row['id'].'}">Hapus Pembelian</a>'
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
    //get pembelian list
    $pembelian_list = $this->transaksi_model->get_list_with_username(-1, 0, "", "tanggal_transaksi DESC");
    $this->data['pembelian_list'] = $pembelian_list;
    //get csv data
    $pembelian_list_csv = $this->load->view('default/pembelian_csv', $this->data, TRUE);
    //force download
    $filename = "pembelian_list_".date("Y-m-d").".csv";
    $this->load->helper("download");
    force_download($filename, $pembelian_list_csv);
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
    $pembelian_id = intval($this->uri->rsegment(3));
    $pembelian_detail = $this->transaksi_model->get_detail($pembelian_id);
    if ( $pembelian_detail )
    {
      $mode = 'edit';
    }
    //validation
    $result = FALSE;
    //validation rules
    $validation = array();
    $validation[] = array(
      'field' => 'barang_id',
      'label' => 'Barang',
      'rules' => 'trim|required|integer|callback_check_barang'
    );
    $validation[] = array(
      'field' => 'qty',
      'label' => 'Qty',
      'rules' => 'trim|required|integer|max_length[11]'
    );
    $validation[] = array(
      'field' => 'harga_satuan',
      'label' => 'Harga Satuan',
      'rules' => 'trim|required|numeric|max_length[16]'
    );
    $validation[] = array(
      'field' => 'diskon',
      'label' => 'Diskon',
      'rules' => 'trim|required|numeric|max_length[16]'
    );
    $validation[] = array(
      'field' => 'keterangan_transaksi',
      'label' => 'Keterangan Transaksi',
      'rules' => 'trim'
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
      $data_pembelian = array(
        'user_id'               => $this->user_model->session_user_id(),
        'barang_id'             => $this->input->post('barang_id'),
        'jenis_transaksi'       => 'barang_masuk',
        'qty'                   => $this->input->post('qty'),
        'harga_satuan'          => $this->input->post('harga_satuan'),
        'diskon'                => $this->input->post('diskon'),
        'keterangan_transaksi'  => $this->input->post('keterangan_transaksi')
      );
      if ( $mode == 'add' )
      {
        $data_pembelian['tanggal_transaksi'] = date('Y-m-d H:i:s');
        $result = $this->transaksi_model->insert($data_pembelian);
        if ( $result )
        {
          $this->data['success_message'] = 'Data pembelian barang telah disimpan!';
          $pembelian_id = $result;
        }
      }
      else // edit
      {
        $result = $this->transaksi_model->update($pembelian_id, $data_pembelian);
        if ( $result )
        {
          $this->data['success_message'] = 'Data pembelian barang telah diedit!';
        }
      }
      //get error message
      $this->data['error_message'] .= str_replace("\n", "", $this->transaksi_model->display_errors('', '<br/>'));
    }
    if ( count($_POST) > 0 )
    {
      //after process
      $redirect_to = 'pembelian/index';
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
    $this->data['in_pembelian'] = TRUE;
    //page title
    $this->data['title'] = 'Tambah data pembelian barang baru';
    $this->data['page_title'] = 'Tambah data pembelian barang baru';
    if ( $mode == 'edit' )
    {
      $this->data['title'] = 'Edit data pembelian barang';
      $this->data['page_title'] = 'Edit data pembelian barang';
    }
    $this->data['mode'] = $mode;
    $this->data['pembelian_detail'] = $pembelian_detail;
    //load barang_option
    $this->data['barang_options'] = $this->barang_model->get_for_options("--Pilih barang--");
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/pembelian_form',
      'page_footer'  => 'default/_footer',
    ));
    $this->view->render();
  }

  /**
   *
   * @param $str string
   * @return boolean
   * @access public
   */
  function check_barang($str)
  {
    if ( intval($str) <= 0 )
    {
      $this->form_validation->set_message('check_barang', 'Pilihlah satu barang untuk transaksi ini!');
      return FALSE;
    }
    return TRUE;
  }

}

/* End of file pembelian.php */
/* Location: ./application/controllers/pembelian.php */