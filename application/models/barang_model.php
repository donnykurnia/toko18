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
    $sql = "    SELECT ".( $found_rows ? "SQL_CALC_FOUND_ROWS " : '').
           "           b.*, u.username ".
           "      FROM {$this->table} b ".
           " LEFT JOIN {$this->CI->user_model->table} u ".
           "        ON u.id=b.user_id ";
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
/* Location: ./system/application/models/barang_model.php */