<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usertype extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("usertype_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('usertype', $language);	
	}

	public function index() {
		$usertype = $this->session->userdata("usertype");
		$this->data['usertypes'] = $this->usertype_m->get_usertype();
		$this->data["subview"] = "usertype/index";
		$this->load->view('_layout_main', $this->data);
		
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'usertype', 
				'label' => $this->lang->line("usertype_usertype"), 
				'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_usertype'
			)
		);
		return $rules;
	}

	public function add() {
		$usertype = $this->session->userdata("usertype");
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) { 
				$this->data["subview"] = "usertype/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"usertype" => $this->input->post("usertype"),
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_username" => $this->session->userdata('username'),
					"create_usertype" => $this->session->userdata('usertype')
				);
				$this->usertype_m->insert_usertype($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("usertype/index"));
			}
		} else {
			$this->data["subview"] = "usertype/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$usertype = $this->session->userdata("usertype");
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['usertype'] = $this->usertype_m->get_usertype($id);
			if($this->data['usertype']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "usertype/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"usertype" => $this->input->post("usertype"),
							"modify_date" => date("Y-m-d h:i:s")
						);

						$this->usertype_m->update_usertype($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("usertype/index"));
					}
				} else {
					$this->data["subview"] = "usertype/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() {
		$usertype = $this->session->userdata("usertype");
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['usertype'] = $this->usertype_m->get_usertype($id);
			if($this->data['usertype']) {
				$reletionarray = array(1,2,3,4,5,6,7);
				if(!in_array($this->data['usertype']->usertypeID, $reletionarray)) {
					$this->usertype_m->delete_usertype($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("usertype/index"));
				} else {
					redirect(base_url("usertype/index"));
				}
			} else {
				redirect(base_url("usertype/index"));
			}
		} else {
			redirect(base_url("usertype/index"));
		}	

	}

	public function unique_usertype() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$usertype = $this->usertype_m->get_order_by_usertype(array("usertype" => $this->input->post("usertype"), "usertypeID !=" => $id));
			if(count($usertype)) {
				$this->form_validation->set_message("unique_usertype", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		} else {
			$usertype = $this->usertype_m->get_order_by_usertype(array("usertype" => $this->input->post("usertype")));

			if(count($usertype)) {
				$this->form_validation->set_message("unique_usertype", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		}	
	}


}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */