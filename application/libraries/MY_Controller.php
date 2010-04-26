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

}

/* End of file MY_Controller.php */
/* Location: ./system/application/controllers/MY_Controller.php */