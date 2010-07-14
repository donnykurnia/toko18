<?php
// $Id$
//

/**
 *
 * @author donny
 * @property ADOConnection $adodb
 * @property Firephp $firephp
 */
class Transaksi_model extends MY_Model {

  function Transaksi_model()
  {
    // Call the Model constructor
    parent::MY_Model();
    $this->table = 'transaksi_model';
  }

  /**
   * Get list joined with barang table and user table to get nama_barang and username column
   * @param $num integer
   * @param $offset integer
   * @param $condition string
   * @param $order_by string
   * @return result_array, array or FALSE
   */
  function get_list_with_barang_username($num=10, $offset=0, $condition='', $order_by='', $found_rows=FALSE)
  {
    $this->CI->load->model('user_model');
    $this->CI->load->model('barang_model');
    $sql = "    SELECT ".( $found_rows ? "SQL_CALC_FOUND_ROWS " : '').
           "           t.* b.nama_barang, u.username ".
           "      FROM {$this->table} t ".
           " LEFT JOIN {$this->CI->user_model->table} u ON u.id=t.user_id ".
           " LEFT JOIN {$this->CI->barang_model->table} b ON b.id=t.barang_id ";
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
    $sql = "    SELECT COUNT(*) total ".
           "      FROM {$this->table} t ".
           " LEFT JOIN {$this->CI->user_model->table} u ON u.id=t.user_id ".
           " LEFT JOIN {$this->CI->barang_model->table} b ON b.id=t.barang_id ";
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

}

/* End of file transaksi_model.php */
/* Location: ./system/application/models/transaksi_model.php */