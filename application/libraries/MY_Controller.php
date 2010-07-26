<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// $Id$
//

/**
 *
 * @author donny
 * @property CI_Input $input
 */
class MY_Controller extends Controller {
  
  var $is_ajax_request = '';
  var $is_ajax_form = '';
  var $data = array();

  function MY_Controller()
  {
    parent::Controller();
    //initialize
    $this->is_ajax_request = ($this->input->server('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest');
    $this->is_ajax_form = ($this->input->post('ajax') == 'ajax');
    //default data
    $this->data['success_message']  = '';
    $this->data['error_message']    = '';
    $this->data['reload']           = FALSE;
    $this->data['new_content']      = FALSE;
    //constructor log
    log_message('debug', "MY_Controller Class Initialized");
  }

  function _redirect_in_list($redirect_to)
  {
    if ( $this->is_ajax_request )
    {
      //form submit
      if ( count($_POST) > 0 )
      {
        $this->data['reload'] = site_url($redirect_to);
        //return json data
        $this->output->set_output(json_encode($this->data));
        return;
      }
      //not a form submit
      redirect_js($redirect_to);
      return;
    }
    //not an ajax
    redirect($redirect_to);
  }

  function _redirect_in_form($redirect_to)
  {
    if ( $this->is_ajax_request )
    {
      $this->data['reload'] = site_url($redirect_to);
      //return json data
      $this->output->set_output(json_encode($this->data));
      return;
    }
    //not an ajax
    redirect($redirect_to);
  }

  function _redirect_in_form_wrap($redirect_to)
  {
    if ( $this->is_ajax_request OR $this->is_ajax_form )
    {
      $this->data['reload'] = site_url($redirect_to);
      //return json data
      $this->output->set_output('<textarea>'.json_encode($this->data).'</textarea>');
      return;
    }
    //not an ajax
    redirect($redirect_to);
  }

}

/* End of file MY_Controller.php */
/* Location: ./system/application/controllers/MY_Controller.php */