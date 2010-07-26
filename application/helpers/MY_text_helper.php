<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Character Limiter
 *
 * Limits the string based on the character count.  Preserves complete words
 * so the character count may not be exactly as specified.
 *
 * @access  public
 * @param string
 * @param integer
 * @param string  the end character. Usually an ellipsis
 * @return  string
 */ 
if ( ! function_exists('character_limiter'))
{
  function character_limiter($str, $n = 500, $force=FALSE, $end_char = '&#8230;')
  {
    if (strlen($str) < $n)
    {
      return $str;
    }
    
    if ( $force )
    {
      $out = substr($str, 0, $n).$end_char;
      return $out;
    }
    else
    {
      $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));
  
      if (strlen($str) <= $n)
      {
        return $str;
      }
  
      $out = "";
      foreach (explode(' ', trim($str)) as $val)
      {
        $out .= $val.' ';
        
        if (strlen($out) >= $n)
        {
          $out = trim($out);
          return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
        }   
      }
    }
  }
}

/**
 * Enum display
 *
 * Display enum values by replace _ with space, and optionally uppercase first character
 *
 * @access  public
 * @param string
 * @param boolean
 * @return  string
 */ 
if ( ! function_exists('enum_display'))
{
  function enum_display($str, $ucfirst = TRUE)
  {
    $result = str_replace('_', ' ', $str);
    if ( $ucfirst )
    {
      $result = ucfirst($result);
    }
    return $result;
  }
}
  
// ------------------------------------------------------------------------

/* End of file MY_text_helper.php */
/* Location: ./system/application/helpers/MY_text_helper.php */
