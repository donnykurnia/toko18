<?php
// $Id$
//

/**
 *
 * @author donny
 * @property CI_Loader $load
 * @property CI_DB_active_record $db
 * @property CI_DB_result $db
 * @property CI_DB_forge $dbforge
 */
class MY_Model extends Model {

  var $table          = '';
  var $primary_key    = 'id';
  var $error_msg      = array();
  var $info_msg       = array();
  var $upload_path    = '';
  var $upload_url     = '';
  var $allowed_types  = 'txt|doc|pdf|zip|gif|jpg|png';
  var $encrypt_name   = TRUE;
  var $max_filename   = 255;
  var $CI;

  function MY_Model()
  {
    // Call the Model constructor
    parent::Model();
    $this->CI =& get_instance();
    $this->CI->load->database();
    log_message('debug', "MY_Model Class Initialized");
  }

  /**
   *
   * @param $upload_path string
   * @return void
   */
  function set_upload_path($upload_path='')
  {
    $this->upload_path = strval($upload_path);
  }

  /**
   *
   * @param $filename string
   * @return string
   */
  function get_upload_path($filename='')
  {
    return $this->upload_path.$filename;
  }

  /**
   *
   * @param $upload_path string
   * @return void
   */
  function set_upload_url($upload_url='')
  {
    $this->upload_url = strval($upload_url);
  }

  /**
   *
   * @param $filename string
   * @return string
   */
  function get_upload_url($filename='', $thumbnail=FALSE)
  {
    if ( $thumbnail AND file_exists($this->get_upload_path('thumb_'.$filename)) )
    {
      return $this->upload_url.'thumb_'.$filename;
    }
    return $this->upload_url.$filename;
  }

  /**
   *
   * @param $condition string
   * @param $order_by string
   * @return result_array, array or FALSE
   */
  function get_all($condition='', $order_by='', $found_rows=FALSE)
  {
    return $this->get_list(-1, 0, $condition, $order_by, $found_rows);
  }

  /**
   *
   * @param $num integer
   * @param $offset integer
   * @param $condition string
   * @param $order_by string
   * @return result_array, array or FALSE
   */
  function get_list($num=10, $offset=0, $condition='', $order_by='', $found_rows=FALSE)
  {
    //default value
    if ( ! $found_rows )
    {
      $result = FALSE;
    }
    else
    {
      $result = array(
        'rs'          => FALSE,
        'found_rows'  => 0
      );
    }
    if ( $found_rows )
    {
      $this->db->select('SQL_CALC_FOUND_ROWS *', FALSE);
    }
    $this->db->from($this->table);
    if ( trim($condition) !== '' )
    {
      $this->db->where($condition, NULL, FALSE);
    }
    if ( trim($order_by) !== '' )
    {
      $this->db->order_by($order_by);
    }
    if ( $num != -1 )
    {
      $this->db->limit($num, $offset);
    }
    $query = $this->db->get();
    if ( $query->num_rows() > 0 )
    {
      if ( ! $found_rows )
      {
        $result = $query->result_array();
      }
      else
      {
        $result['rs'] = $query->result_array();
        //get found_rows
        $sql_found_rows = "SELECT FOUND_ROWS() numrows";
        $rs_found_rows = $this->db->query($sql_found_rows);
        if ( $rs_found_rows->num_rows() > 0 )
        {
          $row = $rs_found_rows->row_array();
          $result['found_rows'] = $row['numrows'];
        }
      }
    }
    return $result;
  }

  /**
   *
   * @param $num integer
   * @param $offset integer
   * @param $sql string
   * @param $found_rows boolean
   * @return result_array, array or FALSE
   */
  function get_list_by_sql($num=10, $offset=0, $sql='', $found_rows=FALSE)
  {
    //default value
    if ( ! $found_rows )
    {
      $result = FALSE;
    }
    else
    {
      $result = array(
        'rs'          => FALSE,
        'found_rows'  => 0
      );
    }
    //query
    $sql = trim($sql);
    if ( $sql != '' )
    {
      if ( $num != -1 )
      {
        $sql .= " LIMIT {$offset}, {$num} ";
      }
      $query = $this->db->query($sql);
      if ( $query->num_rows() > 0 )
      {
        if ( ! $found_rows )
        {
          $result = $query->result_array();
        }
        else
        {
          $result['rs'] = $query->result_array();
          //get found_rows
          $sql_found_rows = "SELECT FOUND_ROWS() numrows";
          $rs_found_rows = $this->db->query($sql_found_rows);
          if ( $rs_found_rows->num_rows() > 0 )
          {
            $row = $rs_found_rows->row_array();
            $result['found_rows'] = $row['numrows'];
          }
        }
      }
    }
    return $result;
  }

  /**
   *
   * @param $condition string
   * @return integer
   */
  function get_total($condition='')
  {
    $this->db->from($this->table);
    if ( trim($condition) !== '' )
    {
      $this->db->where($condition, NULL, FALSE);
    }
    return $this->db->count_all_results();
  }

  /**
   *
   * @param $id integer
   * @return array or FALSE
   */
  function get_detail($id=0)
  {
    $id = intval($id);
    if ( $id > 0 )
    {
      $this->db->from($this->table);
      $this->db->where($this->primary_key, $id);
      $this->db->limit(1);
      $query = $this->db->get();
      if ( $query->num_rows() > 0 )
      {
        return $query->row_array();
      }
    }
    return FALSE;
  }

  /**
   *
   * @param $sql string
   * @param $param array
   * @return array or FALSE
   */
  function get_detail_by_sql($sql='', $param=FALSE)
  {
    $sql = trim($sql);
    if ( $sql != '' )
    {
      if ( is_array($param) )
      {
        $this->db->where($param);
      }
      $this->db->limit(1);
      $query = $this->db->query($sql);
      if ( $query->num_rows() > 0 )
      {
        return $query->row_array();
      }
    }
    return FALSE;
  }

  /**
   *
   * @return array
   */
  function get_enum_values()
  {
    $enum_result = array();
    $sql = "SHOW COLUMNS FROM {$this->table} ";
    $query = $this->db->query($sql);
    if ( $query->num_rows() > 0 )
    {
      $rs = $query->result_array();
      foreach ( $rs as $row )
      {
        if ( ereg(('set|enum'), $row['Type']) )
        {
          eval(ereg_replace('set|enum', '$'.$row['Field'].' = array', $row['Type']).';');
          $enum_result[strtolower($row['Field'])] = array_combine($$row['Field'], $$row['Field']);
        }
      }
    }

    return $enum_result;
  }

  /**
   * Process uploaded file, return file name
   * @param string $file_field_name
   * @param array $config
   * @return array
   */
  function process_upload($file_field_name='', $config=array(), $is_flash = FALSE)
  {
    //default data
    $upload_data = array();
    //upload config
    if ( count($config) == 0)
    {
      $config['upload_path']    = $this->upload_path;
      $config['allowed_types']  = $this->allowed_types;
      $config['encrypt_name']   = $this->encrypt_name;
      $config['max_size']       = return_kilobytes(ini_get('upload_max_filesize'));
      $config['max_filename']   = $this->max_filename;
      $config['is_flash']       = $is_flash;
    }
    $this->CI->load->library('upload');
    $this->CI->upload->initialize($config);
    //process upload
    if ( ! $this->CI->upload->do_upload($file_field_name) )
    {
      $this->error_msg = array_merge($this->error_msg, $this->CI->upload->error_msg);
    }
    else
    {
      $upload_data = $this->CI->upload->data();
    }
    return $upload_data;
  }

  /**
   *
   * @param $data array
   * @param $return_insert_id boolean
   * @return insert_id or FALSE
   */
  function insert($data=array(), $return_insert_id=TRUE)
  {
    if ( is_array($data) AND count($data) > 0 )
    {
      $result = $this->db->insert($this->table, $data);
      if ( $result AND $return_insert_id )
      {
        return $this->db->insert_id();
      }
      return $result;
    }
    return FALSE;
  }

  /**
   *
   * @param $id integer
   * @param $data array
   * @return boolean
   */
  function update($id=0, $data=array())
  {
    $id = intval($id);
    if ( $id > 0 AND is_array($data) AND count($data) > 0 )
    {
      $this->db->where($this->primary_key, $id);
      $result = $this->db->update($this->table, $data);
      return $result;
    }
    return FALSE;
  }

  /**
   *
   * @param $id integer
   * @return boolean
   */
  function delete($id=0)
  {
    $id = intval($id);
    if ( $id > 0 )
    {
      $this->db->where($this->primary_key, $id);
      $result = $this->db->delete($this->table);
      return $result;
    }
    return FALSE;
  }

  /**
   * Set an error message
   *
   * @access  public
   * @param string
   * @return  void
   */
  function set_error($msg)
  {
    //TODO: using language class
    if ( is_array($msg) )
    {
      foreach ( $msg as $val )
      {
        $msg = $val;
        $this->error_msg[] = $msg;
        log_message('error', $msg);
      }
    }
    else
    {
      $msg = $msg;
      $this->error_msg[] = $msg;
      log_message('error', $msg);
    }
  }

  /**
   * Display the error message
   *
   * @access  public
   * @param string
   * @param string
   * @return  string
   */
  function display_errors($open = '<p>', $close = '</p>')
  {
    $str = '';
    foreach ( $this->error_msg as $val )
    {
      $str .= $open.$val.$close;
    }

    return $str;
  }

  /**
   * Set an info message
   *
   * @access  public
   * @param string
   * @return  void
   */
  function set_info($msg)
  {
    //TODO: using language class
    if ( is_array($msg) )
    {
      foreach ( $msg as $val )
      {
        $msg = $val;
        $this->info_msg[] = $msg;
        log_message('info', $msg);
      }
    }
    else
    {
      $msg = $msg;
      $this->info_msg[] = $msg;
      log_message('info', $msg);
    }
  }

  /**
   * Display the info message
   *
   * @access  public
   * @param string
   * @param string
   * @return  string
   */
  function display_infos($open = '<p>', $close = '</p>')
  {
    $str = '';
    foreach ( $this->info_msg as $val )
    {
      $str .= $open.$val.$close;
    }

    return $str;
  }

}

/* End of file MY_Model.php */
/* Location: ./system/application/libraries/MY_Model.php */
