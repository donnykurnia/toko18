<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// $Id$
//

/**
 *
 * @author donny
 */
class MY_Parser extends CI_Parser {

  /**
   *  Parse a template from supplied string
   *
   * Parses pseudo-variables contained in the specified template,
   * replacing them with the data in the second param
   *
   * @access  public
   * @param string
   * @param array
   * @param bool
   * @return  string
   */
  function parse_string($template='', $data=array(), $return = FALSE)
  {
    $CI =& get_instance();
    $template = trim($template);

    if ($template == '')
    {
      return FALSE;
    }

    foreach ($data as $key => $val)
    {
      if (is_array($val))
      {
        $template = $this->_parse_pair($key, $val, $template);
      }
      else
      {
        $template = $this->_parse_single($key, (string)$val, $template);
      }
    }

    if ($return == FALSE)
    {
      $CI->output->append_output($template);
    }

    return $template;
  }

  // --------------------------------------------------------------------

  /**
   *  Check whether variable exist in template or not
   *
   * @access  private
   * @param string
   * @param string
   * @return  mixed
   */
  function _is_exist_variable($string, $variable)
  {
    if ( ! preg_match("|".$this->l_delim . $variable . $this->r_delim."|s", $string, $match))
    {
      return FALSE;
    }

    return $match;
  }
  
  function enclose_vars($var)
  {
    return "{$this->l_delim}{$var}{$this->r_delim}";
  }
  
}

/* End of file MY_Parser.php */
/* Location: ./system/application/libraries/MY_Parser.php */