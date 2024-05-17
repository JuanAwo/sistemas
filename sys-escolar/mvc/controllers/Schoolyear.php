<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schoolyear extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("schoolyear_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('schoolyear', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'schoolyear', 
				'label' => $this->lang->line("schoolyear_schoolyear"), 
				'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_schoolyear'
			), 
			array(
				'field' => 'schoolyeartitle', 
				'label' => $this->lang->line("schoolyear_schoolyeartitle"),
				'rules' => 'trim|xss_clean|max_length[128]|callback_unique_schoolyeartitle',
			),
			array(
				'field' => 'semestercode', 
				'label' => $this->lang->line("schoolyear_semestercode"),
				'rules' => 'trim|xss_clean|max_length[11]|numeric'
			)
		);
		return $rules;
	}

	public function index() { 
		$usertype = $this->session->userdata("usertype");
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$this->data['set'] = $id;
		if($id == '') {
			$this->data['schoolyears'] = $this->schoolyear_m->get_order_by_schoolyear(array('schooltype' => $this->data['siteinfos']->school_type));
			$this->data['set'] = $this->data['siteinfos']->school_type;
			$this->data["subview"] = "schoolyear/index";
			$this->load->view('_layout_main', $this->data);
		} elseif($id == 'classbase' || $id == 'semesterbase') {
			$this->data['schoolyears'] = $this->schoolyear_m->get_order_by_schoolyear(array('schooltype' => $id));
			$this->data["subview"] = "schoolyear/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
		
	}

	function schoolyear_list() {
		$schoolyearID = $this->input->post('schoolyearID');
		if($schoolyearID) {
			$string = base_url("schoolyear/index/$schoolyearID");
			echo $string;
		} else {
			redirect(base_url("schoolyear/index"));
		}
	}

	public function add() {
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) { 
				$this->data["subview"] = "schoolyear/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"schooltype" => $this->data['siteinfos']->school_type,
					"schoolyear" => $this->input->post("schoolyear"),
					"schoolyeartitle" => $this->input->post("schoolyeartitle"),
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_username" => $this->session->userdata('username'),
					"create_usertype" => $this->session->userdata('usertype')
				);

				if($this->data['siteinfos']->school_type == 'semesterbase') {
					$array['semestercode'] = $this->input->post("semestercode");
				}

				$this->schoolyear_m->insert_schoolyear($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("schoolyear/index"));
			}
		} else {
			$this->data["subview"] = "schoolyear/add";
			$this->load->view('_layout_main', $this->data);
		}	
	}

	public function edit() {
		$usertype = $this->session->userdata("usertype");
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['schoolyear'] = $this->schoolyear_m->get_schoolyear($id);
			if($this->data['schoolyear']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "schoolyear/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"schoolyear" => $this->input->post("schoolyear"),
							"schoolyeartitle" => $this->input->post("schoolyeartitle"),
							"modify_date" => date("Y-m-d h:i:s")
						);

						if($this->data['siteinfos']->school_type == 'semesterbase') {
							$array['semestercode'] = $this->input->post("semestercode");
						}

						$this->schoolyear_m->update_schoolyear($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("schoolyear/index"));
					}
				} else {
					$this->data["subview"] = "schoolyear/edit";
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
			$schoolyear = $this->schoolyear_m->get_schoolyear($id);
			if($schoolyear) {
				if($schoolyear->schoolyearID != 1) {
					$this->schoolyear_m->delete_schoolyear($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("schoolyear/index"));
				} else {
					redirect(base_url("schoolyear/index"));
				}
			} else {
				redirect(base_url("schoolyear/index"));
			}
		} else {
			redirect(base_url("schoolyear/index"));
		}
	}

	function valid_number() {
		if($this->input->post('semestercode') < 0) {
			$this->form_validation->set_message("valid_number", "%s es un número invalido.");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_schoolyear() {
		if($this->input->post('schoolyear') && $this->input->post('schoolyeartitle') == '') {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$schoolyear = $this->schoolyear_m->get_schoolyear_where_not($this->input->post("schoolyear"), $id);
				if(count($schoolyear)) {
					$this->form_validation->set_message("unique_schoolyear", "%s ya existe.");
					return FALSE;
				}
				return TRUE;
			} else {
				$schoolyear = $this->schoolyear_m->get_schoolyear_where($this->input->post('schoolyear'));
				if(count($schoolyear)) {
					$this->form_validation->set_message("unique_schoolyear", "%s ya existe.");
					return FALSE;
				}
				return TRUE;

			}
		} 
		return TRUE;
	}

	public function unique_schoolyeartitle() {
		if($this->input->post('schoolyeartitle') && $this->input->post('schoolyear')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$schoolyeartitle = $this->schoolyear_m->get_order_by_schoolyear(array("schoolyear" => $this->input->post("schoolyear"), 'schoolyeartitle' => $this->input->post('schoolyeartitle'),
					'schoolyearID !=' => $id ));
				if(count($schoolyeartitle)) {
					$this->form_validation->set_message("unique_schoolyeartitle", "%s ya existe.");
					return FALSE;
				}
				return TRUE;
			} else {
				$schoolyeartitle = $this->schoolyear_m->get_order_by_schoolyear(array("schoolyear" => $this->input->post("schoolyear"), 'schoolyeartitle' => $this->input->post('schoolyeartitle')));

				if(count($schoolyeartitle)) {
					$this->form_validation->set_message("unique_schoolyeartitle", "%s ya existe.");
					return FALSE;
				}
				return TRUE;

			}
		}
		return TRUE;
	}

	function toggleschoolyear() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->session->set_userdata(array('defaultschoolyearID' => $id));
			redirect($_SERVER['HTTP_REFERER']);
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */