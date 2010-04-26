<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('format_vars'))
{
  /**
   *
   */
  function format_vars(&$item1, $key)
  {
    $CI =& get_instance();
    $CI->load->library('parser');
    $item1 = $CI->parser->enclose_vars($item1);
  }
  
}
