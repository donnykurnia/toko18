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
 * CodeIgniter Form Helpers Add On
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Donny Kurnia
 * @link		http://codeigniter.com/user_guide/helpers/form_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Create Enum Options to be used in form_dropdown
 *
 * @access	public
 * @param	array	an array from enum
 * @return	array
 */
if ( ! function_exists('enum_options'))
{
	function enum_options($enum_arr = array(), $prefix_value=array())
	{
		$result = array();
		if ( is_array($prefix_value) )
		{
			$result = $prefix_value;
		}
		if ( is_array($enum_arr) )
		{
			foreach ( $enum_arr as $row )
			{
			  $result[$row] = ucwords(str_replace('_', ' ', $row));
			}
		}
		return $result;
	}
}

// ------------------------------------------------------------------------


/* End of file MY_form_helper.php */
/* Location: ./application/helpers/MY_form_helper.php */