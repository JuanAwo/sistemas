<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lmember extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("lmember_m");
		$this->load->model("student_m");
		$this->load->model("issue_m");
		$this->load->model('section_m');
		$this->load->model('parents_m');
        $this->load->model('studentgroup_m');
        $this->load->model('subject_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('lmember', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'lID', 
				'label' => $this->lang->line("lmember_lID"),
				'rules' => 'trim|required|max_length[40]|callback_unique_lID|xss_clean'
			),
			array(
				'field' => 'lbalance', 
				'label' => $this->lang->line("lmember_lfee"), 
				'rules' => 'trim|required|max_length[20]|xss_clean|numeric|callback_valid_number'
			)
		);
		return $rules;
	}

	public function index() {
		$usertypeID = $this->session->userdata('usertypeID');
        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');

        $this->data['optionalSubjects'] = pluck($this->subject_m->get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');

		if($usertypeID == 3) {
			if(permissionChecker('lmember_add') || permissionChecker('lmember_edit') || permissionChecker('lmember_delete') || permissionChecker('lmember_view')) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$singleStudent = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($singleStudent) {
					$this->data['students'] = $this->student_m->get_order_by_student(array('classesID' => $singleStudent->classesID, 'schoolyearID' => $schoolyearID));
					$this->data["subview"] = "lmember/index_parents";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data['students'] = array();
					$this->data["subview"] = "lmember/index_parents";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$username = $this->session->userdata("username");
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$this->data['student'] = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					$this->data['set'] = $this->data["class"]->classesID;
					$this->data['lmember'] = $this->lmember_m->get_lmember_sID($this->data['student']->studentID);
					if($this->data['lmember']) {
						$lmember = $this->lmember_m->get_single_lmember(array("studentID" => $this->data['student']->studentID));
						$lID = $lmember->lID;
						$this->data['issues'] = $this->issue_m->get_issue_with_books(array("lID" => $lID));
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data["subview"] = "lmember/view";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data["subview"] = "lmember/message";
						$this->load->view('_layout_main', $this->data);
					}
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
				$this->data['students'] = $this->student_m->get_order_by_student(array('parentID' => $parents->parentsID, 'schoolyearID' => $schoolyearID));
				$this->data["subview"] = "lmember/index_parents";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
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
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['set'] = $id;
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data['students'] = $this->student_m->get_order_by_student(array('classesID' => $id, 'schoolyearID' => $schoolyearID));

				if($this->data['students']) {
					$sections = $this->section_m->get_order_by_section(array("classesID" => $id));
					$this->data['sections'] = $sections;
					foreach ($sections as $key => $section) {
						$this->data['allsection'][$section->sectionID] = $this->student_m->get_order_by_student(array('classesID' => $id, "sectionID" => $section->sectionID, 'schoolyearID' => $schoolyearID));
					}
				} else {
					$this->data['students'] = NULL;
				}

				$this->data["subview"] = "lmember/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = $id;
				$this->data['students'] = NULL;
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["subview"] = "lmember/index";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function add() {
		$lID = '';
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		$lmember = $this->lmember_m->get_lmember();
		$lastid = $this->lmember_m->get_lmember_lastID();
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		

		if((int)$id && (int)$url) {
			$student = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
			if($student) {
				if(count($lmember)) {
				$lID = $lastid->lID+1;
				} else {
					$data = date('Y');
					$lID = $data.'01';
				}

				$this->data['libraryID'] = $lID;
				$this->data['student'] = $student;
				$this->data['set'] = $url;
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data['form_validation'] = validation_errors(); 
						$this->data["subview"] = "lmember/add";
						$this->load->view('_layout_main', $this->data);			
					} else {

						$array = array(
							"lID" => $this->input->post("lID"),
							"studentID" => $student->studentID,
							"name" => $student->name,
							"email" => $student->email,
							"phone" => $student->phone,
							"lbalance" => $this->input->post("lbalance"),
							"ljoindate" => date("Y-m-d")
						);

						$this->lmember_m->insert_lmember($array);
						$this->student_m->update_student(array("library" => 1), $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("lmember/index/$url"));
					}
				} else {
					$this->data["subview"] = "lmember/add";
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

	public function edit() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$id && (int)$url) {
			$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
			if($this->data['student']) {
				$this->data['lmember'] = $this->lmember_m->get_single_lmember(array("studentID" => $id));
				if($this->data['lmember']) {
					$this->data['set'] = $url;
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) { 
							$this->data["subview"] = "lmember/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array(
								"lID" => $this->input->post("lID"),
								"lbalance" => $this->input->post("lbalance")
							);

							$this->lmember_m->update_lmember($array, $this->data['lmember']->lmemberID);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("lmember/index/$url"));	
						}
					} else {
						$this->data["subview"] = "lmember/edit";
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
	}

	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id && (int)$url) {
				$student = $this->student_m->get_order_by_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
				if($student) {
					$this->lmember_m->delete_lmember_sID($id);
					$this->student_m->update_student(array("library" => 0), $id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("lmember/index/$url"));
				} else {
					redirect(base_url("lmember/index"));
				}
			} else {
				redirect(base_url("lmember/index"));
			}
	}


	public function view() {
		$usertypeID = $this->session->userdata("usertypeID");
        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');

        $this->data['optionalSubjects'] = pluck($this->subject_m->get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');

		if($usertypeID == 3) {
			if(permissionChecker('lmember_view')) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				$url = htmlentities(escapeString($this->uri->segment(4)));
				$schoolyearID = $this->session->userdata('defaultschoolyearID');

				if ((int)$id && (int)$url) {
					$this->data['set'] = $url;
					$username = $this->session->userdata("username");
					$originalStudent = $this->student_m->get_single_student(array("username" => $username));
					if($originalStudent) {
						$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));	
						if($this->data['student']) {
							if($originalStudent->classesID == $this->data['student']->classesID) {
								$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
								$this->data['lmember'] = $this->lmember_m->get_lmember_sID($id);
								if($this->data['lmember']) {
									$this->data['issues'] = $this->issue_m->get_issue_with_books(array('lID' => $this->data['lmember']->lID));
									$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
									$this->data["subview"] = "lmember/view";
									$this->load->view('_layout_main', $this->data);
								} else {
									$this->data["subview"] = "lmember/message";
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
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$this->data['student'] = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					$this->data['set'] = $this->data['student']->classesID;
					$this->data['lmember'] = $this->lmember_m->get_lmember_sID($this->data['student']->studentID);
					if($this->data['lmember']) {
						$lmember = $this->lmember_m->get_single_lmember(array("studentID" => $this->data['student']->studentID));
						$lID = $lmember->lID;
						$this->data['issues'] = $this->issue_m->get_issue_with_books(array('lID' => $this->data['lmember']->lID));
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data["subview"] = "lmember/view";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data["subview"] = "lmember/message";
						$this->load->view('_layout_main', $this->data);
					}
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
							$this->data['lmember'] = $this->lmember_m->get_lmember_sID($id);
							if($this->data['lmember']) {
								$this->data['student'] = $checkstudent;
								$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
								$this->data['issues'] = $this->issue_m->get_issue_with_books(array('lID' => $this->data['lmember']->lID));
								$this->data["section"] = $this->section_m->get_section($checkstudent->sectionID);
								$this->data["subview"] = "lmember/view";
								$this->load->view('_layout_main', $this->data);
							} else {
								$this->data["subview"] = "lmember/message";
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
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if ((int)$id && (int)$url) {
				$this->data['set'] = $url;
				$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));	
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					$this->data['lmember'] = $this->lmember_m->get_lmember_sID($id);
					if($this->data['lmember']) {
						$this->data['issues'] = $this->issue_m->get_issue_with_books(array('lID' => $this->data['lmember']->lID));
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data["subview"] = "lmember/view";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data["subview"] = "lmember/message";
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
	}

	public function print_preview() {
		$usertypeID = $this->session->userdata("usertypeID");
        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');

        $this->data['optionalSubjects'] = pluck($this->subject_m->get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');

		if($usertypeID == 3) {
			if(permissionChecker('lmember_view')) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				$url = htmlentities(escapeString($this->uri->segment(4)));
				$schoolyearID = $this->session->userdata('defaultschoolyearID');

				if ((int)$id && (int)$url) {
					$this->data['set'] = $url;
					$username = $this->session->userdata("username");
					$originalStudent = $this->student_m->get_single_student(array("username" => $username));
					if($originalStudent) {
						$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));	
						if($this->data['student']) {
							if($originalStudent->classesID == $this->data['student']->classesID) {
								$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
								$this->data['lmember'] = $this->lmember_m->get_lmember_sID($id);
								if($this->data['lmember']) {
									$this->data['issues'] = $this->issue_m->get_issue_with_books(array('lID' => $this->data['lmember']->lID));
									$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
									$this->printview($this->data, 'lmember/print_preview');
								} else {
									$this->data["subview"] = "lmember/message";
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
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$this->data['student'] = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					$this->data['set'] = $this->data['student']->classesID;
					$this->data['lmember'] = $this->lmember_m->get_lmember_sID($this->data['student']->studentID);
					if($this->data['lmember']) {
						$lmember = $this->lmember_m->get_single_lmember(array("studentID" => $this->data['student']->studentID));
						$lID = $lmember->lID;
						$this->data['issues'] = $this->issue_m->get_issue_with_books(array('lID' => $this->data['lmember']->lID));
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->printview($this->data, 'lmember/print_preview');
					} else {
						$this->data["subview"] = "lmember/message";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($usertypeID == 4) {
			if(permissionChecker('lmember_view')) {
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
								$this->data['lmember'] = $this->lmember_m->get_lmember_sID($id);
								if($this->data['lmember']) {
									$this->data['student'] = $checkstudent;
									$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
									$this->data['issues'] = $this->issue_m->get_issue_with_books(array('lID' => $this->data['lmember']->lID));
									$this->data["section"] = $this->section_m->get_section($checkstudent->sectionID);
									$this->printview($this->data, 'lmember/print_preview');
								} else {
									$this->data["subview"] = "lmember/message";
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
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "errorpermission";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			if(permissionChecker('lmember_view')) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				$url = htmlentities(escapeString($this->uri->segment(4)));
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				if ((int)$id && (int)$url) {
					$this->data['set'] = $url;
					$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));	
					if($this->data['student']) {
						$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
						$this->data['lmember'] = $this->lmember_m->get_lmember_sID($id);
						if($this->data['lmember']) {
							$this->data['issues'] = $this->issue_m->get_issue_with_books(array('lID' => $this->data['lmember']->lID));
							$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
							$this->printview($this->data, 'lmember/print_preview');
						} else {
							$this->data["subview"] = "lmember/message";
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
		}
	}

	public function send_mail() {
        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
        $this->data['optionalSubjects'] = pluck($this->subject_m->get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');

		$id = $this->input->post('id');
		$url = $this->input->post('set');
		if ((int)$id && (int)$url) {
			$this->data["student"] = $this->student_m->get_student($id);
			
			if($this->data["student"]) {
				$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
				$this->data['lmember'] = $this->lmember_m->get_lmember_sID($id);
				$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);

				$this->data['issues'] = $this->issue_m->get_order_by_issue(array('lID' => $this->data['lmember']->lID));

				$email = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');
				
				$this->viewsendtomail($this->data, 'lmember/print_preview', $email, $subject, $message);
				
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("lmember/index/$classID");
			echo $string;
		} else {
			redirect(base_url("lmember/index"));
		}
	}

	public function unique_lID() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$method = $this->uri->segment(2);
		if($method == "edit") {
			$library = $this->lmember_m->get_single_lmember(array("studentID" => $id));
			$lmember = $this->lmember_m->get_order_by_lmember(array("lID" => $this->input->post("lID"), "lmemberID !=" => $library->lmemberID));
			if(count($lmember)) {
				$this->form_validation->set_message("unique_lID", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		} else {
			$lmember = $this->lmember_m->get_order_by_lmember(array("lID" => $this->input->post("lID")));
			if(count($lmember)) {
				$this->form_validation->set_message("unique_lID", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		}
	}

	function valid_number() {
		if($this->input->post('lbalance') && $this->input->post('lbalance') < 0) {
			$this->form_validation->set_message("valid_number", "%s es un número invalido.");
			return FALSE;
		}
		return TRUE;
	}
}

/* End of file lmember.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/lmember.php */