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

// ------------------------------------------------------------------------

/**
 * Create URL Title
 *
 * Takes a "title" string as input and creates a
 * human-friendly URL string with either a dash
 * or an underscore as the word separator.
 *
 * @access  public
 * @param string  the string
 * @param string  the separator: dash, or underscore
 * @return  string
 */
if ( ! function_exists('url_title'))
{
  function url_title($str, $separator = 'dash', $lowercase = FALSE)
  {
    if ($separator == 'dash')
    {
      $search   = '_';
      $replace  = '-';
    }
    else
    {
      $search   = '-';
      $replace  = '_';
    }

    $trans = array(
            '&\#\d+?;'        => '',
            '&\S+?;'          => '',
            '\s+'             => $replace,
            'ñ'               => 'n',
            '\.'              => '',
            '[^a-z0-9\-\._]'  => '',
            $replace.'+'      => $replace,
            $replace.'$'      => $replace,
            '^'.$replace      => $replace,
            '\.+$'            => ''
            );

    $str = strip_tags($str);

    foreach ($trans as $key => $val)
    {
      $str = preg_replace("#".$key."#i", $val, $str);
    }

    if ($lowercase === TRUE)
    {
      $str = strtolower($str);
    }
    
    return trim(stripslashes($str));
  }

}

/* End of file MY_url_helper.php */
/* Location: ./system/application/helpers/MY_url_helper.php */
