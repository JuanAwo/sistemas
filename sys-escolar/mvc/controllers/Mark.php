<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mark extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("mark_m");
		$this->load->model("grade_m");
		$this->load->model("classes_m");
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("user_m");
		$this->load->model("section_m");
		$this->load->model("parents_m");
		$this->load->model("markpercentage_m");
		$this->load->model("markrelation_m");
		$this->load->model('setting_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('mark', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("mark_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_exam'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("mark_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_classes'
			),
			array(
				'field' => 'subjectID',
				'label' => $this->lang->line("mark_subject"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_subject'
			)
		);
		return $rules;
	}


	function getMark($studentID, $classesID, $pdf=FALSE) {
		$studentID = $studentID;
		$classID = $classesID;
		if((int)$studentID && (int)$classID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data["student"] = $this->student_m->get_student($studentID);
			$this->data["classes"] = $this->student_m->get_class($classID);
			if($this->data["student"] && $this->data["classes"]) {
				$this->data['set'] 				= $classID;
				$this->data["exams"] 			= $this->exam_m->get_exam();
				$this->data["grades"] 			= $this->grade_m->get_grade();
				$this->data['markpercentages']	= $this->markpercentage_m->get_markpercentage();
				$subjects 						= $this->subject_m->get_subject_call($classID);

					$allMarkWithRelation = $this->markrelation_m->get_all_mark_with_relation($classID, $schoolyearID);
					$studentMarkPercentage = array();
					foreach ($allMarkWithRelation as $key => $value) {
						$studentMarkPercentage[$value->studentID][$value->examID]['markpercentage'][] = $value->markpercentageID;
						$studentMarkPercentage[$value->studentID][$value->examID][$value->subjectID] = $value->markID;
					}

					$markpercentages = pluck($this->data['markpercentages'], 'markpercentageID');
					foreach ($this->data["exams"] as $exam) {
						$studentPercentage = isset($studentMarkPercentage[$studentID][$exam->examID]['markpercentage']) ? $studentMarkPercentage[$studentID][$exam->examID]['markpercentage'] : [] ;
						if(count($studentPercentage)) {
							$diffMarkPercentage = array_diff($markpercentages, $studentMarkPercentage[$studentID][$exam->examID]['markpercentage']);
							foreach ($diffMarkPercentage as $item) {
								if(isset($studentMarkPercentage[$studentID][$exam->examID]) && count($studentMarkPercentage[$studentID][$exam->examID])) {
									foreach ($studentMarkPercentage[$studentID][$exam->examID] as $subjectID => $markID) {
										if($subjectID == 'markpercentage') continue;
										$markRelation = [
											"markID" => $markID,
											"markpercentageID" => $item
										];
										$this->markrelation_m->insert($markRelation);
									}
								}
							}
						}
					}

				$this->data['markpercentages'] = $this->get_setting_mark_percentage();
				$marks = $this->mark_m->get_order_by_student_mark_with_subject($classID, $this->data['student']->schoolyearID, $studentID);
				$allStudentMarks = $this->mark_m->get_order_by_student_mark_with_subject($classID, $this->data['student']->schoolyearID);
				$this->data['marks'] = $marks;
				$separatedMarks = array();
				foreach ($marks as $key => $value) {
					$separatedMarks[$value->examID][$value->subjectID]['subject'] = $value->subject;
					$separatedMarks[$value->examID][$value->subjectID][$value->markpercentageID]= $value->mark;
				}
				$this->data['separatedMarks'] = $separatedMarks;
				$highestMarks = array();
				foreach ($allStudentMarks as $key => $value) {
					if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}
				$this->data["highestMarks"] = $highestMarks;
				$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
				if($pdf == TRUE) {
					$this->printview($this->data, 'mark/print_preview');
				} else {
					$this->data["subview"] = "mark/view";
					$this->load->view('_layout_main', $this->data);
				}	
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function getMarkSendToMail($studentID, $classesID, $sysEmail, $sysSubject, $sysMessage) {
		$studentID = $studentID;
		$classID = $classesID;
		if((int)$studentID && (int)$classID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data["student"] = $this->student_m->get_student($studentID);
			$this->data["classes"] = $this->student_m->get_class($classID);
			if($this->data["student"] && $this->data["classes"]) {
				$this->data['set'] 				= $classID;
				$this->data["exams"] 			= $this->exam_m->get_exam();
				$this->data["grades"] 			= $this->grade_m->get_grade();
				$this->data['markpercentages']	= $this->markpercentage_m->get_markpercentage();
				$subjects 						= $this->subject_m->get_subject_call($classID);

					$allMarkWithRelation = $this->markrelation_m->get_all_mark_with_relation($classID, $schoolyearID);
					$studentMarkPercentage = array();
					foreach ($allMarkWithRelation as $key => $value) {
						$studentMarkPercentage[$value->studentID][$value->examID]['markpercentage'][] = $value->markpercentageID;
						$studentMarkPercentage[$value->studentID][$value->examID][$value->subjectID] = $value->markID;
					}

					$markpercentages = pluck($this->data['markpercentages'], 'markpercentageID');
					foreach ($this->data["exams"] as $exam) {
						$studentPercentage = isset($studentMarkPercentage[$studentID][$exam->examID]['markpercentage']) ? $studentMarkPercentage[$studentID][$exam->examID]['markpercentage'] : [] ;
						if(count($studentPercentage)) {
							$diffMarkPercentage = array_diff($markpercentages, $studentMarkPercentage[$studentID][$exam->examID]['markpercentage']);
							foreach ($diffMarkPercentage as $item) {
								if(isset($studentMarkPercentage[$studentID][$exam->examID]) && count($studentMarkPercentage[$studentID][$exam->examID])) {
									foreach ($studentMarkPercentage[$studentID][$exam->examID] as $subjectID => $markID) {
										if($subjectID == 'markpercentage') continue;
										$markRelation = [
											"markID" => $markID,
											"markpercentageID" => $item
										];
										$this->markrelation_m->insert($markRelation);
									}
								}
							}
						}
					}

				$this->data['markpercentages'] = $this->get_setting_mark_percentage();
				$marks = $this->mark_m->get_order_by_student_mark_with_subject($classID, $this->data['student']->schoolyearID, $studentID);
				$allStudentMarks = $this->mark_m->get_order_by_student_mark_with_subject($classID, $this->data['student']->schoolyearID);
				$this->data['marks'] = $marks;
				$separatedMarks = array();
				foreach ($marks as $key => $value) {
					$separatedMarks[$value->examID][$value->subjectID]['subject'] = $value->subject;
					$separatedMarks[$value->examID][$value->subjectID][$value->markpercentageID]= $value->mark;
				}
				$this->data['separatedMarks'] = $separatedMarks;
				$highestMarks = array();
				foreach ($allStudentMarks as $key => $value) {
					if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}
				$this->data["highestMarks"] = $highestMarks;
				$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
				$this->viewsendtomail($this->data, 'mark/print_preview', $sysEmail, $sysSubject, $sysMessage);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function index() {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 3) {
			if(permissionChecker('mark_view')) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$singleStudent = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if($singleStudent) {
					$this->data['students'] = $this->student_m->get_order_by_student(array('classesID' => $singleStudent->classesID, 'schoolyearID' => $schoolyearID));
					$this->data['set'] = $singleStudent->classesID;
					$this->data["subview"] = "mark/index_parents";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data['students'] = array();
					$this->data["subview"] = "mark/index_parents";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$username = $this->session->userdata("username");
				$singleStudent = $this->student_m->get_single_student(array("username" => $username, 'schoolyearID' => $schoolyearID));
				if(count($singleStudent)) {
					$this->getMark($singleStudent->studentID, $singleStudent->classesID);
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
				$this->data["subview"] = "mark/index_parents";
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

			$this->data["subview"] = "mark/index";
			$this->load->view('_layout_main', $this->data);
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

        $this->data['students'] = array();
        $this->data['set_exam'] = 0;
        $this->data['set_classes'] = 0;
        $this->data['set_section'] = 0;
        $this->data['set_subject'] = 0;

        $this->data['sendExam'] = array();
        $this->data['sendSubject'] = array();
        $this->data['sendClasses'] = array();
        $this->data['sendSection'] = array();

        $classesID = $this->input->post("classesID");
        if($classesID != 0) {
            $this->data['subjects'] = $this->subject_m->get_subject_call($classesID);
            $this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
        } else {
            $this->data['subjects'] = 0;
            $this->data['sections'] = 0;
        }
        $this->data['exams'] = $this->exam_m->get_exam();
        $this->data['classes'] = $this->classes_m->get_classes();
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "mark/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $examID = $this->input->post('examID');
                $classesID                      = $this->input->post('classesID');
                $sectionID                      = $this->input->post('sectionID');
                $subjectID                      = $this->input->post('subjectID');
                $this->data['set_exam']         = $examID;
                $this->data['set_classes']      = $classesID;
                $this->data['set_section']      = $sectionID;
                $this->data['set_subject']      = $subjectID;
                $this->data['markpercentages']  = $this->markpercentage_m->get_markpercentage();

                $exam = $this->exam_m->get_exam($examID);
                $this->data['sendExam'] = $exam;
                $subject = $this->subject_m->get_subject($subjectID);
                $this->data['sendSubject'] = $subject;
                $classes = $this->classes_m->get_classes($classesID);
                $this->data['sendClasses'] = $classes;
                $section = $this->section_m->get_section($sectionID);
                $this->data['sendSection'] = $section;
                $year = date("Y");
                $schoolyearID = $this->data['siteinfos']->school_year;
                $students = $this->student_m->get_order_by_student(array("classesID" => $classesID, 'schoolyearID' => $schoolyearID));

                    if(count($students)) {
                        foreach ($students as $student) {
                            $studentID = $student->studentID;
                            $in_student = $this->mark_m->get_order_by_mark(array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $studentID, 'schoolyearID' => $this->data['siteinfos']->school_year));
                            if(!count($in_student)) {
                                $array = array(
                                    "examID" => $examID,
									"schoolyearID" => $this->data['siteinfos']->school_year,
                                    "exam" => $exam->exam,
                                    "studentID" => $studentID,
                                    "classesID" => $classesID,
                                    "subjectID" => $subjectID,
                                    "subject" => $subject->subject,
                                    "year" => $year
                                );
                                $this->mark_m->insert_mark($array);
								$markID = $this->db->insert_id();
								$markpercentages = $this->data['markpercentages'];
								foreach ($markpercentages as $value) {
									$markRelation = [
										"markID" => $markID,
										"markpercentageID" => $value->markpercentageID
									];
									$this->markrelation_m->insert($markRelation);
								}
                            }
                        }
                        
                        $all_student = $this->mark_m->get_order_by_mark(array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID));
                        $this->data['marks'] = $all_student;

                    }

					if(count($students)) {
						$allMarkWithRelation = $this->markrelation_m->get_all_mark_with_relation($classesID, $this->data['siteinfos']->school_year);
						$studentMarkPercentage = array();
						foreach ($allMarkWithRelation as $key => $value) {
							$studentMarkPercentage[$value->studentID][$value->examID]['markpercentage'][] = $value->markpercentageID;
							$studentMarkPercentage[$value->studentID][$value->examID][$value->subjectID] = $value->markID;
						}

						$markpercentages = pluck($this->data['markpercentages'], 'markpercentageID');
						foreach ($students as $student) {
							$studentPercentage = isset($studentMarkPercentage[$student->studentID][$examID]['markpercentage']) ? $studentMarkPercentage[$student->studentID][$examID]['markpercentage'] : [];
							if(count($studentPercentage)) {
								$diffMarkPercentage = array_diff($markpercentages, $studentMarkPercentage[$student->studentID][$examID]['markpercentage']);
								foreach ($diffMarkPercentage as $item) {
									$markRelation = [
										"markID" => $studentMarkPercentage[$student->studentID][$examID][$subjectID],
										"markpercentageID" => $item
									];
									$this->markrelation_m->insert($markRelation);
								}
							}
						}
					}

				$students = $this->student_m->get_order_by_student(array("classesID" => $classesID, 'sectionID' => $sectionID, 'schoolyearID' => $schoolyearID));
				$this->data['students'] = $students;	
				$this->data['markpercentages']  = $this->get_setting_mark_percentage();
				$this->data['markRelations'] = $this->getMarkRelationArray($this->mark_m->student_all_mark_array($subjectID));
				$this->data["subview"] = "mark/add";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "mark/add";
            $this->load->view('_layout_main', $this->data);
        }
	}

	function get_setting_mark_percentage() {
		$markpercentagesDatabases = $this->markpercentage_m->get_markpercentage();
		$markpercentagesSettings = $this->setting_m->get_markpercentage();
		$markpercentages = array();
		$array = array();
		if(count($markpercentagesSettings)) {
			foreach ($markpercentagesSettings as $key => $markpercentagesSetting) {
				$expfieldname = explode('_', $markpercentagesSetting->fieldoption);
				$array[] = (int)$expfieldname[1];
			}
		}

		if(count($markpercentagesDatabases)) {
			foreach ($markpercentagesDatabases as $key => $markpercentagesDatabase) {
				if(in_array($markpercentagesDatabase->markpercentageID, $array)) {
					$markpercentages[] = $markpercentagesDatabase;
				}
			}
		}
		return $markpercentages;
	}

	function getMarkRelationArray($arrays=NULL) {
		$mark = array();
		if(count($arrays)) {
			foreach ($arrays as $key => $array) {
				$mark[$array->studentID][$array->markpercentageID] = $array->mark;
			}
		}
		return $mark;
	}

	function mark_send() {
		$examID 		= $this->input->post("examID");
		$classesID		= $this->input->post("classesID");
		$subjectID 		= $this->input->post("subjectID");
		$inputs 		= $this->input->post("inputs");
		$schoolyearID 	= $this->data['siteinfos']->school_year;
		foreach ($inputs as $key => $value) {
			$data = explode('-', $value['mark']);
			$inputs[$key]['id'] = $data[1];
			$inputs[$key]['studentID'] = $data[2];
		}


		foreach ($inputs as $value) {
			$array 			= array("schoolyearID" => $schoolyearID);
			$markInfo 		= $this->mark_m->update_mark_with_condition($array, ["examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $value['studentID']]);
			$markID 		= $this->mark_m->get_single_mark(["schoolyearID" => $schoolyearID, "examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $value['studentID']])->markID;
			if(!empty($value['value']) || $value['value'] != "") {
				$markRelation 	= [
					'mark' => $value['value']
				];
				$this->markrelation_m->update_mark_with_condition($markRelation,['markID' => $markID, 'markpercentageID' => $value['id']]);
			}
		}
		echo $this->lang->line('mark_success');
	}

	public function view() {
		$usertypeID = $this->session->userdata("usertypeID");
		if($usertypeID == 3) {
			if(permissionChecker('mark_view')) {
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
								$studentID 	= htmlentities(escapeString($this->uri->segment(3)));
								$classID 	= htmlentities(escapeString($this->uri->segment(4)));
								$this->getMark($studentID, $classID);
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
					$studentID 	= $student->studentID;
					$classID = $student->classesID;
					$this->getMark($studentID, $classID);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($usertypeID == 4) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID 	= htmlentities(escapeString($this->uri->segment(3)));
			$classID 	= htmlentities(escapeString($this->uri->segment(4)));
			$this->getMark($studentID, $classID);
		} else {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID 	= htmlentities(escapeString($this->uri->segment(3)));
			$classID 	= htmlentities(escapeString($this->uri->segment(4)));
			$this->getMark($studentID, $classID);
		}
	}

	function print_preview() {
		$usertypeID = $this->session->userdata("usertypeID");
		if($usertypeID == 3) {
			if(permissionChecker('mark_view')) {
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
								$studentID 	= htmlentities(escapeString($this->uri->segment(3)));
								$classID 	= htmlentities(escapeString($this->uri->segment(4)));
								$this->getMark($studentID, $classID, TRUE);
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
					$studentID 	= htmlentities(escapeString($this->uri->segment(3)));
					$classID 	= htmlentities(escapeString($this->uri->segment(4)));
					$this->getMark($studentID, $classID, TRUE);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($usertypeID == 4) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID 	= htmlentities(escapeString($this->uri->segment(3)));
			$classID 	= htmlentities(escapeString($this->uri->segment(4)));
			$this->getMark($studentID, $classID, TRUE);
		} else {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID 	= htmlentities(escapeString($this->uri->segment(3)));
			$classID 	= htmlentities(escapeString($this->uri->segment(4)));
			$this->getMark($studentID, $classID, TRUE);
		}
	}

	public function send_mail() {
		$id = $this->input->post('id');
		$url = $this->input->post('set');
		if ((int)$id && (int)$url) {
			$this->data["student"] = $this->student_m->get_student($id);
			$this->data["classes"] = $this->student_m->get_class($url);
			if($this->data["student"] && $this->data["classes"]) {
				$email = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');
				$this->getMarkSendToMail($id, $url, $email, $subject, $message);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function mark_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("mark/index/$classID");
			echo $string;
		} else {
			redirect(base_url("mark/index"));
		}
	}

	function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("mark/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("mark/index"));
		}
	}

	function subjectcall() {
		$id = $this->input->post('id');
		if((int)$id) {
			$allsubject = $this->subject_m->get_order_by_subject(array("classesID" => $id));
			echo "<option value='0'>", $this->lang->line("mark_select_subject"),"</option>";
			foreach ($allsubject as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}
		} else {
			echo "<option value='0'>", $this->lang->line("mark_select_subject"),"</option>";
		}
	}

	function sectioncall() {
		$id = $this->input->post('id');
		if((int)$id) {
			$allsection = $this->section_m->get_order_by_section(array("classesID" => $id));
			echo "<option value='0'>", $this->lang->line("mark_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		} else {
			echo "<option value='0'>", $this->lang->line("mark_select_section"),"</option>";
		}
	}

	function check_exam() {
		if($this->input->post('examID') == 0) {
			$this->form_validation->set_message("check_exam", "%s es requerido");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_classes() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("check_classes", "%s es requerido");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_subject() {
		if($this->input->post('subjectID') == 0) {
			$this->form_validation->set_message("check_subject", "%s es requerido");
	     	return FALSE;
		}
		return TRUE;
	}
}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */
