<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hmember extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("hmember_m");
		$this->load->model("category_m");
		$this->load->model("hostel_m");
		$this->load->model("student_m");
		$this->load->model("section_m");
		$this->load->model('parents_m');
        $this->load->model('studentgroup_m');
        $this->load->model('subject_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('hmember', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'hostelID', 
				'label' => $this->lang->line("hmember_hname"), 
				'rules' => 'trim|max_length[11]|required|xss_clean|numeric|callback_unique_gender'
			),
			array(
				'field' => 'categoryID', 
				'label' => $this->lang->line("hmember_class_type"), 
				'rules' => 'trim|max_length[11]|required|xss_clean|numeric|callback_unique_select|callback_unique_category'
			)
		);
		return $rules;
	}

	public function index() {
		$usertypeID = $this->session->userdata("usertypeID");
        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
        $this->data['optionalSubjects'] = pluck($this->subject_m->get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');

        if($usertypeID == 3) {
			if(permissionChecker('hmember_add') || permissionChecker('hmember_edit') || permissionChecker('hmember_delete') || permissionChecker('hmember_view')) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$singleStudent = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($singleStudent) {
					$this->data['students'] = $this->student_m->get_order_by_student(array('classesID' => $singleStudent->classesID, 'schoolyearID' => $schoolyearID));
					$this->data["subview"] = "hmember/index_parents";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data['students'] = array();
					$this->data["subview"] = "hmember/index_parents";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$this->data['student'] = $this->student_m->get_single_student(array('username' => $username, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data['set'] = $this->data['student']->classesID;
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $this->data['student']->studentID));
					if($this->data['hmember']) {
						$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
						$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data["subview"] = "hmember/view";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data["subview"] = "hmember/message";
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
				$this->data["subview"] = "hmember/index_parents";
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

				$this->data["subview"] = "hmember/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = $id;
				$this->data['students'] = NULL;
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["subview"] = "hmember/index";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
			),
			'js' => array(
				'assets/select2/select2.js',
			)
		);

		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		$this->data["hostels"] = $this->hostel_m->get_hostel();
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		$hostelID = $this->input->post("hostelID");
		if($hostelID != 0) {
			$this->data['categorys'] = $this->category_m->get_order_by_category(array("hostelID" => $hostelID));
		} else {
			$this->data['categorys'] = "empty";
		}
		$this->data['categoryID'] = $this->input->post("categoryID");

		if((int)$id && (int)$url) {
			$student = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
			if($student) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data['form_validation'] = validation_errors(); 
						$this->data["subview"] = "hmember/add";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$hostel_main_id = $this->hostel_m->get_hostel($this->input->post("hostelID"));
						$category_main_id = $this->category_m->get_single_category(array("hostelID" => $hostel_main_id->hostelID, "categoryID" =>  $this->input->post("categoryID")));
						if($hostel_main_id) {
							if($category_main_id) {
								$array = array(
									"hostelID" => $this->input->post("hostelID"),
									"categoryID" => $this->input->post("categoryID"),
									"studentID" => $id,
									"hbalance" => $category_main_id->hbalance,
									"hjoindate" => date("Y-m-d")
								);
								$this->hmember_m->insert_hmember($array);
								$this->student_m->update_student(array("hostel" => 1), $id);
								$this->session->set_flashdata('success', $this->lang->line('menu_success'));
								redirect(base_url("hmember/index/$url"));
							} else {
								$this->data["subview"] = "error";
								$this->load->view('_layout_main', $this->data);
							}
						} else {
							$this->data["subview"] = "error";
							$this->load->view('_layout_main', $this->data);
						}
					}
				} else {
					$this->data["subview"] = "hmember/add";
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
				'assets/select2/css/select2-bootstrap.css',
			),
			'js' => array(
				'assets/select2/select2.js',
			)
		);

		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		if((int)$id && (int)$url) {
			$student = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
			if($student) {
				$this->data["hmember"] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
				if($this->data["hmember"]) {
					$this->data["categorys"] = $this->category_m->get_order_by_category(array("hostelID" => $this->data["hmember"]->hostelID));
					if($this->data["categorys"]) {

						$this->data["hostels"] = $this->hostel_m->get_hostel();
						$this->data['set'] = $url;
						$hostelID = $this->input->post("hostelID");
						if($hostelID != 0) {
							$this->data['categorys'] = $this->category_m->get_order_by_category(array("hostelID" => $hostelID));
						} else {
							$this->data["categorys"] = $this->category_m->get_order_by_category(array("hostelID" => $this->data["hmember"]->hostelID));
						}
						$this->data['categoryID'] = $this->input->post("categoryID");

						if($_POST) {
							$rules = $this->rules();
							$this->form_validation->set_rules($rules);
							if ($this->form_validation->run() == FALSE) { 
								$this->data["subview"] = "hmember/edit";
								$this->load->view('_layout_main', $this->data);
							} else {
								$hostel_main_id = $this->hostel_m->get_hostel($this->input->post("hostelID"));
								$category_main_id = $this->category_m->get_single_category(array("hostelID" => $hostel_main_id->hostelID, "categoryID" =>  $this->input->post("categoryID")));

								if($hostel_main_id) {
									if($category_main_id) {
										$array = array(
											"hostelID" => $this->input->post("hostelID"),
											"categoryID" => $this->input->post("categoryID"),
											"studentID" => $id,
											"hbalance" => $category_main_id->hbalance
										);

										$this->hmember_m->update_hmember($array, $this->data['hmember']->hmemberID);
										$this->session->set_flashdata('success', $this->lang->line('menu_success'));
										redirect(base_url("hmember/index/$url"));
									} else {
										$this->data["subview"] = "error";
										$this->load->view('_layout_main', $this->data);
									}
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}				
							}
						} else {
							$this->data["subview"] = "hmember/edit";
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
	}

	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$id && (int)$url) {
			$student = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
			if($student) {
				$this->data["hmember"] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
				if($this->data["hmember"]) {
					$this->hmember_m->delete_hmember($this->data['hmember']->hmemberID);
					$this->student_m->update_student(array("hostel" => 0), $id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("hmember/index/$url"));
				} else {
					redirect(base_url("hmember/index"));
				}
			} else {
				redirect(base_url("hmember/index"));
			}
		} else {
			redirect(base_url("hmember/index"));
		}
	}

	public function view() {
		$usertypeID = $this->session->userdata("usertypeID");
        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
        $this->data['optionalSubjects'] = pluck($this->subject_m->get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');

		if($usertypeID == 3) {
			if(permissionChecker('hmember_view')) {
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
								$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
								if($this->data['hmember']) {
									$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
									$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
									$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
									$this->data["subview"] = "hmember/view";
									$this->load->view('_layout_main', $this->data);
								} else {
									$this->data["subview"] = "hmember/message";
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
				$this->data['student'] = $this->student_m->get_single_student(array('username' => $username, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					$this->data['set'] = $this->data['student']->classesID;
					$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $this->data['student']->studentID));
					if($this->data['hmember']) {
						$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
						$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data["subview"] = "hmember/view";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data["subview"] = "hmember/message";
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
							if($checkstudent->hostel === '1') {
								$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $checkstudent->studentID));
								if($this->data['hmember']) {
									$this->data['student'] = $checkstudent;
									$this->data["class"] = $this->student_m->get_class($checkstudent->classesID);
									$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
									$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
									$this->data["section"] = $this->section_m->get_section($checkstudent->sectionID);
									$this->data["subview"] = "hmember/view";
									$this->load->view('_layout_main', $this->data);
								} else {
									$this->data["subview"] = "hmember/message";
									$this->load->view('_layout_main', $this->data);
								}
							} else {
								$this->data["subview"] = "hmember/message";
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
					$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
					if($this->data['hmember']) {
						$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
						$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data["subview"] = "hmember/view";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data["subview"] = "hmember/message";
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
			if(permissionChecker('hmember_view')) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				$url = htmlentities(escapeString($this->uri->segment(4)));
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				if((int)$id && (int)$url) {
					$this->data['set'] = $url;

					$username = $this->session->userdata("username");
					$originalStudent = $this->student_m->get_single_student(array("username" => $username));
					if($originalStudent) {
						$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
						if($originalStudent->classesID == $this->data['student']->classesID) {
							if($this->data['student']) {
								$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
								$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
								if($this->data['hmember']) {
									$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
									$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
									$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
									$this->printview($this->data, 'hmember/print_preview');
								} else {
									$this->data["subview"] = "hmember/message";
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
				$this->data['student'] = $this->student_m->get_single_student(array('username' => $username, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					$this->data['set'] = $this->data['student']->classesID;
					$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $this->data['student']->studentID));
					if($this->data['hmember']) {
						$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
						$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->printview($this->data, 'hmember/print_preview');
					} else {
						$this->data["subview"] = "hmember/message";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($usertypeID == 4) {
			if(permissionChecker('hmember_view')) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$parents = $this->parents_m->get_single_parents(array('username' => $username));
				if(count($parents)) {

					$id = htmlentities(escapeString($this->uri->segment(3)));
					if((int)$id) {
						$checkstudent = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
						if(count($checkstudent)) {
							if($checkstudent->parentID == $parents->parentsID) {
								$this->data['set'] = $id;
								if($checkstudent->hostel === '1') {
									$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $checkstudent->studentID));
									if($this->data['hmember']) {
										$this->data['student'] = $checkstudent;
										$this->data["class"] = $this->student_m->get_class($checkstudent->classesID);
										$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
										$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
										$this->data["section"] = $this->section_m->get_section($checkstudent->sectionID);
										$this->printview($this->data, 'hmember/print_preview');
									} else {
										$this->data["subview"] = "hmember/message";
										$this->load->view('_layout_main', $this->data);
									}
								} else {
									$this->data["subview"] = "hmember/message";
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
			if(permissionChecker('hmember_view')) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				$url = htmlentities(escapeString($this->uri->segment(4)));
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				if((int)$id && (int)$url) {
					$this->data['set'] = $url;
					$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
					if($this->data['student']) {
						$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
						$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
						if($this->data['hmember']) {
							$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
							$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
							$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
							$this->printview($this->data, 'hmember/print_preview');
						} else {
							$this->data["subview"] = "hmember/message";
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
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ((int)$id && (int)$url) {
			$this->data["student"] = $this->student_m->get_single_student(array('schoolyearID' => $schoolyearID, 'studentID' => $id));
			
			if($this->data["student"]) {
				$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
				$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
				if($this->data['hmember']) {
				$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
				$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
				}
				$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);

				$email = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');
				$this->viewsendtomail($this->data, 'hmember/print_preview', $email, $subject, $message);
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
			$string = base_url("hmember/index/$classID");
			echo $string;
		} else {
			redirect(base_url("hmember/index"));
		}
	}

	function categorycall() {
		$classtype = $this->input->post('id');
		if((int)$classtype) {
			$allclasstype = $this->category_m->get_order_by_category(array("hostelID" => $classtype));
			echo "<option value='0'>", $this->lang->line("hmember_select_class_type"),"</option>";
			foreach ($allclasstype as $value) {
				echo "<option value=\"$value->categoryID\">",$value->class_type,"</option>";
			}
		} 
	}

	function unique_select() {
		if($this->input->post("categoryID") == 0) {
			$this->form_validation->set_message("unique_select", "%s es requerido");
			return FALSE;
		}
		return TRUE;
	}

	function unique_gender() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			if($this->input->post("hostelID") == 0) {
				$this->form_validation->set_message("unique_gender", "%s es requerido");
				return FALSE;
			} else {
				$student = $this->student_m->get_student($id);
				$hostel = $this->hostel_m->get_single_hostel(array("hostelID" => $this->input->post("hostelID")));
				if($hostel) {
					$gender = "";
					if($student->sex == "Male") {
						$gender = "Boys";
					} else {
						$gender = "Girls";
					}

					if($hostel->htype == $gender) {
						return TRUE;
					} elseif($hostel->htype == "Combine") {
						return TRUE;
					} else {
						$this->form_validation->set_message("unique_gender", "Este Hospedaje solo tiene $hostel->htype.");
						return FALSE;
					}
				} else {
					$this->form_validation->set_message("unique_gender", "%s es requerido.");
					return FALSE;
				}
			}
		}
		return FALSE;	
	}

	function unique_category() {
		$hostelID = $this->input->post('hostelID');
		$categoryID = $this->input->post('categoryID');
		if($hostelID != 0 && $categoryID !=0 ) {
			$category = $this->category_m->get_single_category(array('hostelID' => $hostelID, 'categoryID' => $categoryID));
			if(!count($category)) {
				$this->form_validation->set_message("unique_category", "%s es requerido.");
				return FALSE;
			}
			return TRUE;
		} else {
			$this->form_validation->set_message("unique_category", "%s es requerido.");
			return FALSE;
		}
		// return TRUE;
	}

}

/* End of file hmember.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/hmember.php */