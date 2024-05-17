<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Markpercentage extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("markpercentage_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('markpercentage', $language);	
	}

	public function index() {
		$usertype = $this->session->userdata("usertype");
		$this->data['markpercentage'] = $this->markpercentage_m->get_markpercentage();
		$this->data["subview"] = "markpercentage/index";
		$this->load->view('_layout_main', $this->data);
		
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'markpercentagetype', 
				'label' => $this->lang->line("markpercentage_markpercentagetype"), 
				'rules' => 'trim|required|xss_clean|max_length[100]|callback_unique_markpercentage'
			),
			array(
				'field' => 'percentage', 
				'label' => $this->lang->line("markpercentage_percentage"), 
				'rules' => 'trim|required|xss_clean|max_length[3]'
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
				$this->data["subview"] = "markpercentage/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"markpercentagetype" => $this->input->post("markpercentagetype"),
					"percentage" => $this->input->post("percentage"),
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_username" => $this->session->userdata('username'),
					"create_usertype" => $this->session->userdata('usertype')
				);
				$this->markpercentage_m->insert_markpercentage($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("markpercentage/index"));
			}
		} else {
			$this->data["subview"] = "markpercentage/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$usertype = $this->session->userdata("usertype");
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['markpercentage'] = $this->markpercentage_m->get_markpercentage($id);
			if($this->data['markpercentage']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "markpercentage/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"markpercentagetype" => $this->input->post("markpercentagetype"),
							"percentage" => $this->input->post("percentage"),
							"modify_date" => date("Y-m-d h:i:s")
						);

						$oldfieloption = 'mark_'. str_replace(' ', '', $this->data['markpercentage']->markpercentagetype);
						$olddata = $this->setting_m->get_setting_where($oldfieloption); 
						if(count($olddata)) {
							$this->setting_m->delete_setting($oldfieloption);
							$newdata = array(
								'fieldoption' => 'mark_'. str_replace(' ', '', $this->input->post('markpercentagetype')),
								'value' => $olddata->value
							);
							$this->setting_m->insert_setting($newdata); 
						}


						$this->markpercentage_m->update_markpercentage($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("markpercentage/index"));
					}
				} else {
					$this->data["subview"] = "markpercentage/edit";
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
			$this->data['markpercentage'] = $this->markpercentage_m->get_markpercentage($id);
			if($this->data['markpercentage']) {
				if($this->data['markpercentage']->markpercentageID != 1) {
					$oldfieloption = 'mark_'. str_replace(' ', '', $this->data['markpercentage']->markpercentageID);

					$olddata = $this->setting_m->get_setting_where($oldfieloption);

					if(count($olddata)) {
						$this->setting_m->delete_setting($oldfieloption);
					}

					$this->markpercentage_m->delete_markpercentage($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("markpercentage/index"));
				} else {
					redirect(base_url("markpercentage/index"));
				}
			} else {
				redirect(base_url("markpercentage/index"));
			}
		} else {
			redirect(base_url("markpercentage/index"));
		}	

	}

	public function unique_markpercentage() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$markpercentagetype = $this->markpercentage_m->get_order_by_markpercentage(array("markpercentagetype" => $this->input->post("markpercentagetype"), 'markpercentageID !=' => $id));

			if(count($markpercentagetype)) {
				$this->form_validation->set_message("unique_markpercentage", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		} else {
			$markpercentagetype = $this->markpercentage_m->get_order_by_markpercentage(array("markpercentagetype" => $this->input->post("markpercentagetype")));

			if(count($markpercentagetype)) {
				$this->form_validation->set_message("unique_markpercentage", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		}	
	}


}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */