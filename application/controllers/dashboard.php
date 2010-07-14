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
class Dashboard extends MY_Controller {

  function Dashboard()
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
    //default data
    $redirect_to = 'user/login';
    //if not already login, redirect
    if ( ! $this->user_model->check_login() )
    {
      redirect($redirect_to);
    }

    $this->data['in_dashboard'] = TRUE;
    //page title
    $this->data['title'] = 'Halaman Dashboard';
    $this->data['page_title'] = 'Halaman Dashboard';
    //load view
    $this->load->library('view');
    $this->view->layout = 'default/layout/template';
    $this->view->data($this->data);
    $this->view->load(array(
      'page_header'  => 'default/_header',
      'main_content' => 'default/dashboard_index',
      'page_footer'  => 'default/_footer',
    ));
    $this->view->render();
  }

}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */