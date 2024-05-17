<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailandsms extends Admin_Controller {

	function __construct () {
		parent::__construct();
		$this->load->model('usertype_m');
		$this->load->model('systemadmin_m');
		$this->load->model('teacher_m');
		$this->load->model('student_m');
		$this->load->model('parents_m');
		$this->load->model('user_m');
		$this->load->model('classes_m');
		$this->load->model('section_m');
		$this->load->model("mark_m");
		$this->load->model("grade_m");
		$this->load->model("exam_m");
		$this->load->model('mailandsms_m');
		$this->load->model('mailandsmstemplate_m');
		$this->load->model('mailandsmstemplatetag_m');
		$this->load->library("email");
		$language = $this->session->userdata('lang');
		$this->lang->load('mailandsms', $language);

	}

	protected function rules_mail() {
		$rules = array(
			array(
				'field' => 'email_usertypeID',
				'label' => $this->lang->line("mailandsms_usertype"),
				'rules' => 'trim|required|xss_clean|max_length[15]|callback_check_email_usertypeID'
			),
			array(
				'field' => 'email_schoolyear',
				'label' => $this->lang->line("mailandsms_schoolyear"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'email_class',
				'label' => $this->lang->line("mailandsms_class"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'email_users',
				'label' => $this->lang->line("mailandsms_users"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'email_template',
				'label' => $this->lang->line("mailandsms_template"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'email_subject',
				'label' => $this->lang->line("mailandsms_subject"),
				'rules' => 'trim|required|xss_clean|max_length[255]'
			),
			array(
				'field' => 'email_message',
				'label' => $this->lang->line("mailandsms_message"),
				'rules' => 'trim|required|xss_clean|max_length[20000]'
			),
		);
		return $rules;
	}


	public function index() {
		$this->data['mailandsmss'] = $this->mailandsms_m->get_mailandsms_with_usertypeID();
		$this->data["subview"] = "mailandsms/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/editor/jquery-te-1.4.0.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/editor/jquery-te-1.4.0.min.js'
			)
		);
		$this->data['usertypes'] = $this->usertype_m->get_usertype();
		$this->data['schoolyears'] = $this->schoolyear_m->get_schoolyear();


		$email_usertypeID = $this->input->post("email_usertypeID");
		if($email_usertypeID && $email_usertypeID != 'select') {
			$this->data['email_usertypeID'] = $email_usertypeID;

		} else {
			$this->data['email_usertypeID'] = 'select';
		}


		$sms_usertypeID = $this->input->post("sms_usertypeID");
		if($sms_usertypeID && $sms_usertypeID != 'select') {
			$this->data['sms_usertypeID'] = $sms_usertypeID;
		} else {
			$this->data['sms_usertypeID'] = 'select';
		}


		if($_POST) {
			if($this->input->post('type') == "email") {
				$rules = $this->rules_mail();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					// echo validation_errors();
					$this->data["email"] = 1;
					$this->data["sms"] = 0;
					$this->data["subview"] = "mailandsms/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$usertypeID = $this->input->post('email_usertypeID');

					if($usertypeID == 1) { 
						$systemadminID = $this->input->post('email_users');
						if($systemadminID == 'select') {
							$message = $this->input->post('email_message');
							$multisystemadmins = $this->systemadmin_m->get_systemadmin();
							if(count($multisystemadmins)) {
								$countusers = '';
								foreach ($multisystemadmins as $key => $multisystemadmin) {
									$this->userConfigEmail($message, $multisystemadmin, $usertypeID);
									$countusers .= $multisystemadmin->name .' ,';
								}
								$array = array(
									'usertypeID' => $usertypeID,
									'users' => $countusers,
									'type' => ucfirst($this->input->post('type')),
									'message' => $this->input->post('email_message'),
									'year' => date('Y'),
									'senderusertypeID' => $this->session->userdata('usertypeID'),
									'senderID' => $this->session->userdata('loginuserID')
								);
								$this->mailandsms_m->insert_mailandsms($array);
								redirect(base_url('mailandsms/index'));
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						} else {
							$message = $this->input->post('email_message');
							$singlesystemadmin = $this->systemadmin_m->get_systemadmin($systemadminID);
							if(count($singlesystemadmin)) {
								$this->userConfigEmail($message, $singlesystemadmin, $usertypeID);
								$array = array(
									'usertypeID' => $usertypeID,
									'users' => $singlesystemadmin->name,
									'type' => ucfirst($this->input->post('type')),
									'message' => $this->input->post('email_message'),
									'year' => date('Y'),
									'senderusertypeID' => $this->session->userdata('usertypeID'),
									'senderID' => $this->session->userdata('loginuserID')
								);
								$this->mailandsms_m->insert_mailandsms($array);
								redirect(base_url('mailandsms/index'));
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						}
					} elseif($usertypeID == 2) { 
						$teacherID = $this->input->post('email_users');
						if($teacherID == 'select') {
							$message = $this->input->post('email_message');
							$multiteachers = $this->teacher_m->get_teacher();
							if(count($multiteachers)) {
								$countusers = '';
								foreach ($multiteachers as $key => $multiteacher) {
									$this->userConfigEmail($message, $multiteacher, $usertypeID);
									$countusers .= $multiteacher->name .' ,';
								}
								$array = array(
									'usertypeID' => $usertypeID,
									'users' => $countusers,
									'type' => ucfirst($this->input->post('type')),
									'message' => $this->input->post('email_message'),
									'year' => date('Y'),
									'senderusertypeID' => $this->session->userdata('usertypeID'),
									'senderID' => $this->session->userdata('loginuserID')
								);
								$this->mailandsms_m->insert_mailandsms($array);
								redirect(base_url('mailandsms/index'));
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						} else {
							$message = $this->input->post('email_message');
							$singleteacher = $this->teacher_m->get_teacher($teacherID);
							if(count($singleteacher)) {
								$this->userConfigEmail($message, $singleteacher, $usertypeID);
								$array = array(
									'usertypeID' => $usertypeID,
									'users' => $singleteacher->name,
									'type' => ucfirst($this->input->post('type')),
									'message' => $this->input->post('email_message'),
									'year' => date('Y'),
									'senderusertypeID' => $this->session->userdata('usertypeID'),
									'senderID' => $this->session->userdata('loginuserID')
								);
								$this->mailandsms_m->insert_mailandsms($array);
								redirect(base_url('mailandsms/index'));

							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						}
					} elseif($usertypeID == 3) { /* FOR STUDENT */

						$studentID = $this->input->post('email_users');
						if($studentID == 'select') {
							$class = $this->input->post('email_class');
							if($class == 'select') {
								/* Multi School Year */
								$schoolyear = $this->input->post('email_schoolyear');
								if($schoolyear == 'select') {
									$message = $this->input->post('email_message');
									$multiSchoolYearStudents = $this->student_m->get_student();
									if(count($multiSchoolYearStudents)) {
										$countusers = '';
										foreach ($multiSchoolYearStudents as $key => $multiSchoolYearStudent) {
											$this->userConfigEmail($message, $multiSchoolYearStudent, $usertypeID);
											$countusers .= $multiSchoolYearStudent->name .' ,';
										}
										$array = array(
											'usertypeID' => $usertypeID,
											'users' => $countusers,
											'type' => ucfirst($this->input->post('type')),
											'message' => $this->input->post('email_message'),
											'year' => date('Y'),
											'senderusertypeID' => $this->session->userdata('usertypeID'),
											'senderID' => $this->session->userdata('loginuserID')
										);
										$this->mailandsms_m->insert_mailandsms($array);
										redirect(base_url('mailandsms/index'));
									} else {
										$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
										redirect(base_url('mailandsms/add'));
									}
								} else {
							
									$message = $this->input->post('email_message');
									$singleSchoolYear = $this->input->post('email_schoolyear');
									$singleSchoolYearStudents = $this->student_m->get_order_by_student(array('schoolyearID' => $singleSchoolYear));
									if(count($singleSchoolYearStudents)) {
										$countusers = '';
										foreach ($singleSchoolYearStudents as $key => $singleSchoolYearStudent) {
											$this->userConfigEmail($message, $singleSchoolYearStudent, $usertypeID);
											$countusers .= $singleSchoolYearStudent->name .' ,';
										}
										$array = array(
											'usertypeID' => $usertypeID,
											'users' => $countusers,
											'type' => ucfirst($this->input->post('type')),
											'message' => $this->input->post('email_message'),
											'year' => date('Y'),
											'senderusertypeID' => $this->session->userdata('usertypeID'),
											'senderID' => $this->session->userdata('loginuserID')
										);
										$this->mailandsms_m->insert_mailandsms($array);
										redirect(base_url('mailandsms/index'));
									} else {
										$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
										redirect(base_url('mailandsms/add'));
									}
								}
							} else {
							
								$message = $this->input->post('email_message');
								$singleClass = $this->input->post('email_class');
								$singleClassStudents = $this->student_m->get_order_by_student(array('classesID' => $singleClass));
								if(count($singleClassStudents)) {
									$countusers = '';
									foreach ($singleClassStudents as $key => $singleClassStudent) {
										$this->userConfigEmail($message, $singleClassStudent, $usertypeID);
										$countusers .= $singleClassStudent->name .' ,';
									}
									$array = array(
										'usertypeID' => $usertypeID,
										'users' => $countusers,
										'type' => ucfirst($this->input->post('type')),
										'message' => $this->input->post('email_message'),
										'year' => date('Y'),
										'senderusertypeID' => $this->session->userdata('usertypeID'),
										'senderID' => $this->session->userdata('loginuserID')
									);
									$this->mailandsms_m->insert_mailandsms($array);
									redirect(base_url('mailandsms/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
									redirect(base_url('mailandsms/add'));
								}
							}
						} else {
							
							$message = $this->input->post('email_message');
							$singlestudent = $this->student_m->get_student($studentID);
							if(count($singlestudent)) {
								$this->userConfigEmail($message, $singlestudent, $usertypeID);
								$array = array(
									'usertypeID' => $usertypeID,
									'users' => $singlestudent->name,
									'type' => ucfirst($this->input->post('type')),
									'message' => $this->input->post('email_message'),
									'year' => date('Y'),
									'senderusertypeID' => $this->session->userdata('usertypeID'),
									'senderID' => $this->session->userdata('loginuserID')
								);
								$this->mailandsms_m->insert_mailandsms($array);
								redirect(base_url('mailandsms/index'));
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						}
					} elseif($usertypeID == 4) { 
						$parentsID = $this->input->post('email_users');
						if($parentsID == 'select') {
							$message = $this->input->post('email_message');
							$multiparents = $this->parents_m->get_parents();
							if(count($multiparents)) {
								$countusers = '';
								foreach ($multiparents as $key => $multiparent) {
									$this->userConfigEmail($message, $multiparent, $usertypeID);
									$countusers .= $multiparent->name .' ,';
								}
								$array = array(
									'usertypeID' => $usertypeID,
									'users' => $countusers,
									'type' => ucfirst($this->input->post('type')),
									'message' => $this->input->post('email_message'),
									'year' => date('Y'),
									'senderusertypeID' => $this->session->userdata('usertypeID'),
									'senderID' => $this->session->userdata('loginuserID')
								);
								$this->mailandsms_m->insert_mailandsms($array);
								redirect(base_url('mailandsms/index'));
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						} else {
							$message = $this->input->post('email_message');
							$singleparent = $this->parents_m->get_parents($parentsID);
							if(count($singleparent)) {
								$this->userConfigEmail($message, $singleparent, $usertypeID);
								$array = array(
									'usertypeID' => $usertypeID,
									'users' => $singleparent->name,
									'type' => ucfirst($this->input->post('type')),
									'message' => $this->input->post('email_message'),
									'year' => date('Y'),
									'senderusertypeID' => $this->session->userdata('usertypeID'),
									'senderID' => $this->session->userdata('loginuserID')
								);
								$this->mailandsms_m->insert_mailandsms($array);
								redirect(base_url('mailandsms/index'));
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						}
					} else { 
						$userID = $this->input->post('email_users');
						if($userID == 'select') {
							$message = $this->input->post('email_message');
							$multiusers = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
							if(count($multiusers)) {
								$countusers = '';
								foreach ($multiusers as $key => $multiuser) {
									$this->userConfigEmail($message, $multiuser, $usertypeID);
									$countusers .= $multiuser->name .' ,';
								}
								$array = array(
									'usertypeID' => $usertypeID,
									'users' => $countusers,
									'type' => ucfirst($this->input->post('type')),
									'message' => $this->input->post('email_message'),
									'year' => date('Y'),
									'senderusertypeID' => $this->session->userdata('usertypeID'),
									'senderID' => $this->session->userdata('loginuserID')
								);
								$this->mailandsms_m->insert_mailandsms($array);
								redirect(base_url('mailandsms/index'));
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						} else {
							$message = $this->input->post('email_message');
							$singleuser = $this->user_m->get_user($userID);
							if(count($singleuser)) {
								$this->userConfigEmail($message, $singleuser, $usertypeID);
								$array = array(
									'usertypeID' => $usertypeID,
									'users' => $singleuser->name,
									'type' => ucfirst($this->input->post('type')),
									'message' => $this->input->post('email_message'),
									'year' => date('Y'),
									'senderusertypeID' => $this->session->userdata('usertypeID'),
									'senderID' => $this->session->userdata('loginuserID')
								);
								$this->mailandsms_m->insert_mailandsms($array);
								redirect(base_url('mailandsms/index'));
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						}
					}
				}
			} elseif($this->input->post('type') == "sms") {
				$rules = $this->rules_sms();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					echo validation_errors();
					$this->data["email"] = 0;
					$this->data["sms"] = 1;
					$this->data["subview"] = "mailandsms/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$getway = $this->input->post('sms_getway');
					$usertypeID = $this->input->post('sms_usertypeID');

					if($usertypeID == 1) { 
						$systemadminID = $this->input->post('sms_users');
						if($systemadminID == 'select') {
							$countusers = '';
							$retval = 1;
							$retmess = '';

							$message = $this->input->post('sms_message');
							$multisystemadmins = $this->systemadmin_m->get_systemadmin();
							if(count($multisystemadmins)) {

								foreach ($multisystemadmins as $key => $multisystemadmin) {
									$status = $this->userConfigSMS($message, $multisystemadmin, $usertypeID, $getway);
									$countusers .= $multisystemadmin->name .' ,';

									if($status['check'] == FALSE) {
										$retval = 0;
										$retmess = $status['message'];
										break;
									}

								}
								if($retval == 1) {
									$array = array(
										'usertypeID' => $usertypeID,
										'users' => $countusers,
										'type' => ucfirst($this->input->post('type')),
										'message' => $this->input->post('sms_message'),
										'year' => date('Y'),
										'senderusertypeID' => $this->session->userdata('usertypeID'),
										'senderID' => $this->session->userdata('loginuserID')
									);
									$this->mailandsms_m->insert_mailandsms($array);
									redirect(base_url('mailandsms/index'));
								} else {
									$this->session->set_flashdata('error', $retmess);
									redirect(base_url("mailandsms/add"));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						} else {
							$retval = 1;
							$retmess = '';
							$message = $this->input->post('sms_message');
							$singlesystemadmin = $this->systemadmin_m->get_systemadmin($systemadminID);
							if(count($singlesystemadmin)) {
								$status = $this->userConfigSMS($message, $singlesystemadmin, $usertypeID, $getway);
								if($status['check'] == FALSE) {
									$retval = 0;
									$retmess = $status['message'];
								}

								if($retval == 1) {
									$array = array(
										'usertypeID' => $usertypeID,
										'users' => $singlesystemadmin->name,
										'type' => ucfirst($this->input->post('type')),
										'message' => $this->input->post('sms_message'),
										'year' => date('Y'),
										'senderusertypeID' => $this->session->userdata('usertypeID'),
										'senderID' => $this->session->userdata('loginuserID')
									);
									$this->mailandsms_m->insert_mailandsms($array);
									redirect(base_url('mailandsms/index'));
								} else {
									$this->session->set_flashdata('error', $retmess);
									redirect(base_url("mailandsms/add"));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						}
					} elseif($usertypeID == 2) { 
						$teacherID = $this->input->post('sms_users');
						if($teacherID == 'select') {
							$message = $this->input->post('sms_message');
							$multiteachers = $this->teacher_m->get_teacher();
							if(count($multiteachers)) {
								$countusers = '';
								$retval = 1;
								$retmess = '';
								foreach ($multiteachers as $key => $multiteacher) {
									$status = $this->userConfigSMS($message, $multiteacher, $usertypeID, $getway);
									$countusers .= $multiteacher->name .' ,';

									if($status['check'] == FALSE) {
										$retval = 0;
										$retmess = $status['message'];
										break;
									}

								}
								if($retval == 1) {
									$array = array(
										'usertypeID' => $usertypeID,
										'users' => $countusers,
										'type' => ucfirst($this->input->post('type')),
										'message' => $this->input->post('sms_message'),
										'year' => date('Y'),
										'senderusertypeID' => $this->session->userdata('usertypeID'),
										'senderID' => $this->session->userdata('loginuserID')
									);
									$this->mailandsms_m->insert_mailandsms($array);
									redirect(base_url('mailandsms/index'));
								} else {
									$this->session->set_flashdata('error', $retmess);
									redirect(base_url("mailandsms/add"));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						} else {
							$retval = 1;
							$retmess = '';
							$message = $this->input->post('sms_message');
							$singleteacher = $this->teacher_m->get_teacher($teacherID);
							if(count($singleteacher)) {
								$status = $this->userConfigSMS($message, $singleteacher, $usertypeID, $getway);
								if($status['check'] == FALSE) {
									$retval = 0;
									$retmess = $status['message'];
								}

								if($retval == 1) {
									$array = array(
										'usertypeID' => $usertypeID,
										'users' => $singleteacher->name,
										'type' => ucfirst($this->input->post('type')),
										'message' => $this->input->post('sms_message'),
										'year' => date('Y'),
										'senderusertypeID' => $this->session->userdata('usertypeID'),
										'senderID' => $this->session->userdata('loginuserID')
									);
									$this->mailandsms_m->insert_mailandsms($array);
									redirect(base_url('mailandsms/index'));
								} else {
									$this->session->set_flashdata('error', $retmess);
									redirect(base_url("mailandsms/add"));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						}
					} elseif($usertypeID == 3) { 

						$studentID = $this->input->post('sms_users');
						if($studentID == 'select') {
							$class = $this->input->post('sms_class');
							if($class == 'select') {
						
								$countusers = '';
								$retval = 1;
								$retmess = '';

								$schoolyear = $this->input->post('sms_schoolyear');
								if($schoolyear == 'select') {
									$message = $this->input->post('sms_message');
									$multiSchoolYearStudents = $this->student_m->get_student();
									if(count($multiSchoolYearStudents)) {
										foreach ($multiSchoolYearStudents as $key => $multiSchoolYearStudent) {
											$status = $this->userConfigSMS($message, $multiSchoolYearStudent, $usertypeID, $getway);
											$countusers .= $multiSchoolYearStudent->name .' ,';
											if($status['check'] == FALSE) {
												$retval = 0;
												$retmess = $status['message'];
												break;
											}
										}

										if($retval == 1) {
											$array = array(
												'usertypeID' => $usertypeID,
												'users' => $countusers,
												'type' => ucfirst($this->input->post('type')),
												'message' => $this->input->post('sms_message'),
												'year' => date('Y'),
												'senderusertypeID' => $this->session->userdata('usertypeID'),
												'senderID' => $this->session->userdata('loginuserID')
											);
											$this->mailandsms_m->insert_mailandsms($array);
											redirect(base_url('mailandsms/index'));
										} else {
											$this->session->set_flashdata('error', $retmess);
											redirect(base_url('mailandsms/add'));
										}
									} else {
										$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
										redirect(base_url('mailandsms/add'));
									}
								} else {
									
									$countusers = '';
									$retval = 1;
									$retmess = '';
									$message = $this->input->post('sms_message');
									$singleSchoolYear = $this->input->post('sms_schoolyear');
									$singleSchoolYearStudents = $this->student_m->get_order_by_student(array('schoolyearID' => $singleSchoolYear));
									if(count($singleSchoolYearStudents)) {

										foreach ($singleSchoolYearStudents as $key => $singleSchoolYearStudent) {
											$status = $this->userConfigSMS($message, $singleSchoolYearStudent, $usertypeID, $getway);
											$countusers .= $singleSchoolYearStudent->name .' ,';
											if($status['check'] == FALSE) {
												$retval = 0;
												$retmess = $status['message'];
												break;
											}


										}
										if($retval == 1) {
											$array = array(
												'usertypeID' => $usertypeID,
												'users' => $countusers,
												'type' => ucfirst($this->input->post('type')),
												'message' => $this->input->post('sms_message'),
												'year' => date('Y'),
												'senderusertypeID' => $this->session->userdata('usertypeID'),
												'senderID' => $this->session->userdata('loginuserID')
											);
											$this->mailandsms_m->insert_mailandsms($array);
											redirect(base_url('mailandsms/index'));
										} else {
											$this->session->set_flashdata('error', $retmess);
											redirect(base_url("mailandsms/add"));
										}
									} else {
										$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
										redirect(base_url('mailandsms/add'));
									}
								}
							} else {
								
								$countusers = '';
								$retval = 1;
								$retmess = '';

								$message = $this->input->post('sms_message');
								$singleClass = $this->input->post('sms_class');
								$singleClassStudents = $this->student_m->get_order_by_student(array('classesID' => $singleClass));
								if(count($singleClassStudents)) {
									$countusers = '';
									foreach ($singleClassStudents as $key => $singleClassStudent) {
										$status = $this->userConfigSMS($message, $singleClassStudent, $usertypeID, $getway);
										$countusers .= $singleClassStudent->name .' ,';
										if($status['check'] == FALSE) {
											$retval = 0;
											$retmess = $status['message'];
											break;
										}
									}

									if($retval == 1) {
										$array = array(
											'usertypeID' => $usertypeID,
											'users' => $countusers,
											'type' => ucfirst($this->input->post('type')),
											'message' => $this->input->post('sms_message'),
											'year' => date('Y'),
											'senderusertypeID' => $this->session->userdata('usertypeID'),
											'senderID' => $this->session->userdata('loginuserID')
										);
										$this->mailandsms_m->insert_mailandsms($array);
										redirect(base_url('mailandsms/index'));
									} else {
										$this->session->set_flashdata('error', $retmess);
										redirect(base_url("mailandsms/add"));
									}
								} else {
									$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
									redirect(base_url('mailandsms/add'));
								}
							}
						} else {
							
							$retval = 1;
							$retmess = '';

							$message = $this->input->post('sms_message');
							$singlestudent = $this->student_m->get_student($studentID);
							if(count($singlestudent)) {
								$status = $this->userConfigSMS($message, $singlestudent, $usertypeID, $getway);
								if($status['check'] == FALSE) {
									$retval = 0;
									$retmess = $status['message'];
								}
								if($retval == 1) {
									$array = array(
										'usertypeID' => $usertypeID,
										'users' =>  $singlestudent->name,
										'type' => ucfirst($this->input->post('type')),
										'message' => $this->input->post('sms_message'),
										'year' => date('Y'),
										'senderusertypeID' => $this->session->userdata('usertypeID'),
										'senderID' => $this->session->userdata('loginuserID')
									);
									$this->mailandsms_m->insert_mailandsms($array);
									redirect(base_url('mailandsms/index'));
								} else {
									$this->session->set_flashdata('error', $retmess);
									redirect(base_url("mailandsms/add"));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}

						}
					} elseif($usertypeID == 4) { 
						$parentsID = $this->input->post('sms_users');
						if($parentsID == 'select') {
							$countusers = '';
							$retval = 1;
							$retmess = '';

							$message = $this->input->post('sms_message');
							$multiparents = $this->parents_m->get_parents();
							if(count($multiparents)) {

								foreach ($multiparents as $key => $multiparent) {
									$status = $this->userConfigSMS($message, $multiparent, $usertypeID, $getway);
									$countusers .= $multiparent->name .' ,';

									if($status['check'] == FALSE) {
										$retval = 0;
										$retmess = $status['message'];
										break;
									}
								}

								if($retval == 1) {
									$array = array(
										'usertypeID' => $usertypeID,
										'users' => $countusers,
										'type' => ucfirst($this->input->post('type')),
										'message' => $this->input->post('sms_message'),
										'year' => date('Y'),
										'senderusertypeID' => $this->session->userdata('usertypeID'),
										'senderID' => $this->session->userdata('loginuserID')
									);
									$this->mailandsms_m->insert_mailandsms($array);
									redirect(base_url('mailandsms/index'));
								} else {
									$this->session->set_flashdata('error', $retmess);
									redirect(base_url("mailandsms/add"));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						} else {
							$retval = 1;
							$retmess = '';

							$message = $this->input->post('sms_message');
							$singleparent = $this->parents_m->get_parents($parentsID);
							if(count($singleparent)) {
								$status = $this->userConfigSMS($message, $singleparent, $usertypeID, $getway);
								if($status['check'] == FALSE) {
									$retval = 0;
									$retmess = $status['message'];

								}

								if($retval == 1) {
									$array = array(
										'usertypeID' => $usertypeID,
										'users' => $singleparent->name,
										'type' => ucfirst($this->input->post('type')),
										'message' => $this->input->post('sms_message'),
										'year' => date('Y'),
										'senderusertypeID' => $this->session->userdata('usertypeID'),
										'senderID' => $this->session->userdata('loginuserID')
									);
									$this->mailandsms_m->insert_mailandsms($array);
									redirect(base_url('mailandsms/index'));
								} else {
									$this->session->set_flashdata('error', $retmess);
									redirect(base_url("mailandsms/add"));
								}

							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						}
					} else { 
						$userID = $this->input->post('sms_users');
						if($userID == 'select') {
							$countusers = '';
							$retval = 1;
							$retmess = '';
							$message = $this->input->post('sms_message');
							$multiusers = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
							if(count($multiusers)) {
								foreach ($multiusers as $key => $multiuser) {
									$status = $this->userConfigSMS($message, $multiuser, $usertypeID, $getway);
									$countusers .= $multiuser->name .' ,';

									if($status['check'] == FALSE) {
										$retval = 0;
										$retmess = $status['message'];
										break;
									}
								}

								if($retval == 1) {
									$array = array(
										'usertypeID' => $usertypeID,
										'users' => $countusers,
										'type' => ucfirst($this->input->post('type')),
										'message' => $this->input->post('sms_message'),
										'year' => date('Y'),
										'senderusertypeID' => $this->session->userdata('usertypeID'),
										'senderID' => $this->session->userdata('loginuserID')
									);
									$this->mailandsms_m->insert_mailandsms($array);
									redirect(base_url('mailandsms/index'));
								} else {
									$this->session->set_flashdata('error', $retmess);
									redirect(base_url("mailandsms/add"));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						} else {
							$retval = 1;
							$retmess = '';
							$message = $this->input->post('sms_message');
							$singleuser = $this->user_m->get_user($userID);
							if(count($singleuser)) {
								$status = $this->userConfigSMS($message, $singleuser, $usertypeID, $getway);
								if($status['check'] == FALSE) {
									$retval = 0;
									$retmess = $status['message'];
								}

								if($retval == 1) {
									$array = array(
										'usertypeID' => $usertypeID,
										'users' => $singleuser->name,
										'type' => ucfirst($this->input->post('type')),
										'message' => $this->input->post('sms_message'),
										'year' => date('Y'),
										'senderusertypeID' => $this->session->userdata('usertypeID'),
										'senderID' => $this->session->userdata('loginuserID')
									);
									$this->mailandsms_m->insert_mailandsms($array);
									redirect(base_url('mailandsms/index'));
								} else {
									$this->session->set_flashdata('error', $retmess);
									redirect(base_url("mailandsms/add"));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line('mailandsms_notfound_error'));
								redirect(base_url('mailandsms/add'));
							}
						}
					}
				}
			}
		} else {
			$this->data["email"] = 1;
			$this->data["sms"] = 0;
			$this->data["subview"] = "mailandsms/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function userConfigEmail($message, $user, $usertypeID) {
		if($user && $usertypeID) {
			$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => $usertypeID));

			if($usertypeID == 2) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 2));
			} elseif($usertypeID == 3) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 3));
			} elseif($usertypeID == 4) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 4));
			} else {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 1));
			}

			if(count($userTags)) {
				foreach ($userTags as $key => $userTag) {
					if($userTag->tagname == '[name]') {
						if($user->name) {
							$message = str_replace('[name]', $user->name, $message);
						} else {
							$message = str_replace('[name]', ' ', $message);
						}
					} elseif($userTag->tagname == '[designation]') {
						if($user->designation) {
							$message = str_replace('[designation]', $user->designation, $message);
						} else {
							$message = str_replace('[designation]', ' ', $message);
						}
					} elseif($userTag->tagname == '[dob]') {
						if($user->dob) {
							$dob =  date("d M Y", strtotime($user->dob));
							$message = str_replace('[dob]', $dob, $message);
						} else {
							$message = str_replace('[dob]', ' ', $message);
						}
					} elseif($userTag->tagname == '[gender]') {
						if($user->sex) {
							$message = str_replace('[gender]', $user->sex, $message);
						} else {
							$message = str_replace('[gender]', ' ', $message);
						}
					} elseif($userTag->tagname == '[religion]') {
						if($user->religion) {
							$message = str_replace('[religion]', $user->religion, $message);
						} else {
							$message = str_replace('[religion]', ' ', $message);
						}
					} elseif($userTag->tagname == '[email]') {
						if($user->email) {
							$message = str_replace('[email]', $user->email, $message);
						} else {
							$message = str_replace('[email]', ' ', $message);
						}
					} elseif($userTag->tagname == '[phone]') {
						if($user->phone) {
							$message = str_replace('[phone]', $user->phone, $message);
						} else {
							$message = str_replace('[phone]', ' ', $message);
						}
					} elseif($userTag->tagname == '[address]') {
						if($user->address) {
							$message = str_replace('[address]', $user->address, $message);
						} else {
							$message = str_replace('[address]', ' ', $message);
						}
					} elseif($userTag->tagname == '[jod]') {
						if($user->jod) {
							$jod =  date("d M Y", strtotime($user->jod));
							$message = str_replace('[jod]', $jod, $message);
						} else {
							$message = str_replace('[jod]', ' ', $message);
						}
					} elseif($userTag->tagname == '[username]') {
						if($user->username) {
							$message = str_replace('[username]', $user->username, $message);
						} else {
							$message = str_replace('[username]', ' ', $message);
						}
					} elseif($userTag->tagname == "[father's_name]") {
						if($user->father_name) {
							$message = str_replace("[father's_name]", $user->father_name, $message);
						} else {
							$message = str_replace("[father's_name]", ' ', $message);
						}
					} elseif($userTag->tagname == "[mother's_name]") {
						if($user->mother_name) {
							$message = str_replace("[mother's_name]", $user->mother_name, $message);
						} else {
							$message = str_replace("[mother's_name]", ' ', $message);
						}
					} elseif($userTag->tagname == "[father's_profession]") {
						if($user->father_profession) {
							$message = str_replace("[father's_profession]", $user->father_profession, $message);
						} else {
							$message = str_replace("[father's_profession]", ' ', $message);
						}
					} elseif($userTag->tagname == "[mother's_profession]") {
						if($user->mother_profession) {
							$message = str_replace("[mother's_profession]", $user->mother_profession, $message);
						} else {
							$message = str_replace("[mother's_profession]", ' ', $message);
						}
					} elseif($userTag->tagname == '[class/department]') {
						$classes = $this->classes_m->get_classes($user->classesID);
						if(count($classes)) {
							$message = str_replace('[class/department]', $classes->classes, $message);
						} else {
							$message = str_replace('[class/department]', ' ', $message);
						}
					} elseif($userTag->tagname == '[roll]') {
						if($user->roll) {
							$message = str_replace("[roll]", $user->roll, $message);
						} else {
							$message = str_replace("[roll]", ' ', $message);
						}
					} elseif($userTag->tagname == '[country]') {
						if($user->country) {
							$message = str_replace("[country]", $this->data['allcountry'][$user->country], $message);
						} else {
							$message = str_replace("[country]", ' ', $message);
						}
					} elseif($userTag->tagname == '[state]') {
						if($user->state) {
							$message = str_replace("[state]", $user->state, $message);
						} else {
							$message = str_replace("[state]", ' ', $message);
						}
					} elseif($userTag->tagname == '[register_no]') {
						if($user->registerNO) {
							$message = str_replace("[register_no]", $user->registerNO, $message);
						} else {
							$message = str_replace("[register_no]", ' ', $message);
						}
					} elseif($userTag->tagname == '[section]') {
						if($user->sectionID) {
							$section = $this->section_m->get_section($user->sectionID);
							if(count($section)) {
								$message = str_replace('[section]', $section->section, $message);
							} else {
								$message = str_replace('[section]',' ', $message);
							}
						} else {
							$message = str_replace("[section]", ' ', $message);
						}
					}
				}
			}

			if($user->email) {
				$subject = $this->input->post('email_subject');
				$email = $user->email;
				$this->email->set_mailtype("html");
				$this->email->from($this->data['siteinfos']->email, $this->data['siteinfos']->sname);
				$this->email->to($email);
				$this->email->subject($subject);
				$this->email->message($message);

				if($this->email->send()) {
					$this->session->set_flashdata('success', $this->lang->line('mail_success'));
				} else {
					$this->session->set_flashdata('error', $this->lang->line('mail_error'));
				}
			}
		}
	}

	function userConfigSMS($message, $user, $usertypeID, $getway) {
		if($user && $usertypeID) {
			$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => $usertypeID));

			if($usertypeID == 2) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 2));
			} elseif($usertypeID == 3) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 3));
			} elseif($usertypeID == 4) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 4));
			} else {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 1));
			}

			if(count($userTags)) {
				foreach ($userTags as $key => $userTag) {
					if($userTag->tagname == '[name]') {
						if($user->name) {
							$message = str_replace('[name]', $user->name, $message);
						} else {
							$message = str_replace('[name]', ' ', $message);
						}
					} elseif($userTag->tagname == '[designation]') {
						if($user->designation) {
							$message = str_replace('[designation]', $user->designation, $message);
						} else {
							$message = str_replace('[designation]', ' ', $message);
						}
					} elseif($userTag->tagname == '[dob]') {
						if($user->dob) {
							$dob =  date("d M Y", strtotime($user->dob));
							$message = str_replace('[dob]', $dob, $message);
						} else {
							$message = str_replace('[dob]', ' ', $message);
						}
					} elseif($userTag->tagname == '[gender]') {
						if($user->sex) {
							$message = str_replace('[gender]', $user->sex, $message);
						} else {
							$message = str_replace('[gender]', ' ', $message);
						}
					} elseif($userTag->tagname == '[religion]') {
						if($user->religion) {
							$message = str_replace('[religion]', $user->religion, $message);
						} else {
							$message = str_replace('[religion]', ' ', $message);
						}
					} elseif($userTag->tagname == '[email]') {
						if($user->email) {
							$message = str_replace('[email]', $user->email, $message);
						} else {
							$message = str_replace('[email]', ' ', $message);
						}
					} elseif($userTag->tagname == '[phone]') {
						if($user->phone) {
							$message = str_replace('[phone]', $user->phone, $message);
						} else {
							$message = str_replace('[phone]', ' ', $message);
						}
					} elseif($userTag->tagname == '[address]') {
						if($user->address) {
							$message = str_replace('[address]', $user->address, $message);
						} else {
							$message = str_replace('[address]', ' ', $message);
						}
					} elseif($userTag->tagname == '[jod]') {
						if($user->jod) {
							$jod =  date("d M Y", strtotime($user->jod));
							$message = str_replace('[jod]', $jod, $message);
						} else {
							$message = str_replace('[jod]', ' ', $message);
						}
					} elseif($userTag->tagname == '[username]') {
						if($user->username) {
							$message = str_replace('[username]', $user->username, $message);
						} else {
							$message = str_replace('[username]', ' ', $message);
						}
					} elseif($userTag->tagname == "[father's_name]") {
						if($user->father_name) {
							$message = str_replace("[father's_name]", $user->father_name, $message);
						} else {
							$message = str_replace("[father's_name]", ' ', $message);
						}
					} elseif($userTag->tagname == "[mother's_name]") {
						if($user->mother_name) {
							$message = str_replace("[mother's_name]", $user->mother_name, $message);
						} else {
							$message = str_replace("[mother's_name]", ' ', $message);
						}
					} elseif($userTag->tagname == "[father's_profession]") {
						if($user->father_profession) {
							$message = str_replace("[father's_profession]", $user->father_profession, $message);
						} else {
							$message = str_replace("[father's_profession]", ' ', $message);
						}
					} elseif($userTag->tagname == "[mother's_profession]") {
						if($user->mother_profession) {
							$message = str_replace("[mother's_profession]", $user->mother_profession, $message);
						} else {
							$message = str_replace("[mother's_profession]", ' ', $message);
						}
					} elseif($userTag->tagname == '[class/department]') {
						$classes = $this->classes_m->get_classes($user->classesID);
						if(count($classes)) {
							$message = str_replace('[class/department]', $classes->classes, $message);
						} else {
							$message = str_replace('[class/department]', ' ', $message);
						}
					} elseif($userTag->tagname == '[roll]') {
						if($user->roll) {
							$message = str_replace("[roll]", $user->roll, $message);
						} else {
							$message = str_replace("[roll]", ' ', $message);
						}
					} elseif($userTag->tagname == '[country]') {
						if($user->country) {
							$message = str_replace("[country]", $this->data['allcountry'][$user->country], $message);
						} else {
							$message = str_replace("[country]", ' ', $message);
						}
					} elseif($userTag->tagname == '[state]') {
						if($user->state) {
							$message = str_replace("[state]", $user->state, $message);
						} else {
							$message = str_replace("[state]", ' ', $message);
						}
					} elseif($userTag->tagname == '[register_no]') {
						if($user->registerNO) {
							$message = str_replace("[register_no]", $user->registerNO, $message);
						} else {
							$message = str_replace("[register_no]", ' ', $message);
						}
					} elseif($userTag->tagname == '[section]') {
						if($user->sectionID) {
							$section = $this->section_m->get_section($user->sectionID);
							if(count($section)) {
								$message = str_replace('[section]', $section->section, $message);
							} else {
								$message = str_replace('[section]',' ', $message);
							}
						} else {
							$message = str_replace("[section]", ' ', $message);
						}
					}



				}
			}

			if($user->phone) {
				$send = $this->allgetway_send_message($getway, $user->phone, $message);
				return $send;
			} else {
				$send = array('check' => TRUE);
				return $send;
			}
		}
	}

	function alltemplate() {
		if($this->input->post('usertypeID') == 'select') {
			echo '<option value="select">'.$this->lang->line('mailandsms_select_template').'</option>';
		} else {
			$usertypeID = $this->input->post('usertypeID');
			$type = $this->input->post('type');

			$templates = $this->mailandsmstemplate_m->get_order_by_mailandsmstemplate(array('usertypeID' => $usertypeID, 'type' => $type));
			echo '<option value="select">'.$this->lang->line('mailandsms_select_template').'</option>';
			if(count($templates)) {
				foreach ($templates as $key => $template) {
					echo '<option value="'.$template->mailandsmstemplateID.'">'. $template->name  .'</option>';
				}
			}
		}
	}

	function allusers() {
		if($this->input->post('usertypeID') == 'select') {
			echo '<option value="select">'.$this->lang->line('mailandsms_select_users').'</option>';
		} else {
			$usertypeID = $this->input->post('usertypeID');

			if($usertypeID == 1) {
				$systemadmins = $this->systemadmin_m->get_systemadmin();
				if(count($systemadmins)) {
					echo "<option value='select'>".$this->lang->line('mailandsms_select_users')."</option>";
					foreach ($systemadmins as $key => $systemadmin) {
						echo "<option value='".$systemadmin->systemadminID."'>".$systemadmin->name.'</option>';
					}
				} else {
					echo '<option value="select">'.$this->lang->line('mailandsms_select_users').'</option>';
				}
			} elseif($usertypeID == 2) {
				$teachers = $this->teacher_m->get_teacher();
				if(count($teachers)) {
					echo "<option value='select'>".$this->lang->line('mailandsms_select_users')."</option>";
					foreach ($teachers as $key => $teacher) {
						echo "<option value='".$teacher->teacherID."'>".$teacher->name.'</option>';
					}
				} else {
					echo '<option value="select">'.$this->lang->line('mailandsms_select_users').'</option>';
				}
			} elseif($usertypeID == 3) {
				$classes = $this->classes_m->get_classes();
				if(count($classes)) {
					echo "<option value='select'>".$this->lang->line('mailandsms_select_class')."</option>";
					foreach ($classes as $key => $classm) {
						echo "<option value='".$classm->classesID."'>".$classm->classes.'</option>';
					}
				} else {
					echo '<option value="select">'.$this->lang->line('mailandsms_select_class').'</option>';
				}
			} elseif($usertypeID == 4) {
				$parents = $this->parents_m->get_parents();
				if(count($parents)) {
					echo "<option value='select'>".$this->lang->line('mailandsms_select_users')."</option>";
					foreach ($parents as $key => $parent) {
						echo "<option value='".$parent->parentsID."'>".$parent->name.'</option>';
					}
				} else {
					echo '<option value="select">'.$this->lang->line('mailandsms_select_users').'</option>';
				}
			} else {
				$users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
				if(count($users)) {
					echo "<option value='select'>".$this->lang->line('mailandsms_select_users')."</option>";
					foreach ($users as $key => $user) {
						echo "<option value='".$user->userID."'>".$user->name.'</option>';
					}
				} else {
					echo '<option value="select">'.$this->lang->line('mailandsms_select_users').'</option>';
				}
			}
		}
	}

	function allstudent() {
		$schoolyearID = $this->input->post('schoolyear');
		$classesID = $this->input->post('classes');
		if((int)$schoolyearID && (int)$classesID) {
			$students = $this->student_m->get_order_by_student(array('schoolyearID' => $schoolyearID, 'classesID' => $classesID));

			if(count($students)) {
				echo '<option value="select">'.$this->lang->line('mailandsms_select_users').'</option>';
				foreach ($students as $key => $student) {
					echo '<option value="'.$student->studentID.'">'.$student->name.'</option>';
				}
			} else {
				echo '<option value="select">'.$this->lang->line('mailandsms_select_users').'</option>';
			}
		} else {
			echo '<option value="select">'.$this->lang->line('mailandsms_select_users').'</option>';
		}
	}

	function check_email_usertypeID() {
		if($this->input->post('email_usertypeID') == 'select') {
			$this->form_validation->set_message("check_email_usertypeID", "The %s field is required");
	     	return FALSE;
		} else {
			return TRUE;
		}
	}

	function alltemplatedesign() {
		if((int)$this->input->post('templateID')) {
			$templateID = $this->input->post('templateID');
			$templates = $this->mailandsmstemplate_m->get_mailandsmstemplate($templateID);
			if(count($templates)) {
				echo $templates->template;
			}
		} else {
			echo '';
		}
	}

	
	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->mailandsms_m->delete_mailandsms($id);
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			redirect(base_url("mailandsms/index"));
		} else {
			redirect(base_url("mailandsms/index"));
		}
	}

	public function view() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['mailandsms'] = $this->mailandsms_m->get_mailandsms($id);
			if($this->data['mailandsms']) {
				$this->data["subview"] = "mailandsms/view";
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

/* End of file student.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/student.php */
