<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('output_json'))
{
  /**
   * 
   * @param $data mixed
   * @return void
   */
  function output_json($data, $wrap_textarea=FALSE)
  {
    $CI =& get_instance();
    $CI->output->set_header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    $CI->output->set_header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
    $CI->output->set_header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    $CI->output->set_header ("Pragma: no-cache"); // HTTP/1.0
    $CI->output->set_header("Content-Type: application/json");
    $output = json_encode($data);
    if ($wrap_textarea) $output = "<textarea>$output</textarea>";
    $CI->output->set_output($output);
  }
}
