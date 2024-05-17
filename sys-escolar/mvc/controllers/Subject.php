<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Subject extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("subject_m");
		$this->load->model("student_info_m");
		$this->load->model("parents_info_m");
		$this->load->model("parents_m");
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model("student_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('subject', $language);	
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		$usertypeID = $this->session->userdata("usertypeID");
		if($usertypeID == 3) {
			$student = $this->student_info_m->get_student_info();
			$this->data['subjects'] = $this->student_info_m->get_join_where_subject($student->classesID);
			$this->data['set'] = $student->classesID;
			$this->data["subview"] = "subject/index";
			$this->load->view('_layout_main', $this->data);
		} elseif($usertypeID == 4) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$username = $this->session->userdata("username");
			$parent = $this->parents_m->get_single_parents(array('username' => $username));
			$this->data['students'] = $this->student_m->get_order_by_student(array('parentID' => $parent->parentsID, 'schoolyearID' => $schoolyearID));
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$checkstudent = $this->student_m->get_single_student(array('studentID' => $id));
				if(count($checkstudent)) {
					$classesID = $checkstudent->classesID;
					$this->data['set'] = $id;
					$this->data['subjects'] = $this->subject_m->get_join_subject($classesID);
					$this->data["subview"] = "subject/index_parent";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "subject/search_parent";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['set'] = $id;
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data['subjects'] = $this->subject_m->get_join_subject($id);
				$this->data["subview"] = "subject/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["subview"] = "subject/search";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	protected function rules() {
		$rules = array(
				array(
					'field' => 'classesID', 
					'label' => $this->lang->line("subject_class_name"), 
					'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_allclasses'
				),
				array(
					'field' => 'teacherID', 
					'label' => $this->lang->line("subject_teacher_name"), 
					'rules' => 'trim|required|xss_clean|max_length[60]|callback_allteacher'
				),
				array(
					'field' => 'type', 
					'label' => $this->lang->line("subject_type"), 
					'rules' => 'trim|required|xss_clean|max_length[11]|callback_alltype'
				),
				array(
					'field' => 'passmark', 
					'label' => $this->lang->line("subject_passmark"), 
					'rules' => 'trim|required|xss_clean|max_length[11]|numeric'
				),
				array(
					'field' => 'finalmark', 
					'label' => $this->lang->line("subject_finalmark"), 
					'rules' => 'trim|required|xss_clean|max_length[11]|numeric'
				),
				array(
					'field' => 'subject', 
					'label' => $this->lang->line("subject_name"), 
					'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_subject'
				), 
				array(
					'field' => 'subject_author', 
					'label' => $this->lang->line("subject_author"), 
					'rules' => 'trim|xss_clean|max_length[100]'
				), 
				array(
					'field' => 'subject_code', 
					'label' => $this->lang->line("subject_code"),
					'rules' => 'trim|required|max_length[20]|xss_clean|callback_unique_subject_code'
				),
			);
		return $rules;
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		$this->data['classes'] = $this->subject_m->get_classes();
		$this->data['teachers'] = $this->teacher_m->get_teacher();
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) { 
				$this->data["subview"] = "subject/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$teacher = $this->teacher_m->get_teacher($this->input->post("teacherID"));
				$array = array(
					"classesID" => $this->input->post("classesID"),
					"teacherID" => $this->input->post("teacherID"),
					"subject" => $this->input->post("subject"),
					'type' => $this->input->post('type'),
					'passmark' => $this->input->post('passmark'),
					'finalmark' => $this->input->post('finalmark'),
					"subject_author" => $this->input->post("subject_author"),
					"subject_code" => $this->input->post("subject_code"),
					"teacher_name" => $teacher->name,
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_username" => $this->session->userdata('username'),
					"create_usertype" => $this->session->userdata('usertype')
				);
				$this->subject_m->insert_subject($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("subject/index"));
			}
		} else {
			$this->data["subview"] = "subject/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && (int)$url) {
			$this->data['classes'] = $this->subject_m->get_classes();
			$this->data['teachers'] = $this->teacher_m->get_teacher();
			$this->data['subject'] = $this->subject_m->get_subject($id);
			if($this->data['subject']) {
				$this->data['set'] = $url;
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data['form_validation'] = validation_errors(); 
						$this->data["subview"] = "subject/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$teacher = $this->teacher_m->get_teacher($this->input->post("teacherID"));
						$array = array(
							"classesID" => $this->input->post("classesID"),
							"teacherID" => $this->input->post("teacherID"),
							"subject" => $this->input->post("subject"),
							'type' => $this->input->post('type'),
							'passmark' => $this->input->post('passmark'),
							'finalmark' => $this->input->post('finalmark'),
							"subject_author" => $this->input->post("subject_author"),
							"subject_code" => $this->input->post("subject_code"),
							"teacher_name" => $teacher->name,
							"modify_date" => date("Y-m-d h:i:s")
						);
						
						$this->subject_m->update_subject($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("subject/index/$url"));
					}
				} else {
					$this->data["subview"] = "subject/edit";
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
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && (int)$url) {
			$subject = $this->subject_m->get_subject($id);
			if(count($subject)) {
				$this->subject_m->delete_subject($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("subject/index/$url"));
			} else {
				redirect(base_url("subject/index"));
			}
		} else {
			redirect(base_url("subject/index"));
		}
	}

	public function unique_subject() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$subject = $this->subject_m->get_order_by_subject(array("subject" => $this->input->post("subject"), "subjectID !=" => $id, "classesID" => $this->input->post("classesID")));
			if(count($subject)) {
				$this->form_validation->set_message("unique_subject", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		} else {
			$subject = $this->subject_m->get_order_by_subject(array("subject" => $this->input->post("subject"), "classesID" => $this->input->post("classesID"), "subject_code" => $this->input->post("subject_code")));

			if(count($subject)) {
				$this->form_validation->set_message("unique_subject", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		}	
	}

	public function unique_subject_code() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$subject = $this->subject_m->get_order_by_subject(array("subject_code" => $this->input->post("subject_code"), "subjectID !=" => $id));
			if(count($subject)) {
				$this->form_validation->set_message("unique_subject_code", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		} else {
			$subject = $this->subject_m->get_order_by_subject(array("subject_code" => $this->input->post("subject_code")));

			if(count($subject)) {
				$this->form_validation->set_message("unique_subject_code", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		}	
	}

	public function subject_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("subject/index/$classID");
			echo $string;
		} else {
			redirect(base_url("subject/index"));
		}
	}

	public function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("subject/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("subject/index"));
		}
	}

	function allclasses() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("allclasses", "%s es requerido");
	     	return FALSE;
		}
		return TRUE;
	}

	public function allteacher() {
		if($this->input->post('teacherID') === '0') {

			$this->form_validation->set_message("allteacher", "%s es requerido");
	     	return FALSE;
		}
		return TRUE;
	}

	function alltype() {
		if($this->input->post('type') == 'select') {
			$this->form_validation->set_message("alltype", "%s es requerido");
	     	return FALSE;
		}
		return TRUE;
	}
}

/* End of file subject.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/subject.php */