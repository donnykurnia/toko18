<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// $Id$
//

/**
 *
 * @author donny
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Email $email
 * @property CI_Form_validation $form_validation
 * @property CI_URI $uri
 * @property Firephp $firephp
 * @property ADOConnection $adodb
 */
class Front extends MY_Controller {

  function Front()
  {
    parent::MY_Controller();
    //load model
    $this->load->model('user_model');
  }

  /**
   *
   * @return void
   * @access public
   */
  function index()
  {
    //page title
    $this->data['title'] = 'Halaman depan';
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/front_index',
      'page_footer'  => 'default/_footer',
    ));
    $this->view->render();
  }

}

/* End of file front.php */
/* Location: ./system/application/controllers/front.php */