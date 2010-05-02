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
    $this->table = 'wb_user';
    $this->primary_key = 'uid';
  }

  /**
   *
   * @return boolean
   */
  function check_login()
  {
    return ( $this->session->userdata('uid') !== FALSE );
  }

  /**
   *
   * @return boolean
   */
  function check_is_admin()
  {
    return ( $this->session->userdata('is_admin') == TRUE  );
  }

  /**
   *
   * @param $email string
   * @param $password string
   * @return user_data or FALSE
   */
  function login($email='', $password='')
  {
    $email = trim($email);
    $password = trim($password);
    if ( $email != '' AND $password != '' )
    {
      $email_q = $this->db->escape($email);
      $password_q = $this->db->escape(sha1($password));
      //check admin
      $condition = " email=$email_q AND password=$password_q ";
      $result = $this->get_all($condition);
      if ( $result AND count($result) == 1 )
      {
        //set session
        $user_data = $result[0];
        $session = array(
          'uid'       => $user_data['uid'],
          'email'     => $user_data['email'],
          'is_admin'  => ($user_data['is_admin'] == 1)
        );
        $this->session->set_userdata($session);
        return $user_data;
      }
      else
      {
        $this->set_error('Wrong combination of email and password!');
      }
    }
    else
    {
      $this->set_error('Please fill in email and password!');
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
      'uid'       => '',
      'email'     => '',
      'is_admin'  => ''
    );
    $this->session->unset_userdata($session);
    return TRUE;
  }

  /**
   * Check if email already registered in database
   * @param $email string
   * @return boolean
   */
  function is_email_exist($email='')
  {
    $email = trim($email);
    if ( $email != '' )
    {
      $email_e = $this->db->escape($email);
      $count = $this->get_total(" email=$email_e ");
      return ($count > 0);
    }
    return FALSE;
  }

  /**
   * Delete user and all related data
   * @param $id integer
   * @return boolean
   */
  function delete($id=0)
  {
    $id = intval($id);
    $result = parent::delete($id);
    if ( $result )
    {
      //delete related data
      $this->CI->load->model('user_country_list_model');
      $this->CI->user_country_list_model->delete_by_user($id);
      $this->CI->load->model('user_reseller_list_model');
      $this->CI->user_reseller_list_model->delete_by_user($id);
      $this->CI->load->model('links_model');
      $this->CI->links_model->delete_by_user($id);
    }
    return $result;
  }

}

/* End of file user_model.php */
/* Location: ./system/application/models/user_model.php */