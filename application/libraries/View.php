<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * View Object
 *
 * Renders a layout with partials (blocks) inside.
 * Renders partials only (header,content,footer. etc).
 * Allows a plugin or module to render a partial.
 *
 * The source directory can be specified.
 * Works with Matchbox modules.
 *
 * Version 2.1.0 Wiredesignz (c) 2008-02-18
 **/
class View
{
  var $layout, $data, $partials = array();

  function View($params = array('layout' => NULL))            //specify a layout file to use
  {
    $this->layout = $params['layout'];

    static $data = array(
      'directory' => '',
      'module'    => '',
    );
    $this->data =& $data;            //data is static
  }

  // --------------------------------------------------------------------

  /**
   * Initialize Preferences
   *
   * @access    public
   * @param     array    initialization parameters
   * @return    void
   */
  function initialize($params = array())
  {
    if (count($params) > 0)
    {
      foreach ($params as $key => $val)
      {
        if (isset($this->$key))
        {
          $this->$key = $val;
        }
      }
    }
  }

  function load($views, $data = NULL)    //store partials as nested objects
  {
    if (is_array($views))
    {
      foreach ($views as $k => $v)
      {
        $this->set($k, $v);
      }
    }
    else $this->set(NULL, $views);

    if (is_array($data))
    {
      $this->data($data);
    }
  }

  function set($view, $file)            //create or overwrite a partial
  {
    $this->partials[$view] = (is_object($file)) ? $file : new View(array('layout' =>$file));
  }

  function data($data, $value = NULL)    //store data for this view
  {
    if (is_array($data))
    {
      $this->data = array_merge($this->data, $data);
    }
    elseif ($value != NULL)
    {
      $this->data[$data] = $value;
    }
  }

  function fetch($key = NULL)            //returns data value(s)
  {
    return ($key) ? (isset($this->data[$key])) ? $this->data[$key] : NULL : $this->data;
  }

  function render($render = FALSE)    // create the page
  {
    if ($this->layout)
    {
      $ci = & get_instance();
      $ci->load->vars($this->partials);
      return $ci->load->view($this->data['directory'].$this->layout, $this->data, $render, $this->data['module']);
    }
    else
    {
      foreach($this->partials as $k => $v)
      {
        $v->render();
      }
    }
  }
}

/* End of file View.php */
/* Location: ./system/application/libraries/View.php */