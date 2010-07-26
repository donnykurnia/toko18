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
class Report extends MY_Controller {

  function Report()
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
    $condition_arr = array();
    $suffix_arr = array();
    $filter = FALSE;
    if ( trim($this->input->get('start_date')) != '' )
    {
      $date = $this->db->escape(date('Y-m-d H:i:s', strtotime(trim($this->input->get('start_date')))));
      $condition_arr[] = "{$date} <= tanggal_transaksi";
      $suffix_arr[] = "start_date=".urlencode(trim($this->input->get('start_date')));
      $filter = TRUE;
    }
    if ( trim($this->input->get('end_date')) != '' )
    {
      $date = $this->db->escape(date('Y-m-d H:i:s', strtotime(trim($this->input->get('end_date')).' 23:59:59')));
      $condition_arr[] = "tanggal_transaksi <= {$date}";
      $suffix_arr[] = "end_date=".urlencode(trim($this->input->get('end_date')));
      $filter = TRUE;
    }
    if ( intval($this->input->get('barang_id')) > 0 )
    {
      $condition_arr[] = "barang_id = ".intval($this->input->get('barang_id'));
      $suffix_arr[] = "barang_id=".urlencode(intval($this->input->get('barang_id')));
    }
    if ( trim($this->input->get('jenis_transaksi')) != '' )
    {
      $condition_arr[] = "jenis_transaksi = ".$this->db->escape(trim($this->input->get('jenis_transaksi')));
      $suffix_arr[] = "jenis_transaksi=".urlencode(trim($this->input->get('jenis_transaksi')));
      $filter = TRUE;
    }
    $condition = implode(' AND ', $condition_arr);
    $sort = "tanggal_transaksi DESC";
    //get report list
    $report_list = $filter ? $this->transaksi_model->get_list_with_barang_username($item_per_page, $offset, $condition, $sort, TRUE) : FALSE;
    $this->data['report_list'] = $report_list['rs'];
    $this->data['report_sum_total'] = $this->transaksi_model->get_sum_harga_total($condition);
    //set pagination
    $paging_config['base_url'] = 'report/index/page/%d%s';
    $paging_config['suffix_url'] = count($suffix_arr) > 0 ? '?'.implode('&amp;', $suffix_arr) : '';
    $paging_config['cur_page'] = $current_page;
    $paging_config['per_page'] = $item_per_page;
    $paging_config['total_rows'] = $report_list['found_rows'];
    $this->load->library('paging');
    $this->paging->initialize($paging_config);
    $this->data['paging']     = $this->paging->create_links();
    $this->data['cur_page']   = $this->paging->cur_page;
    $this->data['total_page'] = $this->paging->num_page;
    $this->data['start']      = $this->paging->start;
    $this->data['per_page']   = $this->paging->per_page;
    $this->data['filter']     = $filter;
    //load barang_option
    $this->data['barang_options'] = $this->barang_model->get_for_options("--Semua barang--");
    $transaksi_enums = $this->transaksi_model->get_enum_values();
    $this->data['jenis_transaksi_options'] = enum_options($transaksi_enums['jenis_transaksi']);
    //load view
    if ( $this->is_ajax_request )
    {
      //not a form submit
      $this->load->view('default/report_list', $this->data);
      return;
    }
    //not an ajax
    $this->data['in_report'] = TRUE;
    //page title
    $this->data['title'] = 'Report Transaksi';
    $this->data['page_title'] = 'Report Transaksi';
    //additional css file
    $this->data['additional_css'] = array(
      base_url().'assets/dataTables-1.6/media/css/demo_table.css'
    );
    //additional js file
    $this->data['additional_js'] = array(
      base_url().'assets/js/jquery-ui-1.7.2.custom.min.js',
      base_url().'assets/js/apps/report_list.js'
    );
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/report_list',
      'page_footer'  => 'default/_footer',
    ));
    $this->view->render();
  }

}

/* End of file report.php */
/* Location: ./application/controllers/report.php */