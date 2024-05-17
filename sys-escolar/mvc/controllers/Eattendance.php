<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eattendance extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("exam_m");
		$this->load->model('subject_m');
		$this->load->model("eattendance_m");
		$this->load->model("classes_m");
		$this->load->model("section_m");
		$this->load->model('parents_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('eattendance', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID', 
				'label' => $this->lang->line("eattendance_exam"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_check_exam'
			), 
			array(
				'field' => 'classesID', 
				'label' => $this->lang->line("eattendance_classes"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_check_classes'
			), 
			array(
				'field' => 'sectionID', 
				'label' => $this->lang->line("eattendance_section"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_check_section'
			), 
			array(
				'field' => 'subjectID', 
				'label' => $this->lang->line("eattendance_subject"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_check_subject'
			)
		);
		return $rules;
	}

	protected function rulessearch() {
		$rules = array(
			array(
				'field' => 'examID', 
				'label' => $this->lang->line("eattendance_exam"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_check_exam'
			), 
			array(
				'field' => 'classesID', 
				'label' => $this->lang->line("eattendance_classes"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_check_classes'
			), 
			array(
				'field' => 'subjectID', 
				'label' => $this->lang->line("eattendance_subject"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_check_subject'
			)
		);
		return $rules;
	}

	protected function rulessearchparents() {
		$rules = array(
			array(
				'field' => 'examID', 
				'label' => $this->lang->line("eattendance_exam"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_check_exam'
			), 
			array(
				'field' => 'studentID', 
				'label' => $this->lang->line("eattendance_student"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_check_student'
			)
		);
		return $rules;
	}


	public function index() {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 3) {
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
			$this->data['students'] = array();
			$username = $this->session->userdata('username');
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['exams'] = $this->exam_m->get_exam();
			$student = $this->student_m->get_single_student(array('username' => $username));
			$this->data['set'] = $id;
			if(count($student)) {
				if((int)$id) {
					$this->data['students'] = $this->eattendance_m->get_eattendance_with_student($id, $student->studentID, $schoolyearID);
					$this->data["subview"] = "eattendance/index_parents";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "eattendance/index_parents";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} elseif($usertypeID == 4) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);
			$this->data['students'] = array();
			$username = $this->session->userdata('username');
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['exams'] = $this->exam_m->get_exam();

			
			$parents = $this->parents_m->get_single_parents(array('username' => $username));
			if($parents) {
				$this->data['parentsStudents'] = $this->student_m->get_order_by_student(array('parentID' => $parents->parentsID, 'schoolyearID' => $schoolyearID));
			} else {
				$this->data['parentsStudents'] = array();
			}
			
			if($_POST) {
				$rules = $this->rulessearchparents();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) { 
					$this->data["subview"] = "eattendance/index_parents";
					$this->load->view('_layout_main', $this->data);			
				} else {
					$examID = $this->input->post("examID");
					$studentID = $this->input->post('studentID');
					$this->data['students'] = $this->eattendance_m->get_eattendance_with_student($examID, $studentID, $schoolyearID);
					$this->data["subview"] = "eattendance/index_parents";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "eattendance/index_parents";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['exams'] = $this->exam_m->get_exam();
			$this->data['classes'] = $this->classes_m->get_classes();
			$classesID = $this->input->post("classesID");
			if($classesID != 0) {
				$this->data['subjects'] = $this->subject_m->get_order_by_subject(array("classesID" => $classesID));
			} else {
				$this->data['subjects'] = "empty";
			}
			$this->data['subjectID'] = 0;
			$this->data['students'] = array();
			$year = date("Y");

			if($_POST) {
				$rules = $this->rulessearch();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) { 
					$this->data["subview"] = "eattendance/index";
					$this->load->view('_layout_main', $this->data);			
				} else {
					$examID = $this->input->post("examID");
					$classesID = $this->input->post("classesID");
					$subjectID = $this->input->post("subjectID");
					$date = date("Y-m-d");


					$this->data['eattendances'] = $this->eattendance_m->get_order_by_eattendance(array("examID" => $examID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, "subjectID" => $subjectID));
					if(count($this->data['eattendances'])) {
						$this->data['students'] = $this->student_m->get_order_by_student(array("classesID" => $classesID, 'schoolyearID' => $schoolyearID));

						if(count($this->data['students'])) {

							if($this->data['students']) {
								$sections = $this->section_m->get_order_by_section(array("classesID" => $classesID));
								$this->data['sections'] = $sections;
								foreach ($sections as $key => $section) {
									$this->data['allsection'][$section->section] = $this->student_m->get_order_by_student(array('schoolyearID' => $schoolyearID, 'classesID' => $classesID, "sectionID" => $section->sectionID));
								}
							} else {
								$this->data['students'] = NULL;
							}

							$students = $this->data['students'];
							foreach ($students as $key => $student) {
								$section = $this->section_m->get_section($student->sectionID);
								if($section) {
									$this->data['students'][$key] = (object) array_merge( (array)$student, array('ssection' => $section->section));
								} else {
									$this->data['students'][$key] = (object) array_merge( (array)$student, array('ssection' => $student->section));
								}
							}
							$this->data['examID'] = $examID;
							$this->data['classesID'] = $classesID;
							$this->data['subjectID'] = $subjectID;
						}
					}

					$this->data["subview"] = "eattendance/index";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "eattendance/index";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function exam_list() {
		$examID = $this->input->post('id');
		if((int)$examID) {
			$string = base_url("eattendance/index/$examID");
			echo $string;
		} else {
			redirect(base_url("eattendance/index"));
		}
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
		$schoolyearID = $this->data['siteinfos']->school_year;
		$this->data['exams'] = $this->exam_m->get_exam();
		$this->data['classes'] = $this->classes_m->get_classes();
		

		$classesID = $this->input->post("classesID");
		if($classesID != 0) {
			$this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
			$this->data['subjects'] = $this->subject_m->get_order_by_subject(array("classesID" => $classesID));
		} else {
			$this->data['sections'] = 'empty';
			$this->data['subjects'] = "empty";
		}
		$this->data['sectionID'] = 0;
		$this->data['subjectID'] = 0;

		$this->data['students'] = array();
		$this->data['eattendanceinfo'] = array();
		
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) { 
				$this->data["subview"] = "eattendance/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$examID = $this->input->post("examID");
				$classesID = $this->input->post("classesID");
				$sectionID = $this->input->post('sectionID');
				$subjectID = $this->input->post("subjectID");
				$date = date("Y-m-d");
				$year = date("Y");

					$students = $this->student_m->get_order_by_student(array('schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID));
					if(count($students)) {
						foreach ($students as $key => $student) {
							$section = $this->section_m->get_section($student->sectionID);
							if($section) {
								$this->data['students'][$key] = (object) array_merge( (array)$student, array('ssection' => $section->section));
							} else {
								$this->data['students'][$key] = (object) array_merge( (array)$student, array('ssection' => $student->section));
							}

							$studentID = $student->studentID;
							$eattendance = $this->eattendance_m->get_order_by_eattendance(array("studentID" => $studentID, "examID" => $examID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "subjectID" => $subjectID));
							if(!count($eattendance)) {
								$array = array(
									"examID" => $examID,
									'schoolyearID' => $schoolyearID,
									"classesID" => $classesID,
									'sectionID' => $sectionID,
									"subjectID" => $subjectID,
									"studentID" => $studentID,
									"s_name" => $student->name,
									"date" => $date,
									"year" => $year
								);
								$this->eattendance_m->insert_eattendance($array);
							}
						}
						$this->data['eattendances'] = $this->eattendance_m->get_eattendance();
						$this->data['examID'] = $examID;
						$this->data['classesID'] = $classesID;
						$this->data['sectionID'] = $sectionID;
						$this->data['subjectID'] = $subjectID;
					}


					$this->data['eattendanceinfo']['exam'] = $this->exam_m->get_exam($examID)->exam;
					$this->data['eattendanceinfo']['class'] = $this->classes_m->get_classes($classesID)->classes;
					$this->data['eattendanceinfo']['section'] = $this->section_m->get_section($sectionID)->section;
					$this->data['eattendanceinfo']['subject'] = $this->subject_m->get_subject($subjectID)->subject;
					$this->data["subview"] = "eattendance/add";
					$this->load->view('_layout_main', $this->data);

			}
		} else {
			$this->data["subview"] = "eattendance/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function check_exam() {
		$examID = $this->input->post('examID');
		if($examID === '0') {
			$this->form_validation->set_message("check_exam", "%s es requerido.");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_classes() {
		$classesID = $this->input->post('classesID');
		if($classesID === '0') {
			$this->form_validation->set_message("check_classes", "%s es requerido.");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_subject() {
		$subjectID = $this->input->post('subjectID');
		if($subjectID === '0') {
			$this->form_validation->set_message("check_subject", "%s es requerido.");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_section() {
		$sectionID = $this->input->post('sectionID');
		if($sectionID === '0') {
			$this->form_validation->set_message("check_section", "%s es requerido.");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_student() {
		$studentID = $this->input->post('studentID');
		if($studentID === '0') {
			$this->form_validation->set_message("check_student", "%s es requerido.");
	     	return FALSE;
		}
		return TRUE;
	}

	function subjectcall() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$allclasses = $this->subject_m->get_order_by_subject(array("classesID" => $classID));
			echo "<option value='0'>", $this->lang->line("eattendance_select_subject"),"</option>";
			foreach ($allclasses as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}
		} 
	}

	function sectioncall() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$sections = $this->section_m->get_order_by_section(array("classesID" => $classID));
			echo "<option value='0'>", $this->lang->line("eattendance_select_section"),"</option>";
			foreach ($sections as $section) {
				echo "<option value=\"$section->sectionID\">",$section->section,"</option>";
			}
		} 
	}


	function single_add() {
		$examID = $this->input->post('examID');
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$subjectID = $this->input->post('subjectID');
		$studentID = $this->input->post('studentID');
		$status = 0;
		$status = $this->input->post('status');
		$year = date("Y");
		$schoolyearID = $this->data['siteinfos']->school_year;
		
		if($status == "checked") {
			$status = "Presente";
		} elseif($status == "unchecked") {
			$status = "Ausente";
		}
		if((int)$examID && (int)$classesID && (int)$subjectID) {
			$array = array("eattendance" => $status);
			$this->eattendance_m->update_eattendance_classes($array, array("examID" => $examID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "subjectID" => $subjectID, "year" => $year, "studentID" => $studentID));
			echo $this->lang->line('menu_success');
		}
	}

	function all_add() {
		$examID = $this->input->post('examID');
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$subjectID = $this->input->post('subjectID');
		$status = 0;
		$status = $this->input->post('status');
		$year = date("Y");
		$schoolyearID = $this->data['siteinfos']->school_year;

		if($status == "checked") {
			$status = "Presente";
		} elseif($status == "unchecked") {
			$status = "Ausente";
		}
		if((int)$examID && (int)$classesID && (int)$subjectID) {
			$array = array("eattendance" => $status);
			$this->eattendance_m->update_eattendance_classes($array, array("examID" => $examID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "subjectID" => $subjectID, "year" => $year));
			echo $this->lang->line('menu_success');
		}
	}
}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */