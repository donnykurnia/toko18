<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('adodb_firephp_logging'))
{
/**
* FirePHP Log Handler.
*
* @param $debug_output  the logging string from AdoDB as html
* @param $newline   force newline
*/
function adodb_firephp_logging($debug_output, $newline)
{
  $log = array();
  foreach(explode("\n", htmlspecialchars_decode(strip_tags($debug_output))) as $line)
  {
    $line = trim($line);
    if($line != '') $log[] = array($line);
  }
  $title = array_shift($log);

  //load firephp
  $CI =& get_instance();
  $CI->load->library('firephp');
  //output
  $CI->firephp->table($title[0], $log);
}

}

/* End of file adodb_firephp_helper.php */
/* Location: ./system/application/helpers/adodb_firephp_helper.php */