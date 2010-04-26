<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Date Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/date_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Converts time in timezone to GMT time
 *
 * Takes a Unix timestamp (in $timezone) as input, and returns
 * at the GMT value based on the timezone and DST setting
 * submitted
 *
 * @access	public
 * @param	integer Unix timestamp
 * @param	string	timezone
 * @param	bool	whether DST is active
 * @return	integer
 */	
if ( ! function_exists('timezone_to_gmt'))
{
	function timezone_to_gmt($time = '', $timezone = 'UTC', $dst = FALSE)
	{			
		if ($time == '')
		{
			return now();
		}
	
		$time -= timezones($timezone) * 3600;

		if ($dst == TRUE)
		{
			$time -= 3600;
		}
	
		return $time;
	}
}

// ------------------------------------------------------------------------

/**
 * Converts time in timezone to GMT time
 *
 * Takes a Unix timestamp (in $timezone) as input, and returns
 * at the GMT value based on the timezone and DST setting
 * submitted
 *
 * @access	public
 * @param	integer Unix timestamp
 * @param	string	timezone
 * @param	bool	whether DST is active
 * @return	integer
 */	
if ( ! function_exists('gmt_to_server'))
{
	function gmt_to_server($time = '', $dst = FALSE)
	{			
		if ($time == '')
		{
			return now();
		}
	
		$time += date('Z');

		if ($dst == TRUE)
		{
			$time += 3600;
		}
	
		return $time;
	}
}


/* End of file MY_date_helper.php */
/* Location: ./system/application/helpers/MY_date_helper.php */