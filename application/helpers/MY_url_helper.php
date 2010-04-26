<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('redirect_js'))
{
  function redirect_js($location='', $is_in_thickbox=FALSE, $return=FALSE)
  {
    $code = '';
    $location = trim($location);
    if ($location != '')
    {
      $CI =& get_instance();
      $CI->load->helper('url');

      $code .= '<script type="text/javascript">//<![CDATA['."\n";
      $code .= '  jQuery(function($){'."\n";
      if ($is_in_thickbox)
      {
        $code .= '    tb_remove();'."\n";
      }
      $code .= '    window.top.location.assign("'.site_url($location).'");'."\n";
      $code .= '  });'."\n";
      $code .= '//]]></script>'."\n";
    }
    if (! $return)
    {
      echo $code;
      exit;
    }
    return $code;
  }

}

/* End of file MY_url_helper.php */
/* Location: ./system/application/helpers/MY_url_helper.php */
