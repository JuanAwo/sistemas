<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//Sattendance.php (controllers)
//asistencia estudiante (controllers)
//edit sattendance 
//controllers
//model
//views
class Sattendance extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("sattendance_m");
		$this->load->model("teacher_m");
		$this->load->model("classes_m");
		$this->load->model("user_m");
		$this->load->model("section_m");
		$this->load->model("setting_m");
		$this->data['setting'] = $this->setting_m->get_setting(1);
		if($this->data['setting']->attendance == "subject") {
			$this->load->model("subject_m");
			$this->load->model("subjectattendance_m");
		}
		$language = $this->session->userdata('lang');
		$this->lang->load('sattendance', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("attendance_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_classes'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("attendance_section"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_section'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("attendance_date"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid|callback_valid_future_date'
			)
		);
		return $rules;
	}

	protected function subject_rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("attendance_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_classes'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("attendance_section"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_section'
			),
			array(
				'field' => 'subjectID',
				'label' => $this->lang->line("attendance_subject"),
				'rules' => 'trim|required|xss_clean|callback_check_subject'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("attendance_date"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid|callback_valid_future_date'
			)
		);
		return $rules;
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

		if($this->session->userdata('usertypeID') == 3) {
			if(permissionChecker('sattendance_view')) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$singleStudent = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($singleStudent) {
					$this->data['students'] = $this->student_m->get_order_by_student(array('classesID' => $singleStudent->classesID, 'schoolyearID' => $schoolyearID));
					$this->data['set'] = $singleStudent->classesID;
					$this->data["subview"] = "sattendance/index_parents";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data['students'] = array();
					$this->data["subview"] = "sattendance/index_parents";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$username = $this->session->userdata("username");
				$student = $this->student_m->get_single_student(array("username" => $username));
				if($student) {
					$this->data["student"] = $student;
					$this->data['classes'] = $this->classes_m->get_classes($student->classesID);
					$this->data['set'] = $student->classesID;

					if($this->data['setting']->attendance == "subject") {
						$this->data["subjects"] = $this->subject_m->get_order_by_subject(array("classesID" => $student->classesID));
						$this->data['attendances'] = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $student->studentID, "classesID" => $student->classesID));
					} else {
						$this->data['attendances'] = $this->sattendance_m->get_order_by_attendance(array("studentID" => $student->studentID, "classesID" => $student->classesID));
					}

					$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
					$this->data["subview"] = "sattendance/view";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($this->session->userdata('usertypeID') == 4) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$username = $this->session->userdata("username");
			$parents = $this->parents_m->get_single_parents(array('username' => $username));
			if(count($parents)) {
				$this->data['students'] = $this->student_m->get_order_by_student(array('parentID' => $parents->parentsID, 'schoolyearID' => $schoolyearID));
				$this->data["subview"] = "sattendance/index_parents";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['set'] = $id;
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data['students'] = $this->student_m->get_order_by_student(array('classesID' => $id, 'schoolyearID' => $schoolyearID));

				if($this->data['students']) {
					$sections = $this->section_m->get_order_by_section(array("classesID" => $id));
					$this->data['sections'] = $sections;
					foreach ($sections as $key => $section) {
						$this->data['allsection'][$section->section] = $this->student_m->get_order_by_student(array('classesID' => $id, "sectionID" => $section->sectionID, 'schoolyearID' => $schoolyearID));
					}
				} else {
					$this->data['students'] = NULL;
				}
				$this->data["subview"] = "sattendance/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['students'] = NULL;
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["subview"] = "sattendance/search";
				$this->load->view('_layout_main', $this->data);
			}
		}

	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/datepicker/datepicker.js'
			)
		);

		$this->data['sattendanceinfo'] = array();
		$this->data['set'] = 0;
		$this->data['date'] = date("d-m-Y");
		$this->data['day'] = 0;
		$this->data['monthyear'] = 0;
		$username = $this->session->userdata("username");
		$this->data['classes'] = $this->classes_m->get_classes();
		$this->data['students'] = array();
		$classesID = $this->input->post("classesID");

		if($classesID != 0 && $this->data['setting']->attendance == "subject") {
			$this->data['subjects'] = $this->subject_m->get_order_by_subject(array("classesID" => $classesID));
		} else {
			$this->data['subjects'] = "empty";
		}


		if($classesID != 0) {
			$this->data['sections'] = $this->section_m->get_order_by_section(array("classesID" => $classesID));
		} else {
			$this->data['sections'] = "empty";
		}

		$this->data['subjectID'] = 0;
		$this->data['sectionID'] = 0;

		if($_POST) {

			if($this->data['setting']->attendance == "subject") {
				$rules = $this->subject_rules();
			} else {
				$rules = $this->rules();
			}

			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "sattendance/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$classesID = $this->input->post("classesID");
				$sectionID = $this->input->post("sectionID");
				$schoolyearID = $this->data['siteinfos']->school_year;
				$usertype = $this->session->userdata('usertype');

				if($this->data['setting']->attendance == "subject") {
					$subjectID = $this->input->post("subjectID");
					$this->data['subjectID'] = $subjectID;
					$this->data['sattendanceinfo']['subject'] = $this->subject_m->get_subject($subjectID)->subject;
				}

				if($sectionID !=0) {
					$this->data['sectionID'] = $sectionID;
				}


				$date = $this->input->post("date");
				$this->data['set'] = $classesID;
				$this->data['date'] = $date;
				$explode_date = explode("-", $date);
				$monthyear = $explode_date[1]."-".$explode_date[2];
				$userID = $this->session->userdata('loginuserID');

				$last_day = cal_days_in_month(CAL_GREGORIAN, $explode_date[1], $explode_date[2]);

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


						if($this->data['setting']->attendance == "subject") {
							$attendance_monthyear = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $studentID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "subjectID" => $subjectID, "monthyear" => $monthyear));
						} else {
							$attendance_monthyear = $this->sattendance_m->get_order_by_attendance(array("studentID" => $studentID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "monthyear" => $monthyear));
						}


						if(!count($attendance_monthyear)) {
							if($this->data['setting']->attendance == "subject") {
								$array = array(
									"studentID" => $studentID,
									'schoolyearID' => $schoolyearID,
									"classesID" => $classesID,
									'sectionID' => $sectionID,
									"subjectID" => $subjectID,
									"userID" => $userID,
									"usertype" => $usertype,
									"monthyear" => $monthyear
								);
								$this->subjectattendance_m->insert_sub_attendance($array);
							} else {
								$array = array(
									"studentID" => $studentID,
									'schoolyearID' => $schoolyearID,
									"classesID" => $classesID,
									'sectionID' => $sectionID,
									"userID" => $userID,
									"usertype" => $usertype,
									"monthyear" => $monthyear
								);
								$this->sattendance_m->insert_attendance($array);
							}
						}
					}


					if($this->data['setting']->attendance == "subject") {
						$this->data['attendances'] = $this->subjectattendance_m->get_sub_attendance();
					} else {
						$this->data['attendances'] = $this->sattendance_m->get_attendance();
					}
					$this->data['monthyear'] = $monthyear;
					$this->data['day'] = $explode_date[0];
				}

				$this->data['sattendanceinfo']['class'] = $this->classes_m->get_classes($classesID)->classes;
				$this->data['sattendanceinfo']['section'] = $this->section_m->get_section($sectionID)->section;
				$this->data['sattendanceinfo']['day'] = date('l', strtotime($date));
				$this->data['sattendanceinfo']['date'] = date('jS F Y', strtotime($date));

				$this->data["subview"] = "sattendance/add";
				$this->load->view('_layout_main', $this->data);

			}
		} else {
			$this->data["subview"] = "sattendance/add";
			$this->load->view('_layout_main', $this->data);
		}

	}

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("sattendance/index/$classID");
			echo $string;
		} else {
			redirect(base_url("sattendance/index"));
		}
	}

	function singl_add() {
		$id = $this->input->post('id');
		$day = $this->input->post('day');
		if((int)$id && (int)$day) {
			$aday = "a".abs($day);

			if($this->data['setting']->attendance == "subject") {
				$attendance_row = $this->subjectattendance_m->get_sub_attendance($id);
				if($attendance_row) {
					if($attendance_row->$aday == "") {
						$this->subjectattendance_m->update_sub_attendance(array($aday => "P"), $id);
						echo $this->lang->line('menu_success');
					} elseif($attendance_row->$aday == "P") {
						$this->subjectattendance_m->update_sub_attendance(array($aday => "A"), $id);
						echo $this->lang->line('menu_success');
					} elseif($attendance_row->$aday == "A") {
						$this->subjectattendance_m->update_sub_attendance(array($aday => "P"), $id);
						echo $this->lang->line('menu_success');
					}
				}
			} else {
				$attendance_row = $this->sattendance_m->get_attendance($id);
				if($attendance_row) {
					if($attendance_row->$aday == "") {
						$this->sattendance_m->update_attendance(array($aday => "P"), $id);
						echo $this->lang->line('menu_success');
					} elseif($attendance_row->$aday == "P") {
						$this->sattendance_m->update_attendance(array($aday => "A"), $id);
						echo $this->lang->line('menu_success');
					} elseif($attendance_row->$aday == "A") {
						$this->sattendance_m->update_attendance(array($aday => "P"), $id);
						echo $this->lang->line('menu_success');
					}
				}
			}

		}
	}

	function all_add() {
		$classes = $this->input->post('classes');
		$day = $this->input->post('day');
		$monthyear = $this->input->post('monthyear');
		$status = $this->input->post('status');
		$schoolyearID = $this->data['siteinfos']->school_year;
		$sectionID = $this->input->post('section');

		if($status == "checked") {
			$status = "P";
		} elseif($status == "unchecked") {
			$status = "A";
		}
		if((int)$classes) {

			if($this->data['setting']->attendance == "subject") {
				$subjectID = $this->input->post('subject');
				$this->subjectattendance_m->update_sub_attendance_classes_new("a".abs($day), $status, $schoolyearID, $classes, $monthyear, $sectionID, $subjectID);
			} else {
				$this->sattendance_m->update_sub_attendance_classes_new("a".abs($day), $status, $schoolyearID, $classes, $monthyear, $sectionID);
			}
			echo $this->lang->line('menu_success');
		}
	}

	public function view() {
		$usertypeID = $this->session->userdata("usertypeID");
		$url = htmlentities(escapeString($this->uri->segment(4)));

		if($this->data['setting']->attendance == "subject") {
			$this->data["subjects"] = $this->subject_m->get_order_by_subject(array("classesID" => $url));
		}

		if($usertypeID == 3) {
			if(permissionChecker('sattendance_view')) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				$url = htmlentities(escapeString($this->uri->segment(4)));
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				if((int)$id && (int)$url) {
					$this->data['set'] = $url;
					$username = $this->session->userdata("username");
					$originalStudent = $this->student_m->get_single_student(array("username" => $username));
					if($originalStudent) {
						$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
						if($this->data['student']) {
							if($originalStudent->classesID == $this->data['student']->classesID) {

								$this->data['classes'] = $this->classes_m->get_classes($this->data['student']->classesID);

								if($this->data['setting']->attendance == "subject") {
									$this->data['attendances'] = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $id, "classesID" => $url));
								} else {
									$this->data['attendances'] = $this->sattendance_m->get_order_by_attendance(array("studentID" => $id, "classesID" => $url));
								}

								$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
								$this->data["subview"] = "sattendance/view";
								$this->load->view('_layout_main', $this->data);
							} else {
								$this->data["subview"] = "error";
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
				} else {
					$this->data["subview"] = "errorpermission";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$username = $this->session->userdata("username");
				$student = $this->student_m->get_single_student(array("username" => $username));
				if($student) {
					$this->data["student"] = $student;
					$this->data['classes'] = $this->classes_m->get_classes($student->classesID);

					if($this->data['setting']->attendance == "subject") {
						$this->data['attendances'] = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $student->studentID, "classesID" => $student->classesID));
					} else {
						$this->data['attendances'] = $this->sattendance_m->get_order_by_attendance(array("studentID" => $student->studentID, "classesID" => $student->classesID));
					}

					$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
					$this->data["subview"] = "sattendance/view";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($usertypeID == 4) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$username = $this->session->userdata("username");
			$parents = $this->parents_m->get_single_parents(array('username' => $username));
			if(count($parents)) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				if((int)$id) {
					$checkstudent = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
					if(count($checkstudent)) {
						if($checkstudent->parentID == $parents->parentsID) {
							$this->data['set'] = $checkstudent->classesID;
							$this->data["student"] = $checkstudent;
							$this->data['classes'] = $this->classes_m->get_classes($checkstudent->classesID);
							if($this->data['setting']->attendance == "subject") {
		                        $this->data["subjects"] = $this->subject_m->get_order_by_subject(array("classesID" => $checkstudent->classesID));

		                        $this->data['attendances'] = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $id, "classesID" => $checkstudent->classesID));
		                    } else {
		                        $this->data['attendances'] = $this->sattendance_m->get_order_by_attendance(array("studentID" => $id, "classesID" => $checkstudent->classesID));
		                    }

		                    $this->data["section"] = $this->section_m->get_section($checkstudent->sectionID);

		                    $this->data["subview"] = "sattendance/view";
		                    $this->load->view('_layout_main', $this->data);

						} else {
							$this->data["subview"] = "error";
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
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id && (int)$url) {
				$this->data["student"] = $this->student_m->get_student($id);
				$this->data["classes"] = $this->student_m->get_class($url);
				if($this->data["student"] && $this->data["classes"]) {
					$this->data['set'] = $url;

					if($this->data['setting']->attendance == "subject") {
						$this->data['attendances'] = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $id, "classesID" => $url));
					} else {
						$this->data['attendances'] = $this->sattendance_m->get_order_by_attendance(array("studentID" => $id, "classesID" => $url));
					}

					$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
					$this->data["subview"] = "sattendance/view";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function pstudent_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("sattendance/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("sattendance/index"));
		}
	}

	function check_classes() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("check_classes", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_section() {
		if($this->input->post('sectionID') == 0) {
			$this->form_validation->set_message("check_section", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_subject() {
		if($this->input->post('subjectID') == 0) {
			$this->form_validation->set_message("check_subject", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function date_valid($date) {
   		if(strlen($date) <10) {
			$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     	return FALSE;
		} else {
	   		$arr = explode("-", $date);
	        $dd = $arr[0];
	        $mm = $arr[1];
	        $yyyy = $arr[2];
	      	if(checkdate($mm, $dd, $yyyy)) {
	      		return TRUE;
	      	} else {
	      		$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     		return FALSE;
	      	}
	    }
	}

	public function print_preview() {
		$usertypeID = $this->session->userdata("usertypeID");
		$url = htmlentities(escapeString($this->uri->segment(4)));

		if($this->data['setting']->attendance == "subject") {
			$this->data["subjects"] = $this->subject_m->get_order_by_subject(array("classesID" => $url));
		}	

		if($usertypeID == 3) {

			if(permissionChecker('sattendance_view')) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				$url = htmlentities(escapeString($this->uri->segment(4)));
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				if((int)$id && (int)$url) {
					$this->data['set'] = $url;
					$username = $this->session->userdata("username");
					$originalStudent = $this->student_m->get_single_student(array("username" => $username));
					if($originalStudent) {
						$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
						if($this->data['student']) {
							if($originalStudent->classesID == $this->data['student']->classesID) {

								$this->data['classes'] = $this->classes_m->get_classes($this->data['student']->classesID);

								if($this->data['setting']->attendance == "subject") {
									$this->data['attendances'] = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $id, "classesID" => $url));
								} else {
									$this->data['attendances'] = $this->sattendance_m->get_order_by_attendance(array("studentID" => $id, "classesID" => $url));
								}

								$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
								$this->printview($this->data, 'sattendance/print_preview');
							} else {
								$this->data["subview"] = "error";
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
				} else {
					$this->data["subview"] = "errorpermission";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$username = $this->session->userdata("username");
				$student = $this->student_m->get_single_student(array("username" => $username));
				if($student) {
					$this->data["student"] = $student;
					$this->data['classes'] = $this->classes_m->get_classes($student->classesID);

					if($this->data['setting']->attendance == "subject") {
						$this->data['attendances'] = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $student->studentID, "classesID" => $student->classesID));
					} else {
						$this->data['attendances'] = $this->sattendance_m->get_order_by_attendance(array("studentID" => $student->studentID, "classesID" => $student->classesID));
					}

					$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
					$this->printview($this->data, 'sattendance/print_preview');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($usertypeID == 4) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$username = $this->session->userdata("username");
			$parents = $this->parents_m->get_single_parents(array('username' => $username));
			if(count($parents)) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				if((int)$id) {
					$checkstudent = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
					if(count($checkstudent)) {
						if($checkstudent->parentID == $parents->parentsID) {
							$this->data['set'] = $checkstudent->classesID;
							$this->data["student"] = $checkstudent;
							$this->data['classes'] = $this->classes_m->get_classes($checkstudent->classesID);
							if($this->data['setting']->attendance == "subject") {
		                        $this->data["subjects"] = $this->subject_m->get_order_by_subject(array("classesID" => $checkstudent->classesID));

		                        $this->data['attendances'] = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $id, "classesID" => $checkstudent->classesID));
		                    } else {
		                        $this->data['attendances'] = $this->sattendance_m->get_order_by_attendance(array("studentID" => $id, "classesID" => $checkstudent->classesID));
		                    }

		                    $this->data["section"] = $this->section_m->get_section($checkstudent->sectionID);

		                    $this->printview($this->data, 'sattendance/print_preview');

						} else {
							$this->data["subview"] = "error";
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
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id && (int)$url) {
				$this->data["student"] = $this->student_m->get_student($id);
				$this->data["classes"] = $this->student_m->get_class($url);
				if($this->data["student"] && $this->data["classes"]) {
					$this->data['set'] = $url;

					if($this->data['setting']->attendance == "subject") {
						$this->data['attendances'] = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $id, "classesID" => $url));
					} else {
						$this->data['attendances'] = $this->sattendance_m->get_order_by_attendance(array("studentID" => $id, "classesID" => $url));
					}

					$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);

					$this->printview($this->data, 'sattendance/print_preview');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function send_mail() {
		$usertype = $this->session->userdata("usertype");
		$id = $this->input->post('id');
		$url = $this->input->post('set');
		if ((int)$id && (int)$url) {
			$this->data["student"] = $this->student_m->get_student($id);
			$this->data["classes"] = $this->student_m->get_class($url);
			if($this->data["student"] && $this->data["classes"]) {

				if($this->data['setting']->attendance == "subject") {
					$this->data["subjects"] = $this->subject_m->get_order_by_subject(array("classesID" => $url));

					$this->data['attendances'] = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $id, "classesID" => $url));
				} else {
					$this->data['attendances'] = $this->sattendance_m->get_order_by_attendance(array("studentID" => $id, "classesID" => $url));
				}

				$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);

				$email = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');

				$this->viewsendtomail($this->data, 'sattendance/print_preview', $email, $subject, $message);

			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}

	}

	function valid_future_date($date) {
		$presentdate = date('Y-m-d');
		$date = date("Y-m-d", strtotime($date));
		if($date > $presentdate) {
			return FALSE;
		}
		return TRUE;
	}

	public function subjectall() {
		$id = $this->input->post('id');
		if((int)$id) {
			$allsubject = $this->subject_m->get_order_by_subject(array("classesID" => $id));
			echo "<option value='0'>", $this->lang->line("attendance_select_subject"),"</option>";
			foreach ($allsubject as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}
		}
	}

	function sectionall() {
		$classesID = $this->input->post('id');
		if((int)$classesID) {
			$sections = $this->section_m->get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("attendance_select_section"),"</option>";
			if(count($sections)) {
				foreach ($sections as $key => $section) {
					echo "<option value=\"$section->sectionID\">",$section->section,"</option>";
				}
			}
		}
	}
}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/sattendance.php */
