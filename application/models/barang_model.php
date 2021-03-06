<?php
// $Id$
//

/**
 *
 * @author donny
 * @property ADOConnection $adodb
 * @property Firephp $firephp
 */
class Barang_model extends MY_Model {

  function Barang_model()
  {
    // Call the Model constructor
    parent::MY_Model();
    $this->table = 'barang';
  }

  /**
   * Return an array for form_dropdown usage
   * @param $first string
   * @param $condition strung
   * @param $escape_html boolean
   * @return array
   */
  function get_for_options($first="", $condition="", $with_code=TRUE, $escape_html=FALSE)
  {
    //default value
    $result = array();
    //sanitize
    $first = trim($first);
    if ( $first )
    {
      $result[0] = $first;
    }

    $sort = "nama_barang ASC";
    if ( $with_code )
    {
      $sort = "kode_barang ASC";
    }
    $list = $this->get_list_with_username(-1, 0, $condition, $sort);
    if ( $list )
    {
      foreach ( $list as $row )
      {
        $option = $row['nama_barang'];
        if ( $with_code )
        {
          $option = "[{$row['kode_barang']}] {$row['nama_barang']}";
        }
        $result[$row['id']] = $escape_html ? htmlentities($option, ENT_COMPAT, 'UTF-8') : $option;
      }
    }
    return $result;
  }

  /**
   * Get list joined with user table to get username column
   * @param $num integer
   * @param $offset integer
   * @param $condition string
   * @param $order_by string
   * @return result_array, array or FALSE
   */
  function get_list_with_username($num=10, $offset=0, $condition='', $order_by='', $found_rows=FALSE)
  {
    $this->CI->load->model('user_model');
    $this->CI->load->model('transaksi_model');
    //build sub_query
    $sub_sql = "    SELECT b.*, u.username, ".
               "           (".
               "            IFNULL((SELECT SUM(qty) FROM {$this->CI->transaksi_model->table} t WHERE t.barang_id=b.id AND t.jenis_transaksi='barang_masuk'), 0) - ".
               "            IFNULL((SELECT SUM(qty) FROM {$this->CI->transaksi_model->table} t WHERE t.barang_id=b.id AND t.jenis_transaksi='barang_keluar'), 0)".
               "           ) qty ".
               "      FROM {$this->table} b ".
               " LEFT JOIN {$this->CI->user_model->table} u ON u.id=b.user_id ";
    //actual query
    $sql = "    SELECT ".( $found_rows ? "SQL_CALC_FOUND_ROWS " : '').
           "           * ".
           "      FROM ({$sub_sql}) barang_qty ";
    if ( trim($condition) !== '' )
    {
      $where = trim($condition);
      $sql .= " WHERE ( {$where} ) ";
    }
    if ( trim($order_by) !== '' )
    {
      $sql .= " ORDER BY {$order_by} ";
    }
    $result = $this->get_list_by_sql($num, $offset, $sql, $found_rows);
    return $result;
  }

  /**
   * Get total joined with user table to get username column
   * @param $num integer
   * @param $offset integer
   * @param $condition string
   * @param $order_by string
   * @return result_array, array or FALSE
   */
  function get_total_with_username($condition='')
  {
    $this->CI->load->model('user_model');
    $this->CI->load->model('transaksi_model');
    //build sub_query
    $sub_sql = "    SELECT b.*, u.username, ".
               "           IFNULL((".
               "            (SELECT SUM(qty) FROM {$this->CI->transaksi_model->table} t WHERE t.barang_id=b.id AND t.jenis_transaksi='barang_masuk') - ".
               "            (SELECT SUM(qty) FROM {$this->CI->transaksi_model->table} t WHERE t.barang_id=b.id AND t.jenis_transaksi='barang_keluar')".
               "           ), 0) qty ".
               "      FROM {$this->table} b ".
               " LEFT JOIN {$this->CI->user_model->table} u ON u.id=b.user_id ";
    //actual query
    $sql = "    SELECT COUNT(*) total ".
           "      FROM ({$sub_sql}) barang_qty ";
    if ( trim($condition) !== '' )
    {
      $where = trim($condition);
      $sql .= " WHERE ( {$where} ) ";
    }
    $result = $this->get_list_by_sql(-1, 0, $sql);
    if ( $result )
    {
      $row = $result[0];
      return $row['total'];
    }
    return 0;
  }

  /**
   * Check if a department can be deleted (no sub department and no position in it
   * @param $id integer
   * @return boolean
   */
  function is_can_deleted($id=0)
  {
    $id = intval($id);
    if ( $id > 0 )
    {
      //check transaksi
      $this->CI->load->model('transaksi_model');
      $position_count = $this->CI->transaksi_model->get_total("barang_id={$id}");
      if ( $position_count <= 0 )
      {
        return TRUE;
      }
      $this->set_error("Barang tidak bisa dihapus karena telah ada transaksi terkait barang ini!");
    }
    return FALSE;
  }

  /**
   *
   * @param $data array
   * @param $return_insert_id boolean
   * @return insert_id or FALSE
   */
  function insert($data=array(), $return_insert_id=TRUE)
  {
    $data['tanggal_input'] = date('Y-m-d H:i:s');
    $data['tanggal_update'] = date('Y-m-d H:i:s');
    $result = parent::insert($data, $return_insert_id);
    return $result;
  }

  /**
   *
   * @param $id integer
   * @param $data array
   * @return boolean
   */
  function update($id=0, $data=array())
  {
    $data['tanggal_update'] = date('Y-m-d H:i:s');
    $result = parent::update($id, $data);
    return $result;
  }

  /**
   *
   * @param $id integer
   * @return boolean
   */
  function delete($id=0)
  {
    $result = FALSE;
    if ( $this->is_can_deleted($id) )
    {
      $result = parent::delete($id);
    }
    return $result;
  }

}

/* End of file barang_model.php */
/* Location: ./application/models/barang_model.php */