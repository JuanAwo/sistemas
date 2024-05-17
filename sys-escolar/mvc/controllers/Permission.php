<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("permission_m");
		$this->load->model("usertype_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('permission', $language);	
	}

	public function index() {
 		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$usertype = $this->usertype_m->get_usertype($id);
				if(count($usertype)) {

				$this->data['set'] = $id;
				$this->data['usertypes'] = $this->usertype_m->get_usertype();
				$this->data['permissions'] = $this->permission_m->get_modules_with_permission($id);
				if(empty($this->data['permissions'])) {
					$this->data['permissions'] = NULL;
				}
				$this->data["subview"] = "permission/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data['usertypes'] = $this->usertype_m->get_usertype();
			$this->data["subview"] = "permission/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function permission_list() {
		$usertypeID = $this->input->post('usertypeID');
		if((int)$usertypeID) {
			$string = base_url("permission/index/$usertypeID");
			echo $string;
		} else {
			redirect(base_url("permission/index"));
		}
	}

	public function save() {
		$this->session->userdata('usertype');
		$usertypeID = $this->uri->segment(3);
		if ((int)$usertypeID) {
			$usertype = $this->usertype_m->get_usertype($usertypeID);
			if(count($usertype)) {
				if ($this->permission_m->delete_all_permission($usertypeID)) {
					foreach ($_POST as $key => $value) {
						$array = array();
						$array['permission_id'] = $value;
						$array['usertype_id'] = $usertypeID;
						$this->permission_m->insert_relation($array);
					}
					redirect(base_url('permission/index/'.$usertypeID),'refresh');
				} else {
					redirect(base_url('permission/index/'.$usertypeID),'refresh');
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			redirect(base_url('permission/index/'.$usertypeID),'refresh');
		}
	}
}
