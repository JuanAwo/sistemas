<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tmember extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("tmember_m");
		$this->load->model("transport_m");
		$this->load->model("student_m");
		$this->load->model("section_m");
		$this->load->model('parents_m');
        $this->load->model('studentgroup_m');
        $this->load->model('subject_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('tmember', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'transportID', 
				'label' => $this->lang->line("tmember_route_name"), 
				'rules' => 'trim|required|max_length[11]|xss_clean|callback_alltransport'
			),
			array(
				'field' => 'tbalance', 
				'label' => $this->lang->line("tmember_tfee"), 
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
			if(permissionChecker('tmember_add') || permissionChecker('tmember_edit') || permissionChecker('tmember_delete') || permissionChecker('tmember_view')) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$singleStudent = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($singleStudent) {
					$this->data['students'] = $this->student_m->get_order_by_student(array('classesID' => $singleStudent->classesID, 'schoolyearID' => $schoolyearID));
					$this->data['set'] = $singleStudent->classesID;
					$this->data["subview"] = "tmember/index_parents";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data['students'] = array();
					$this->data["subview"] = "tmember/index_parents";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$this->data['student'] = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					$this->data['set'] = $this->data["class"]->classesID;
					$this->data['tmember'] = $this->tmember_m->get_tmember_sID($this->data['student']->studentID);
					if($this->data['tmember']) {
						$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data["subview"] = "tmember/view";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data["subview"] = "tmember/message";
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
				$this->data["subview"] = "tmember/index_parents";
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

				$this->data["subview"] = "tmember/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = $id;
				$this->data['students'] = NULL;
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["subview"] = "tmember/index";
				$this->load->view('_layout_main', $this->data);
			}
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

		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		$this->data['transports'] = $this->transport_m->get_transport();
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		
		if((int)$id && (int)$url) {
			$student = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
			if($student){
				$this->data['set'] = $url;
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "tmember/add";
						$this->load->view('_layout_main', $this->data);			
					} else {

						$array = array(
							"studentID" => $student->studentID,
							"transportID" => $this->input->post("transportID"),
							"name" => $student->name,
							"email" => $student->email,
							"phone" => $student->phone,
							"tbalance" => $this->input->post("tbalance"),
							"tjoindate" => date("Y-m-d")
						);

						$this->tmember_m->insert_tmember($array);
						$this->student_m->update_student(array("transport" => 1), $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("tmember/index/$url"));
					}
				} else {
					$this->data["subview"] = "tmember/add";
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
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		if((int)$id && (int)$url) {
			$student = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
			if($student) {
				$this->data['tmember'] = $this->tmember_m->get_single_tmember(array("studentID" =>$id));
				if($this->data['tmember']) {
					$this->data['transports'] = $this->transport_m->get_transport();
					$this->data['set'] = $url;
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) { 
							$this->data["subview"] = "tmember/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array(
								"transportID" => $this->input->post("transportID"),
								"tbalance" => $this->input->post("tbalance")
							);
							$this->tmember_m->update_tmember($array, $this->data['tmember']->tmemberID);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("tmember/index/$url"));	
						}
					} else {
						$this->data["subview"] = "tmember/edit";
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
			$student = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
			if($student) {
				$this->tmember_m->delete_tmember_sID($id);
				$this->student_m->update_student(array("transport" => 0), $id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("tmember/index/$url"));
			} else {
				redirect(base_url("tmember/index"));
			}
		} else {
			redirect(base_url("tmember/index"));
		}
	}

	public function view() {
		$usertypeID = $this->session->userdata("usertypeID");
        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
        $this->data['optionalSubjects'] = pluck($this->subject_m->get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');

		if($usertypeID == 3) {
			if(permissionChecker('tmember_view')) {
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
								$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
								$this->data['tmember'] = $this->tmember_m->get_tmember_sID($id);
								if($this->data['tmember']) {
									$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
									$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
									$this->data["subview"] = "tmember/view";
									$this->load->view('_layout_main', $this->data);
								} else {
									$this->data["subview"] = "tmember/message";
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
					$this->data['tmember'] = $this->tmember_m->get_tmember_sID($this->data['student']->studentID);
					if($this->data['tmember']) {
						$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data["subview"] = "tmember/view";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data["subview"] = "tmember/message";
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
							$this->data['tmember'] = $this->tmember_m->get_tmember_sID($id);
							if($this->data['tmember']) {
								$this->data['set'] = $checkstudent->classesID;	
								$this->data['student'] = $checkstudent;
								$this->data["class"] = $this->student_m->get_class($checkstudent->classesID);
								$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
								$this->data["section"] = $this->section_m->get_section($checkstudent->sectionID);
								$this->data["subview"] = "tmember/view";
								$this->load->view('_layout_main', $this->data);
							} else {
								$this->data["subview"] = "tmember/message";
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
			if((int)$id && (int)$url) {
				$this->data['set'] = $url;
				$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					$this->data['tmember'] = $this->tmember_m->get_tmember_sID($id);
					if($this->data['tmember']) {
						$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data["subview"] = "tmember/view";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data["subview"] = "tmember/message";
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

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("tmember/index/$classID");
			echo $string;
		} else {
			redirect(base_url("tmember/index"));
		}
	}

	public function transport_fare() {
		$transportID = $this->input->post('id');
		if((int)$transportID) {
			$string = $this->transport_m->get_transport($transportID);
			echo $string->fare;
		} else {
			redirect(base_url("tmember/index"));
		}
	}

	public function print_preview() {
		$usertypeID = $this->session->userdata("usertypeID");
        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
        $this->data['optionalSubjects'] = pluck($this->subject_m->get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');

        if($usertypeID == 3) {
			if(permissionChecker('tmember_view')) {
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
								$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
								$this->data['tmember'] = $this->tmember_m->get_tmember_sID($id);
								if($this->data['tmember']) {
									$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
									$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
									$this->printview($this->data, 'tmember/print_preview');
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
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");

				$this->data['student'] = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					$this->data['tmember'] = $this->tmember_m->get_tmember_sID($this->data['student']->studentID);
					if($this->data['tmember']) {
						$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->printview($this->data, 'tmember/print_preview');
					} else {
						$this->data["subview"] = "tmember/view";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($usertypeID == 4) {
			if(permissionChecker('tmember_view')) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$parents = $this->parents_m->get_single_parents(array('username' => $username));
				if(count($parents)) {
					$id = htmlentities(escapeString($this->uri->segment(3)));
					if((int)$id) {
						$checkstudent = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
						if(count($checkstudent)) {
							if($checkstudent->parentID == $parents->parentsID) {
								$this->data['tmember'] = $this->tmember_m->get_tmember_sID($id);
								if($this->data['tmember']) {
									$this->data['set'] = $checkstudent->classesID;	
									$this->data['student'] = $checkstudent;
									$this->data["class"] = $this->student_m->get_class($checkstudent->classesID);
									$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
									$this->data["section"] = $this->section_m->get_section($checkstudent->sectionID);
									$this->printview($this->data, 'tmember/print_preview');
								} else {
									$this->data["subview"] = "tmember/message";
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
			if(permissionChecker('tmember_view')) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				$url = htmlentities(escapeString($this->uri->segment(4)));
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				if((int)$id && (int)$url) {
					$this->data['set'] = $url;
					$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
					if($this->data['student']) {
						$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
						$this->data['tmember'] = $this->tmember_m->get_tmember_sID($id);
						if($this->data['tmember']) {
							$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
							$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
							$this->printview($this->data, 'tmember/print_preview');
						} else {
							$this->data["subview"] = "tmember/message";
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
				$this->data['tmember'] = $this->tmember_m->get_tmember_sID($id);
				if($this->data['tmember']) {
					$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
					$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);

					$email = $this->input->post('to');
					$subject = $this->input->post('subject');
					$message = $this->input->post('message');
				
					$this->viewsendtomail($this->data, 'tmember/print_preview', $email, $subject, $message);
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

	function alltransport() {
		if($this->input->post('transportID') == 0) {
			$this->form_validation->set_message("alltransport", "%s es requerido");
	     	return FALSE;
		}
		return TRUE;
	}

	function valid_number() {
		if($this->input->post('tbalance') && $this->input->post('tbalance') < 0) {
			$this->form_validation->set_message("valid_number", "%s es un número invalido.");
			return FALSE;
		}
		return TRUE;
	}

}

/* End of file tmember.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/tmember.php */