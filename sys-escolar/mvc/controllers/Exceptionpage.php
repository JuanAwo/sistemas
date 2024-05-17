<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exceptionpage extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$language = $this->session->userdata('lang');
		$this->lang->load('exceptionpage', $language);	
	}

	public function index() {
		$this->data["subview"] = "exceptionpage/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function error() {
		$this->data["subview"] = "errorpermission";
		$this->load->view('_layout_main', $this->data);
	}
}

/* End of file book.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/book.php */