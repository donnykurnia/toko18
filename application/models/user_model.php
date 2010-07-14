<?php
// $Id$
//

/**
 *
 * @author donny
 * @property CI_DB_active_record $db
 * @property CI_DB_result $db
 * @property CI_DB_forge $dbforge
 * @property Firephp $firephp
 */
class User_model extends MY_Model {

  function User_model()
  {
    // Call the Model constructor
    parent::MY_Model();
    $this->table = 'user';
    $this->primary_key = 'id';
  }

  /**
   *
   * @return boolean
   */
  function check_login()
  {
    return ( $this->session->userdata('user_id') !== FALSE );
  }

  /**
   *
   * @return boolean
   */
  function check_is_administrator()
  {
    return ( $this->session->userdata('user_role') == 'administrator' );
  }

  /**
   *
   * @param $username string
   * @param $user_password string
   * @return user_data or FALSE
   */
  function login($username='', $user_password='')
  {
    $username = trim($username);
    $user_password = trim($user_password);
    if ( $username != '' AND $user_password != '' )
    {
      $username_q = $this->db->escape($username);
      //check admin
      $condition = " username={$username_q} ";
      $result = $this->get_all($condition);
      if ( $result AND count($result) == 1 )
      {
        //set session
        $user_data = $result[0];
        //check the password
        $password_check = sha1($user_data['password_salt'].$user_password);
        if ( $password_check == $user_data['user_password'] )
        {
          $session = array(
            'user_id'   => $user_data['id'],
            'username'  => $user_data['username'],
            'user_role' => $user_data['user_role']
          );
          $this->session->set_userdata($session);
          //update user data in database
          $this->update($user_data['id'], array(
            'last_login_datetime' => date('Y-m-d H:i:s'),
            'last_login_ip' => inet_aton($this->CI->input->ip_address())
          ));
          return $user_data;
        }
      }
      $this->set_error('Wrong combination of username and password!');
    }
    else
    {
      $this->set_error('Please fill in username and password!');
    }
    return FALSE;
  }

  /**
   * Destroy login session data
   * @return TRUE
   */
  function logout()
  {
    $session = array(
      'user_id'   => '',
      'username'  => '',
      'user_role' => ''
    );
    $this->session->unset_userdata($session);
    return TRUE;
  }

  /**
   * return username in session
   */
  function session_user_id()
  {
    return $this->session->userdata('user_id');
  }

  /**
   * return username in session
   */
  function session_username()
  {
    return $this->session->userdata('username');
  }

  /**
   * Check if username already registered in database
   * @param $username string
   * @param $id int
   * @return boolean
   */
  function is_username_exist($username='', $id=0)
  {
    //sanitize
    $username = trim($username);
    $id = intval($id);
    if ( $username != '' )
    {
      $username_e = $this->db->escape($username);
      $count = $this->get_total(" username={$username_e} AND id<>{$id} ");
      return ($count > 0);
    }
    return TRUE;
  }

  /**
   * Delete user and all related data
   * @param $id integer
   * @return boolean
   */
  function delete($id=0)
  {
    $id = intval($id);
    $detail = $this->get_detail($id);
    if ( $id == $this->session->userdata('user_id') )
    {
      $this->set_error('Cannot delete yourself!');
      return FALSE;
    }
    elseif ( ! $this->check_is_administrator() )
    {
      $this->set_error('Not authorized!');
      return FALSE;
    }
    else
    {
      $result = parent::delete($id);
      if ( $result )
      {
        //delete related data
      }
      return $result;
    }
  }

}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */