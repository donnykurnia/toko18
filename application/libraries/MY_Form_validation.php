<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * MY Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Validation
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/form_validation.html
 */
class MY_Form_validation extends CI_Form_validation {

	/**
	 * Constructor
	 *
	 */
	function MY_Form_validation($rules = array())
	{
	  parent::CI_Form_validation($rules);
	}

	// --------------------------------------------------------------------

	/**
	 * Valid Date Ymd
   * Determine if date is a valid date
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function valid_date_Ymd($date='')
  {
    //sanitize
    $date = trim($date);
    //return ( ! preg_match("#^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$#", $date)) ? FALSE : TRUE;
    return ( $date === date("Y-m-d", strtotime($date)) );
  }

}
// END Form Validation Class

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */