<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Dompdf\Dompdf;
class Student extends Admin_Controller {

	function __construct () {
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("section_m");
		$this->load->model("classes_m");
		$this->load->model("setting_m");
		$this->load->model("idmanager_m");
		$this->load->model('studentrelation_m');
		$this->load->model('studentgroup_m');
		$this->load->model('studentextend_m');
		$this->load->model('subject_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('student', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("student_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("student_dob"),
				'rules' => 'trim|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("student_sex"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'bloodgroup',
				'label' => $this->lang->line("student_bloodgroup"),
				'rules' => 'trim|max_length[5]|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("student_email"),
				'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("student_phone"),
				'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("student_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'state',
				'label' => $this->lang->line("student_state"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'country',
				'label' => $this->lang->line("student_country"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("student_classes"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classesID'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("student_section"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_sectionID|callback_unique_capacity'
			),
			array(
				'field' => 'registerNO',
				'label' => $this->lang->line("student_registerNO"),
				'rules' => 'trim|required|max_length[40]|callback_unique_registerNO|xss_clean'
			),
			array(
				'field' => 'roll',
				'label' => $this->lang->line("student_roll"),
				'rules' => 'trim|max_length[11]|numeric|callback_unique_roll|xss_clean'
			),
			array(
				'field' => 'guargianID',
				'label' => $this->lang->line("student_guargian"),
				'rules' => 'trim|required|max_length[11]|xss_clean|numeric'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("student_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),

			array(
				'field' => 'username',
				'label' => $this->lang->line("student_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("student_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
			),
            array(
				'field' => 'dni',
				'label' => $this->lang->line("student_dni"),
				'rules' => 'trim|required|max_length[30]|numeric|callback_unique_dni|xss_clean'
			)
            
		);
		return $rules;
	}

	public function photoupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$student = array();
		if((int)$id) {
			$student = $this->student_m->get_student($id);
		}

		$new_file = "defualt.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = rand(1, 10000000000000000);
	    	$makeRandom = hash('sha512', $random.$this->input->post('username') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.$explode[1];
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name'] = $new_file;
				$config['max_size'] = '1024';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("photo")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Archivo no valido.");
	     		return FALSE;
			}
		} else {
			if(count($student)) {
				$this->upload_data['file'] = array('file_name' => $student->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
			return TRUE;
			}
		}
	}

	public function index() {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 3) {
			if(permissionChecker('student_view')) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$singleStudent = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($singleStudent) {
					$this->data['students'] = $this->student_m->get_order_by_student(array('classesID' => $singleStudent->classesID, 'schoolyearID' => $schoolyearID));
					if($this->data['students']) {
						$sections = $this->section_m->get_order_by_section(array("classesID" => $singleStudent->classesID));
						$this->data['sections'] = $sections;
						foreach ($sections as $key => $section) {
							$this->data['allsection'][$section->sectionID] = $this->student_m->get_order_by_student(array('classesID' => $singleStudent->classesID, "sectionID" => $section->sectionID, 'schoolyearID' => $schoolyearID));
						}
					} else {
						$this->data['students'] = NULL;
					}
					$this->data["subview"] = "student/index_parents";
					$this->load->view('_layout_main', $this->data);
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
					if($this->data["student"] && $this->data["class"]) {
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data['set'] = $this->data['student']->classesID;
						if ($this->data["student"]->parentID) {
							$this->data["parent"] = $this->parents_m->get_parents($this->data["student"]->parentID);
						}
						$this->data["subview"] = "student/view";
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
		} elseif($usertypeID == 4) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$username = $this->session->userdata("username");
			$parents = $this->parents_m->get_single_parents(array('username' => $username));
			if(count($parents)) {
				$this->data['students'] = $this->student_m->get_order_by_student(array('parentID' => $parents->parentsID, 'schoolyearID' => $schoolyearID));
				$this->data["subview"] = "student/index_parents";
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

			$this->data["subview"] = "student/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
				'assets/select2/select2.js'
			)
		);


		$usertype = $this->session->userdata("usertype");
		$this->data['classes'] = $this->student_m->get_classes();
		$this->data['sections'] = $this->section_m->get_section();
		$this->data['parents'] = $this->parents_m->get_parents();

		$classesID = $this->input->post("classesID");

		if($classesID != 0) {
			$this->data['sections'] = $this->section_m->get_order_by_section(array("classesID" =>$classesID));
		} else {
			$this->data['sections'] = "empty";
		}
		$this->data['sectionID'] = $this->input->post("sectionID");

		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "student/add";
				$this->load->view('_layout_main', $this->data);
			} else {

				$sectionID = $this->input->post("sectionID");
				if($sectionID == 0) {
					$this->data['sectionID'] = 0;
				} else {
					$this->data['sections'] = $this->section_m->get_allsection($classesID);
					$this->data['sectionID'] = $this->input->post("sectionID");
				}

				$section = $this->section_m->get_section($sectionID);
				$array = array();
				$array["dni"] = $this->input->post("dni");
				$array["name"] = $this->input->post("name");
				$array["sex"] = $this->input->post("sex");
				$array["email"] = $this->input->post("email");
				$array["phone"] = $this->input->post("phone");
				$array["address"] = $this->input->post("address");
				$array["classesID"] = $this->input->post("classesID");
				$array["sectionID"] = $this->input->post("sectionID");
				$array["roll"] = $this->input->post("roll");
				$array["bloodgroup"] = $this->input->post("bloodgroup");
				$array["state"] = $this->input->post("state");
				$array["country"] = $this->input->post("country");
				$array["registerNO"] = $this->input->post("registerNO");
				$array["username"] = $this->input->post("username");
				$array['password'] = $this->student_m->hash($this->input->post("password"));
				$array['usertypeID'] = 3;
				$array['parentID'] = $this->input->post('guargianID');
				$array['library'] = 0;
				$array['hostel'] = 0;
				$array['transport'] = 0;
				$array['create_date'] = date("Y-m-d");
				$array['createschoolyearID'] = $this->data['siteinfos']->school_year;
				$array['schoolyearID'] = $this->data['siteinfos']->school_year;
				$array["create_date"] = date("Y-m-d h:i:s");
				$array["modify_date"] = date("Y-m-d h:i:s");
				$array["create_userID"] = $this->session->userdata('loginuserID');
				$array["create_username"] = $this->session->userdata('username');
				$array["create_usertype"] = $this->session->userdata('usertype');
				$array["active"] = 1;

				if($this->input->post('dob')) {
					$array["dob"] 		= date("Y-m-d", strtotime($this->input->post("dob")));
				}
				$array['photo'] = $this->upload_data['file']['file_name'];
				// For Email
				$this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));

				$this->student_m->insert_student($array);
				$studentID = $this->db->insert_id();

				$section = $this->section_m->get_section($this->input->post("sectionID"));
				$classes = $this->classes_m ->get_classes($this->input->post("classesID"));

				if(count($classes)) {
					$setClasses = $classes->classes;
				} else {
					$setClasses = NULL;
				}

				if(count($section)) {
					$setSection = $section->section;
				} else {
					$setSection = NULL;
				}

				$arrayStudentRelation = array(
					'srstudentID' => $studentID,
					//'srdni' => $this->input->post("dni"),
					'srname' => $this->input->post("name"),
					'srclassesID' => $this->input->post("classesID"),
					'srclasses' => $setClasses,
					'srroll' => $this->input->post("roll"),
					'srregisterNO' => $this->input->post("registerNO"),
					'srsectionID' => $this->input->post("sectionID"),
					'srsection' => $setSection,
					'srschoolyearID' => $this->data['siteinfos']->school_year
				);
				
				$this->studentrelation_m->insert_studentrelation($arrayStudentRelation);

				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("student/index"));
			}
		} else {
			$this->data["subview"] = "student/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
				'assets/select2/select2.js'
			)
		);
		$usertype = $this->session->userdata("usertype");
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && (int)$url) {
			$this->data['classes'] = $this->student_m->get_classes();
			$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
			$this->data['parents'] = $this->parents_m->get_parents();

			if($this->data['student']) {
				$classesID = $this->data['student']->classesID;
				$this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
			}


			$this->data['set'] = $url;
			if($this->data['student']) {
				if($_POST) {
					$rules = $this->rules();
					unset($rules[16]);
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "student/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array = array();
						$array["dni"] = $this->input->post("dni");
						$array["name"] = $this->input->post("name");
						$array["sex"] = $this->input->post("sex");
						$array["email"] = $this->input->post("email");
						$array["phone"] = $this->input->post("phone");
						$array["address"] = $this->input->post("address");
						$array["classesID"] = $this->input->post("classesID");
						$array["sectionID"] = $this->input->post("sectionID");
						$array["roll"] = $this->input->post("roll");
						$array["bloodgroup"] = $this->input->post("bloodgroup");
						$array["state"] = $this->input->post("state");
						$array["country"] = $this->input->post("country");
						$array["registerNO"] = $this->input->post("registerNO");
						$array["parentID"] = $this->input->post("guargianID");
						$array["username"] = $this->input->post("username");
						$array["modify_date"] = date("Y-m-d h:i:s");
						$array['photo'] = $this->upload_data['file']['file_name'];

						if($this->input->post('dob')) {
							$array["dob"] 	= date("Y-m-d", strtotime($this->input->post("dob")));
						} else {
							$array["dob"] = NULL;
						}


						$studentReletion = $this->studentrelation_m->get_order_by_studentrelation(array('srstudentID' => $id, 'srschoolyearID' => $this->data['siteinfos']->school_year));
						$section = $this->section_m->get_section($this->input->post("sectionID"));
						$classes = $this->classes_m ->get_classes($this->input->post("classesID"));

						if(count($classes)) {
							$setClasses = $classes->classes;
						} else {
							$setClasses = NULL;
						}

						if(count($section)) {
							$setSection = $section->section;
						} else {
							$setSection = NULL;
						}

						if(!count($studentReletion)) {
							$arrayStudentRelation = array(
								'srstudentID' => $id,
								'srname' => $this->input->post("name"),
								'srclassesID' => $this->input->post("classesID"),
								'srclasses' => $setClasses,
								'srroll' => $this->input->post("roll"),
								'srregisterNO' => $this->input->post("registerNO"),
								'srsectionID' => $this->input->post("sectionID"),
								'srsection' => $setSection,
								'srschoolyearID' => $this->data['siteinfos']->school_year
							);
							$this->studentrelation_m->insert_studentrelation($arrayStudentRelation);
						} else {
							$arrayStudentRelation = array(
								'srname' => $this->input->post("name"),
								'srclassesID' => $this->input->post("classesID"),
								'srclasses' => $setClasses,
								'srroll' => $this->input->post("roll"),
								'srregisterNO' => $this->input->post("registerNO"),
								'srsectionID' => $this->input->post("sectionID"),
								'srsection' => $setSection,
							);

							$this->studentrelation_m->update_studentrelation_with_multicondition($arrayStudentRelation, array('srstudentID' => $id, 'srschoolyearID' => $this->data['siteinfos']->school_year));
						}

						$this->student_m->update_student($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("student/index/$url"));
					}
				} else {
					$this->data["subview"] = "student/edit";
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

	public function view() {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 3) {
			if(permissionChecker('student_view')) {
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
								$this->data["class"] = $this->student_m->get_class($url);
								if($this->data["student"] && $this->data["class"]) {
									$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
									$this->data['set'] = $url;
									if ($this->data["student"]->parentID) {
										$this->data["parent"] = $this->parents_m->get_parents($this->data["student"]->parentID);
									}
									$this->data["subview"] = "student/view";
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
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$this->data['student'] = $this->student_m->get_single_student(array('username' => $username, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					if($this->data["student"] && $this->data["class"]) {
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data['set'] = $this->data['student']->classesID;
						if ($this->data["student"]->parentID) {
							$this->data["parent"] = $this->parents_m->get_parents($this->data["student"]->parentID);
						}
						$this->data["subview"] = "student/view";
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
		} elseif($usertypeID == 4) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$username = $this->session->userdata("username");
			$parents = $this->parents_m->get_single_parents(array('username' => $username));
			if(count($parents)) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				$url = htmlentities(escapeString($this->uri->segment(4)));
				if((int)$id && (int)$url) {
					$checkstudent = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
					if(count($checkstudent)) {
						if($checkstudent->parentID == $parents->parentsID) {
							$this->data['set'] = $checkstudent->classesID;
							$this->data['student'] = $checkstudent;
							$this->data["class"] = $this->student_m->get_class($checkstudent->classesID);
							if($this->data["class"]) {
								$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
								$this->data['set'] = $url;
								if ($this->data["student"]->parentID) {
									$this->data["parent"] = $this->parents_m->get_parents($this->data["student"]->parentID);
								}
								$this->data["subview"] = "student/view";
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
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$usertype = $this->session->userdata("usertype");
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			if ((int)$id && (int)$url) {
				$this->data["student"] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));

				$this->data["class"] = $this->student_m->get_class($url);
				if($this->data["student"] && $this->data["class"]) {
					$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
					$this->data['set'] = $url;
					if ($this->data["student"]->parentID) {
						$this->data["parent"] = $this->parents_m->get_parents($this->data["student"]->parentID);
					}
					$this->data["subview"] = "student/view";
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

	public function print_preview() {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 3) {
			if(permissionChecker('student_view')) {
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
								$this->data["class"] = $this->student_m->get_class($url);
								if($this->data["student"] && $this->data["class"]) {
									$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
									$this->data['set'] = $url;
									if ($this->data["student"]->parentID) {
										$this->data["parent"] = $this->parents_m->get_parents($this->data["student"]->parentID);
									} else {
										$this->data["parent"] = array();
									}
									$this->printview($this->data, 'student/print_preview');
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
				$this->data['student'] = $this->student_m->get_single_student(array('username' => $username, 'schoolyearID' => $schoolyearID));
				if($this->data['student']) {
					$this->data["class"] = $this->student_m->get_class($this->data['student']->classesID);
					if($this->data["student"] && $this->data["class"]) {
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data['set'] = $this->data['student']->classesID;
						if ($this->data["student"]->parentID) {
							$this->data["parent"] = $this->parents_m->get_parents($this->data["student"]->parentID);
						}
						$this->printview($this->data, 'student/print_preview');
					} else {
						$this->data["subview"] = "error";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($usertypeID == 4) {
			if(permissionChecker('student_view')) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$parents = $this->parents_m->get_single_parents(array('username' => $username));
				if(count($parents)) {
					$id = htmlentities(escapeString($this->uri->segment(3)));
					$url = htmlentities(escapeString($this->uri->segment(4)));
					if((int)$id && (int)$url) {
						$checkstudent = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
						if(count($checkstudent)) {
							if($checkstudent->parentID == $parents->parentsID) {
								$this->data['set'] = $checkstudent->classesID;
								$this->data['student'] = $checkstudent;
								$this->data["class"] = $this->student_m->get_class($checkstudent->classesID);
								if($this->data["class"]) {
									$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
									$this->data['set'] = $url;
									if ($this->data["student"]->parentID) {
										$this->data["parent"] = $this->parents_m->get_parents($this->data["student"]->parentID);
									} else {
										$this->data["parent"] = array();
									}
									$this->printview($this->data, 'student/print_preview');
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
				$this->data["subview"] = "errorpermission";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			if(permissionChecker('student_view')) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$usertype = $this->session->userdata("usertype");
				$id = htmlentities(escapeString($this->uri->segment(3)));
				$url = htmlentities(escapeString($this->uri->segment(4)));
				if ((int)$id && (int)$url) {
					$this->data["student"] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));

					$this->data["class"] = $this->student_m->get_class($url);
					if($this->data["student"] && $this->data["class"]) {
						$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
						$this->data['set'] = $url;
						if ($this->data["student"]->parentID) {
							$this->data["parent"] = $this->parents_m->get_parents($this->data["student"]->parentID);
						} else {
							$this->data["parent"] = array();
						}
                       $this->data['panel_title'] = $this->lang->line('panel_title');
						$this->printview($this->data, 'student/print_preview');
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
		$usertype = $this->session->userdata("usertype");
		$id = $this->input->post('id');
		$url = $this->input->post('set');
		if ((int)$id && (int)$url) {
			$this->data["student"] = $this->student_m->get_student($id);
			$this->data["class"] = $this->student_m->get_class($url);
			if($this->data["student"] && $this->data["class"]) {
				$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);

				$this->data["parent"] = $this->parents_m->get_parents($this->data["student"]->parentID);
				$email = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');

				$this->viewsendtomail($this->data, 'student/print_preview', $email, $subject, $message);
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
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$usertype = $this->session->userdata("usertype");
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if ((int)$id && (int)$url) {
			$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $id, 'schoolyearID' => $schoolyearID));
			if($this->data['student']) {
				if($this->data['student']->photo != 'defualt.png') {
					if(file_exists(FCPATH.'uploads/images/'.$this->data['student']->photo)) {
						unlink(FCPATH.'uploads/images/'.$this->data['student']->photo);
					}
				}
				$this->student_m->delete_student($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("student/index/$url"));
			} else {
				redirect(base_url("student/index"));
			}
		} else {
			redirect(base_url("student/index/$url"));
		}

	}

	public function unique_roll() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$schoolyearID = $this->data['siteinfos']->school_year;
		if((int)$id) {
			$student = $this->student_m->get_order_by_roll(array("roll" => $this->input->post("roll"), "studentID !=" => $id, "classesID" => $this->input->post('classesID'), 'schoolyearID' => $schoolyearID));
			if(count($student)) {
				$this->form_validation->set_message("unique_roll", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		} else {
			$student = $this->student_m->get_order_by_roll(array("roll" => $this->input->post("roll"), "classesID" => $this->input->post('classesID'), 'schoolyearID' => $schoolyearID));

			if(count($student)) {
				$this->form_validation->set_message("unique_roll", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function lol_username() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$student_info = $this->student_m->get_single_student(array('studentID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username'), "username !=" => $student_info->username));
				if(count($user)) {
					$this->form_validation->set_message("lol_username", "%s ya existe.");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}
			if(in_array('no', $array['permition'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username')));
				if(count($user)) {
					$this->form_validation->set_message("lol_username", "%s ya existe.");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}

			if(in_array('no', $array['permition'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	public function date_valid($date) {
		if($date) {
			if(strlen($date) <10) {
				$this->form_validation->set_message("date_valid", "%s no es un formato valido dd-mm-yyyy");
		     	return FALSE;
			} else {
		   		$arr = explode("-", $date);
		        $dd = $arr[0];
		        $mm = $arr[1];
		        $yyyy = $arr[2];
		      	if(checkdate($mm, $dd, $yyyy)) {
		      		return TRUE;
		      	} else {
		      		$this->form_validation->set_message("date_valid", "%s no es un formato valido dd-mm-yyyy");
		     		return FALSE;
		      	}
		    }
		}
		return TRUE;
	}

	public function unique_classesID() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classesID", "%s es requerido.");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_sectionID() {
		if($this->input->post('sectionID') == 0) {
			$this->form_validation->set_message("unique_sectionID", "%s es requerido.");
	     	return FALSE;
		}
		return TRUE;
	}

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("student/index/$classID");
			echo $string;
		} else {
			redirect(base_url("student/index"));
		}
	}

	public function unique_email() {
		if($this->input->post('email')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$student_info = $this->student_m->get_single_student(array('studentID' => $id));
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $student_info->username ));
					if(count($user)) {
						$this->form_validation->set_message("unique_email", "%s ya existe.");
						$array['permition'][$i] = 'no';
					} else {
						$array['permition'][$i] = 'yes';
					}
					$i++;
				}
				if(in_array('no', $array['permition'])) {
					return FALSE;
				} else {
					return TRUE;
				}
			} else {
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email')));
					if(count($user)) {
						$this->form_validation->set_message("unique_email", "%s ya existe.");
						$array['permition'][$i] = 'no';
					} else {
						$array['permition'][$i] = 'yes';
					}
					$i++;
				}

				if(in_array('no', $array['permition'])) {
					return FALSE;
				} else {
					return TRUE;
				}
			}
		}
		return TRUE;
	}

	function sectioncall() {
		$classesID = $this->input->post('id');
		if((int)$classesID) {
			$allsection = $this->section_m->get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("student_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		}
	}

	public function unique_capacity() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			if($this->input->post('sectionID')) {
				$sectionID = $this->input->post('sectionID');
				$classesID = $this->input->post('classesID');
				$schoolyearID = $this->data['siteinfos']->school_year;
				$section = $this->section_m->get_section($this->input->post('sectionID'));
				$student = $this->student_m->get_order_by_student(array('classesID' => $classesID, 'sectionID' => $sectionID, 'schoolyearID' => $schoolyearID, 'studentID !=' => $id));
				if(count($student) >= $section->capacity) {
					$this->form_validation->set_message("unique_capacity", "%s capacidad llena.");
		     		return FALSE;
				}
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_capacity", "%s es requerido.");
		     	return FALSE;
			}
		} else {
			if($this->input->post('sectionID')) {
				$sectionID = $this->input->post('sectionID');
				$classesID = $this->input->post('classesID');
				$schoolyearID = $this->data['siteinfos']->school_year;
				$section = $this->section_m->get_section($this->input->post('sectionID'));
				$student = $this->student_m->get_order_by_student(array('classesID' => $classesID, 'sectionID' => $sectionID, 'schoolyearID' => $schoolyearID));
				if(count($student) >= $section->capacity) {
					$this->form_validation->set_message("unique_capacity", "%s capacidad llena.");
		     		return FALSE;
				}
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_capacity", "%s es requerido.");
		     	return FALSE;
			}
		}
	}

	public function unique_registerNO() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$schoolyearID = $this->data['siteinfos']->school_year;
		if((int)$id) {
			$student = $this->student_m->get_single_student(array("registerNO" => $this->input->post("registerNO"), "studentID !=" => $id, "classesID" => $this->input->post('classesID'), 'schoolyearID' => $schoolyearID));
			if(count($student)) {
				$this->form_validation->set_message("unique_registerNO", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		} else {
			$student = $this->student_m->get_single_student(array("registerNO" => $this->input->post("registerNO"), "classesID" => $this->input->post('classesID'), 'schoolyearID' => $schoolyearID));

			if(count($student)) {
				$this->form_validation->set_message("unique_registerNO", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function unique_dni() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$student_info = $this->student_m->get_student($id);
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("dni" => $this->input->post('dni'), "username !=" => $student_info->username));
				if(count($user)) {
					$this->form_validation->set_message("unique_dni", "%s ya existe.");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}
			if(in_array('no', $array['permition'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("dni" => $this->input->post('dni')));
				if(count($user)) {
					$this->form_validation->set_message("unique_dni", "%s ya existe.");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}

			if(in_array('no', $array['permition'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	function active() {
		if(permissionChecker('student_edit')) {
			$id = $this->input->post('id');
			$status = $this->input->post('status');
			if($id != '' && $status != '') {
				if((int)$id) {
					if($status == 'chacked') {
						$this->student_m->update_student(array('active' => 1), $id);
						echo 'Success';
					} elseif($status == 'unchacked') {
						$this->student_m->update_student(array('active' => 0), $id);
						echo 'Success';
					} else {
						echo "Error";
					}
				} else {
					echo "Error";
				}
			} else {
				echo "Error";
			}
		} else {
			echo "Error";
		}
	}
}

/* End of file student.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/student.php */
