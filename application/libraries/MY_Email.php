<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// $Id$
//

/**
 *
 * @author donny
 */
class MY_Email extends CI_Email {

	/**
	 * SMTP Connect
	 *
	 * @access	private
	 * @param	string
	 * @return	string
	 */
	function _smtp_connect()
	{
		$this->_smtp_connect = @fsockopen($this->smtp_host,
										$this->smtp_port,
										$errno,
										$errstr,
										$this->smtp_timeout);

		if( ! is_resource($this->_smtp_connect))
		{
			$this->_set_error_message('email_smtp_error', $errno." ".$errstr);
			return FALSE;
		}

		$this->_set_error_message($this->_get_smtp_data());
		return $this->_send_command('hello');
	}
  
	// --------------------------------------------------------------------

	/**
	 * Send SMTP data
	 *
	 * @access	private
	 * @return	bool
	 */
	function _send_data($data)
	{
		if ( ! @fwrite($this->_smtp_connect, $data . $this->newline))
		{
			$this->_set_error_message('email_smtp_data_failure', $data);
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
  
	// --------------------------------------------------------------------

	/**
	 * Get SMTP data
	 *
	 * @access	private
	 * @return	string
	 */
	function _get_smtp_data()
	{
		$data = "";

		while ($str = @fgets($this->_smtp_connect, 512))
		{
			$data .= $str;

			if (substr($str, 3, 1) == " ")
			{
				break;
			}
		}

		return $data;
	}

}
// END MY_Email class

/* End of file MY_Email.php */
/* Location: ./application/libraries/MY_Email.php */