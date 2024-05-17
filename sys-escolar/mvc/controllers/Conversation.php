<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conversation extends Admin_Controller {

	function __construct() {
		parent::__construct();	
		$this->load->model('usertype_m');	
		$this->load->model('classes_m');
		$this->load->model('student_m');
		$this->load->model('student_info_m');
		$this->load->model('user_m');
		$this->load->model('teacher_m');
		$this->load->model('parents_m');
		$this->load->model('systemadmin_m');
		$this->load->model('conversation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('conversation', $language);
		if(permissionChecker('conversation') == FALSE) {
			redirect('conversation/index');
		}
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'userGroup',
				'label' => $this->lang->line("select_group"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("message"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'attachment',
				'label' => $this->lang->line("attachment"),
				'rules' => 'trim|xss_clean'
			)
		);
		return $rules;
	}

	public function index() {
		$usertypeID = $this->session->userdata("usertypeID");
		$username = $this->session->userdata("username");
		$userID = $this->session->userdata("loginuserID");
		$this->data['conversations'] = $this->conversation_m->get_my_conversations(); 
		$this->data['methodpass'] = $this->uri->segment(htmlentities(escapeString(2)));
		if ($this->data['conversations']) {
			foreach ($this->data['conversations'] as $key => $item) {
				if ($item['usertypeID']== 1) {
					$table = "systemadmin";
				} elseif($item['usertypeID']== 2) {
					$table = "teacher";
				} elseif($item['usertypeID']== 3) {
					$table = 'student';
				} elseif($item['usertypeID']== 4) {
					$table = 'parents';
				} else {
					$table = 'user';
				}

				$query = $this->db->get_where($table, array($table.'ID' => $item['user_id']));
				if (count($query->row())) {
					$this->data['conversations'][$key] = (object) array_merge( (array)$item, array( 'sender' => $query->row()->name));
				}
			}
			foreach ($this->data['conversations'] as $key => $value) {
				$get_messages = $this->conversation_m->get_conversation_msg_by_id($value->conversation_id);
				$msgCounter = count($get_messages);
				$this->data['conversations'][$key] = (object) array_merge( (array)$value, array( 'msgCount' => $msgCounter));
			}
		}
		if($usertypeID) {
			$this->data["subview"] = "conversation/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function draft() {
		$usertype = $this->session->userdata("usertype");
		$username = $this->session->userdata("username");
		$userID = $this->session->userdata("loginuserID");
		$this->data['conversations'] = $this->conversation_m->get_my_conversations_draft(); 
		if ($this->data['conversations']) {
			foreach ($this->data['conversations'] as $key => $item) {
				if ($item['usertypeID'] == 1) {
					$table = "systemadmin";
				} elseif($item['usertypeID'] == 2) {
					$table = "teacher";
				} elseif($item['usertypeID'] == 3) {
					$table = 'student';
				} elseif($item['usertypeID'] == 4) {
					$table = 'parents';
				} else {
					$table = 'user';
				}

				$query = $this->db->get_where($table, array($table.'ID' => $item['user_id']));
				if (count($query->row())) {
					$this->data['conversations'][$key] = (object) array_merge( (array)$item, array( 'sender' => $query->row()->name));
				}
			}
			foreach ($this->data['conversations'] as $key => $value) {
				$get_messages = $this->conversation_m->get_conversation_msg_by_id($value->conversation_id);
				$msgCounter = count($get_messages);
				$this->data['conversations'][$key] = (object) array_merge( (array)$value, array( 'msgCount' => $msgCounter));
			}
		}
		if($usertype) {
			$this->data["subview"] = "conversation/draft";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function sent() {
		$usertype = $this->session->userdata("usertype");
		$username = $this->session->userdata("username");
		$userID = $this->session->userdata("loginuserID");
		$this->data['conversations'] = $this->conversation_m->get_my_conversations_sent(); 
		$this->data['methodpass'] = $this->uri->segment(htmlentities(escapeString(2)));
		if ($this->data['conversations']) {
			foreach ($this->data['conversations'] as $key => $item) {
				if ($item['usertypeID'] == 1) {
					$table = "systemadmin";
				} elseif($item['usertypeID'] == 2) {
					$table = "teacher";
				} elseif($item['usertypeID'] == 3) {
					$table = 'student';
				} elseif($item['usertypeID'] == 4) {
					$table = 'parents';
				} else {
					$table = 'user';
				}
				$query = $this->db->get_where($table, array($table.'ID' => $item['user_id']));
				if(count($query->row())) {
					$this->data['conversations'][$key] = (object) array_merge( (array)$item, array( 'sender' => $query->row()->name));
				}
			}
			foreach ($this->data['conversations'] as $key => $value) {
				$get_messages = $this->conversation_m->get_conversation_msg_by_id($value->conversation_id);
				$msgCounter = count($get_messages);
				$this->data['conversations'][$key] = (object) array_merge( (array)$value, array( 'msgCount' => $msgCounter));
			}
		}
		if($usertype) {
			$this->data["subview"] = "conversation/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function trash_msg() {
		$this->data['methodpass'] = $this->uri->segment(htmlentities(escapeString(2)));
		$usertype = $this->session->userdata("usertype");
		$username = $this->session->userdata("username");
		$userID = $this->session->userdata("loginuserID");
		$this->data['conversations'] = $this->conversation_m->get_my_conversations_trash(); 
		if ($this->data['conversations']) {
			foreach ($this->data['conversations'] as $key => $item) {
				if ($item['usertypeID'] == 1) {
					$table = "systemadmin";
				} elseif($item['usertypeID'] == 2) {
					$table = "teacher";
				} elseif($item['usertypeID'] == 3) {
					$table = 'student';
				} elseif($item['usertypeID'] == 4) {
					$table = 'parents';
				} else {
					$table = 'user';
				}

				$query = $this->db->get_where($table, array($table.'ID' => $item['user_id']));
				if (count($query->row())) {
					$this->data['conversations'][$key] = (object) array_merge( (array)$item, array( 'sender' => $query->row()->name));
				}
			}
			foreach ($this->data['conversations'] as $key => $value) {
				$get_messages = $this->conversation_m->get_conversation_msg_by_id($value->conversation_id);
				$msgCounter = count($get_messages);
				$this->data['conversations'][$key] = (object) array_merge( (array)$value, array( 'msgCount' => $msgCounter));
			}
		}
		if($usertype) {
			$this->data["subview"] = "conversation/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function draft_send($id) {
		if ((int)$id) {
			$data = array();
			$conversation = $this->conversation_m->get_conversation($id);
			if ($conversation->draft==1) {
				$data['draft'] = 0;
			} 
			$this->conversation_m->update_conversation($data, $id);
			$this->session->set_flashdata('success', $this->lang->line("menu_success"));
			redirect(base_url("conversation/index"));
		}
	}

	public function create() {
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
		$username = $this->session->userdata("username");
		$userID = $this->session->userdata("loginuserID");
		$year = date("Y");
		$this->data['usertypes'] = $this->conversation_m->get_usertype_by_permission();

		$this->data['classes'] = $this->conversation_m->get_recivers('classes');
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->data["subview"] = "conversation/add_group";
				$this->load->view('_layout_main', $this->data);
			} else {
				$conversation_user = array();
				$conversations = array(
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s")
				);
				if($this->input->post('submit') == "draft") { 
				    $conversations['draft'] = 1;
				}

				$conversation_msg = array(
					"user_id" => $userID,
					"usertypeID" => $usertypeID,
					"subject" => $this->input->post('subject'),
					"msg" => $this->input->post('message'),
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s"),
					"start" => 1
				);

				if($_FILES["attachment"]['name'] !="") {
					$file_name = $_FILES["attachment"]['name'];
					$file_name_rename = rand(1, 100000000000);
		            $explode = explode('.', $file_name);
		            if(count($explode) >= 2) {
		            	$new_file = $file_name_rename.'.'.end($explode);
		            	if (preg_match('/\s/',$file_name)) {
							$file_name = str_replace(' ', '_', $file_name);
						}
						$config['upload_path'] = "./uploads/attach";
						$config['allowed_types'] = "gif|jpg|png|pdf|docx|csv";
						$config['file_name'] = $new_file;
						$config['max_size'] = '1024';
						$config['max_width'] = '3000';
						$config['max_height'] = '3000';
						$conversation_msg['attach'] = $file_name;
						$conversation_msg['attach_file_name'] = $new_file;
						$this->load->library('upload', $config);
						if(!$this->upload->do_upload("attachment")) {

							$this->data["attachment_error"] = $this->upload->display_errors();
							$this->session->set_flashdata('error', $this->data["attachment_error"]);
							redirect(base_url('conversation/create'));
						} else {
							$data = array("upload_data" => $this->upload->data());
						}
					} else {
						$this->session->set_flashdata('error', $this->lang->line("invalid"));
						redirect(base_url('conversation/create'));
					}
				}

				if ($this->input->post('userGroup')) {
					$convID = '';
					$convID = $this->conversation_m->insert_conversation($conversations);
					$conversation_user = array(
						"conversation_id" => $convID,
						"user_id" => $userID,
						"usertypeID" => $usertypeID,
						"is_sender" => 1,
					);
					$this->conversation_m->insert_conversation_user($conversation_user);

					if ($this->input->post('userGroup')== 3) {
						if (!$this->input->post('classID')) {
							$students = $this->conversation_m->get_all_student();
							if ($students) {
								foreach ($students as $student) {
									$conversation_user = array(
										"conversation_id" => $convID,
										"user_id" => $student->studentID,
										"usertypeID" => $student->usertypeID
									);
									$userAdd = $this->conversation_m->insert_conversation_user($conversation_user);
								}
								$conversation_msg['conversation_id'] = $convID;
								$msgAdd = $this->conversation_m->insert_conversation_msg($conversation_msg);
								if ($msgAdd==true) {
									$this->session->set_flashdata('success', $this->lang->line("success_msg"));
									redirect(base_url('conversation/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line("error_msg"));
									redirect(base_url('conversation/create'));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line("error_msg_not_found"));
								redirect(base_url('conversation/create'));
							}
						} else {
							$classID = $this->input->post('classID');
							$studentID = $this->input->post('studentID');
							$students = array();
							if ($studentID) {
								$students = $this->conversation_m->get_student_by_class($studentID);
							} else {
								$students = $this->conversation_m->get_order_by_student_class($classID);
							}
							if ($students) {
								foreach ($students as $student) {
									$conversation_user = array(
										"conversation_id" => $convID,
										"user_id" => $student->studentID,
										"usertypeID" => $student->usertypeID
									);
									$userAdd = $this->conversation_m->insert_conversation_user($conversation_user);
								}
								$conversation_msg['conversation_id'] = $convID;
								$msgAdd = $this->conversation_m->insert_conversation_msg($conversation_msg);
								if ($msgAdd==true) {
									$this->session->set_flashdata('success', $this->lang->line("success_msg"));
									redirect(base_url('conversation/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line("error_msg"));
									redirect(base_url('conversation/create'));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line("error_msg_not_found"));
								redirect(base_url('conversation/create'));
							}
						}
					} elseif($this->input->post('userGroup') == 1) {
						if (!$this->input->post('systemadminID')) {
							$systemadmins = $this->systemadmin_m->get_systemadmin();
							if ($systemadmins) {
								foreach ($systemadmins as $systemadmin) {
									$conversation_user = array(
										"conversation_id" => $convID,
										"user_id" => $systemadmin->systemadminID,
										"usertypeID" => $systemadmin->usertypeID
									);
									$userAdd = $this->conversation_m->insert_conversation_user($conversation_user);
								}
								$conversation_msg['conversation_id'] = $convID;
								$msgAdd = $this->conversation_m->insert_conversation_msg($conversation_msg);
								if ($msgAdd==true) {
									$this->session->set_flashdata('success', $this->lang->line("success_msg"));
									redirect(base_url('conversation/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line("error_msg"));
									redirect(base_url('conversation/create'));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line("error_msg_not_found"));
								redirect(base_url('conversation/create'));
							}

						} else {
							$systemadminID = $this->input->post('systemadminID');
							$systemadmin = $this->systemadmin_m->get_systemadmin($systemadminID);
							if (count($systemadmin)) {
								$conversation_user = array(
									"conversation_id" => $convID,
									"user_id" => $systemadmin->systemadminID,
									"usertypeID" => $systemadmin->usertypeID
								);
								$userAdd = $this->conversation_m->insert_conversation_user($conversation_user);

								$conversation_msg['conversation_id'] = $convID;
								$msgAdd = $this->conversation_m->insert_conversation_msg($conversation_msg);
								if ($msgAdd==true) {
									$this->session->set_flashdata('success', $this->lang->line("success_msg"));
									redirect(base_url('conversation/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line("error_msg"));
									redirect(base_url('conversation/create'));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line("error_msg_not_found"));
								redirect(base_url('conversation/create'));
							}
						}
						
					} elseif ($this->input->post('userGroup') == 2) {
						if (!$this->input->post('teacherID')) {
							$teachers = $this->teacher_m->get_teacher();
							if ($teachers) {
								foreach ($teachers as $teacher) {
									$conversation_user = array(
										"conversation_id" => $convID,
										"user_id" => $teacher->teacherID,
										"usertypeID" => $teacher->usertypeID
									);
									$userAdd = $this->conversation_m->insert_conversation_user($conversation_user);
								}
								$conversation_msg['conversation_id'] = $convID;
								$msgAdd = $this->conversation_m->insert_conversation_msg($conversation_msg);
								if ($msgAdd==true) {
									$this->session->set_flashdata('success', $this->lang->line("success_msg"));
									redirect(base_url('conversation/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line("error_msg"));
									redirect(base_url('conversation/create'));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line("error_msg_not_found"));
								redirect(base_url('conversation/create'));
							}

						} else {
							$teacherID = $this->input->post('teacherID');
							$teacher = $this->teacher_m->get_teacher($teacherID);
							if (count($teacher)) {
								$conversation_user = array(
									"conversation_id" => $convID,
									"user_id" => $teacher->teacherID,
									"usertypeID" => $teacher->usertypeID
								);
								$userAdd = $this->conversation_m->insert_conversation_user($conversation_user);

								$conversation_msg['conversation_id'] = $convID;
								$msgAdd = $this->conversation_m->insert_conversation_msg($conversation_msg);
								if ($msgAdd==true) {
									$this->session->set_flashdata('success', $this->lang->line("success_msg"));
									redirect(base_url('conversation/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line("error_msg"));
									redirect(base_url('conversation/create'));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line("error_msg_not_found"));
								redirect(base_url('conversation/create'));
							}
						}
					} else if($this->input->post('userGroup') == 4) {
						if (!$this->input->post('parentsID')) {
							$parents = $this->parents_m->get_parents();
							if ($parents) {
								foreach ($parents as $parent) {
									$conversation_user = array(
										"conversation_id" => $convID,
										"user_id" => $parent->parentsID,
										"usertypeID" => $parent->usertypeID
									);
									$userAdd = $this->conversation_m->insert_conversation_user($conversation_user);
								}
								$conversation_msg['conversation_id'] = $convID;
								$msgAdd = $this->conversation_m->insert_conversation_msg($conversation_msg);
								if ($msgAdd==true) {
									$this->session->set_flashdata('success', $this->lang->line("success_msg"));
									redirect(base_url('conversation/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line("error_msg"));
									redirect(base_url('conversation/create'));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line("error_msg_not_found"));
								redirect(base_url('conversation/create'));
							}

						} else {
							$parentsID = $this->input->post('parentsID');
							$parent = $this->parents_m->get_parents($parentsID);
							if (count($parent)) {
								$conversation_user = array(
									"conversation_id" => $convID,
									"user_id" => $parent->parentsID,
									"usertypeID" => $parent->usertypeID
								);
								$userAdd = $this->conversation_m->insert_conversation_user($conversation_user);

								$conversation_msg['conversation_id'] = $convID;
								$msgAdd = $this->conversation_m->insert_conversation_msg($conversation_msg);
								if ($msgAdd==true) {
									$this->session->set_flashdata('success', $this->lang->line("success_msg"));
									redirect(base_url('conversation/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line("error_msg"));
									redirect(base_url('conversation/create'));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line("error_msg_not_found"));
								redirect(base_url('conversation/create'));
							}
						}
					} else {
						if (!$this->input->post('userID')) {
							$users = $this->user_m->get_user();
							if ($users) {
								foreach ($users as $user) {
									$conversation_user = array(
										"conversation_id" => $convID,
										"user_id" => $user->userID,
										"usertypeID" => $user->usertypeID
									);
									$userAdd = $this->conversation_m->insert_conversation_user($conversation_user);
								}
								$conversation_msg['conversation_id'] = $convID;
								$msgAdd = $this->conversation_m->insert_conversation_msg($conversation_msg);
								if ($msgAdd==true) {
									$this->session->set_flashdata('success', $this->lang->line("success_msg"));
									redirect(base_url('conversation/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line("error_msg"));
									redirect(base_url('conversation/create'));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line("error_msg_not_found"));
								redirect(base_url('conversation/create'));
							}

						} else {

							$userID = $this->input->post('userID');
							$user = $this->user_m->get_user($userID);
							if (count($user)) {
								$conversation_user = array(
									"conversation_id" => $convID,
									"user_id" => $user->userID,
									"usertypeID" => $user->usertypeID
								);
								$userAdd = $this->conversation_m->insert_conversation_user($conversation_user);

								$conversation_msg['conversation_id'] = $convID;
								$msgAdd = $this->conversation_m->insert_conversation_msg($conversation_msg);
								if ($msgAdd==true) {
									$this->session->set_flashdata('success', $this->lang->line("success_msg"));
									redirect(base_url('conversation/index'));
								} else {
									$this->session->set_flashdata('error', $this->lang->line("error_msg"));
									redirect(base_url('conversation/create'));
								}
							} else {
								$this->session->set_flashdata('error', $this->lang->line("error_msg_not_found"));
								redirect(base_url('conversation/create'));
							}
						}
					}
					
				}
			}
		} else {
			$this->data["subview"] = "conversation/add_group";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function view() {
		$usertypeID = $this->session->userdata("usertypeID");
		$username = $this->session->userdata("username");
		$userID = $this->session->userdata("loginuserID");
		$id = $this->uri->segment(3);
		if ((int)$id) {
			$check = $this->checkUserAuth($id, $userID, $usertypeID);
			if (count($check) == 1) {
				if($check->trash != 2) {
					if ($_POST) {
						if ($_POST['reply'] || $_FILES["attachment"]['name'] !="") {
							$array = array();
							$array['conversation_id'] = $id;
							$array['msg'] = $this->input->post('reply');
							$array['user_id'] = $userID;
							$array['usertypeID'] = $usertypeID;
							$array['create_date'] = date("Y-m-d h:i:s");
							$array['modify_date'] = date("Y-m-d h:i:s");
							// attachment
							if($_FILES["attachment"]['name'] !="") {
								$file_name = $_FILES["attachment"]['name'];
								$file_name_rename = rand(1, 100000000000);
					            $explode = explode('.', $file_name);
					            if(count($explode) >= 2) {
					            	$new_file = $file_name_rename.'.'.end($explode);
					            	if (preg_match('/\s/',$file_name)) {
										$file_name = str_replace(' ', '_', $file_name);
									}
									$config['upload_path'] = "./uploads/attach";
									$config['allowed_types'] = "gif|jpg|png|pdf|docx|csv";
									$config['file_name'] = $new_file;
									$config['max_size'] = '1024';
									$config['max_width'] = '3000';
									$config['max_height'] = '3000';
									$array['attach'] = $file_name;
									$array['attach_file_name'] = $new_file;
									$this->load->library('upload', $config);
									if(!$this->upload->do_upload("attachment")) {
										$this->data["attachment_error"] = $this->upload->display_errors();
										$this->data["subview"] = "conversation/view";
										$this->load->view('_layout_main', $this->data);
									} else {
										$data = array("upload_data" => $this->upload->data());
										$this->conversation_m->insert_conversation_msg($array);
										$this->session->set_flashdata('success', $this->lang->line("menu_success"));
										redirect(base_url("conversation/view/$id"));
									}
								} else {
									$this->data["attachment_error"] = "Invalid file";
									$this->data["subview"] = "conversation/view";
									$this->load->view('_layout_main', $this->data);
								}
							} else {
								$this->conversation_m->insert_conversation_msg($array);
								$this->session->set_flashdata('success', $this->lang->line("menu_success"));
								redirect(base_url("conversation/view/$id"));
							}
							// attachment condition end
						}
					} 
					$this->data['messages'] = $this->conversation_m->get_conversation_msg_by_id($id);
					$table = "";
					foreach ($this->data['messages'] as $key => $msg) {
						if ($msg->usertypeID == 1) {
							$table = "systemadmin";
						} elseif($msg->usertypeID == 2) {
							$table = "teacher";
						} elseif($msg->usertypeID == 3) {
							$table = 'student';
						} elseif($msg->usertypeID == 4) {
							$table = 'parents';
	 					} else {
							$table = 'user';
						}

						$query = $this->db->get_where($table, array($table.'ID' => $msg->user_id));
						if (count($query->row())) {
							$this->data['messages'][$key] = (object) array_merge( (array)$msg, array( 'sender' => $query->row()->name, 'photo' => $query->row()->photo));
						}
					}
					$this->data["subview"] = "conversation/view";
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
		
		$this->conversation_m->get_conversation($id);
	}

	function classCall() {
		$allsection = $this->classes_m->get_classes();
		echo "<option value='0'>", $this->lang->line("select_class"),"</option>";
		foreach ($allsection as $value) {
			echo "<option value=\"$value->classesID\">",$value->classes,"</option>";
		}
	}

	function adminCall() {
		$alladmin = $this->systemadmin_m->get_systemadmin();
		echo "<option value='0'>", $this->lang->line("select_admin"),"</option>";
		foreach ($alladmin as $value) {
			echo "<option value=\"$value->systemadminID\">",$value->name,"</option>";
		}
	}

	function teacherCall() {
		$allteacher = $this->teacher_m->get_teacher();
		echo "<option value='0'>", $this->lang->line("select_teacher"),"</option>";
		foreach ($allteacher as $value) {
			echo "<option value=\"$value->teacherID\">",$value->name,"</option>";
		}
	}

	function parentCall() {
		$allteacher = $this->parents_m->get_parents();
		echo "<option value='0'>", $this->lang->line("select_parent"),"</option>";
		foreach ($allteacher as $value) {
			echo "<option value=\"$value->parentsID\">",$value->name,"</option>";
		}
	}

	function userCall() {
		$id = $this->input->post('id');
		$allteacher = $this->user_m->get_order_by_user(array('usertypeID' => $id));
		echo "<option value='0'>", $this->lang->line("select_user"),"</option>";
		foreach ($allteacher as $value) {
			echo "<option value=\"$value->userID\">",$value->name,"</option>";
		}
	}

	function call_all_student() {
		$classesID = $this->input->post('id');
		if((int)$classesID) {
			echo "<option value='". 0 ."'>". $this->lang->line('select_student') ."</option>";
			$students = $this->student_m->get_order_by_student(array('classesID' => $classesID));
			foreach ($students as $key => $student) {
				echo "<option value='". $student->studentID ."'>". $student->name ."</option>";
			}
		} else {
			echo "<option value='". 0 ."'>". $this->lang->line('invoice_select_student') ."</option>";
		}
	}

	public function checkUserAuth($conv_id, $user_id, $usertype) {	
		$result = $this->conversation_m->user_Check($conv_id, $user_id, $usertype);
		return $result;
	}

	public function fav_status() {
		$id = $this->input->post('id');
		if ((int)$id) {
			$data = array();
			$conversation = $this->conversation_m->get_conversation($id);
			if ($conversation->fav_status==1) {
				$data['fav_status'] = 0;
			} else {
				$data['fav_status'] = 1;
			}
			$this->conversation_m->update_conversation($data, $id);
			$string = base_url("conversation/index");
			echo $string;
		}
	}

	public function delete_conversation() {
		$id = htmlentities(escapeString($this->input->post('id')));
		if($id) {
			$array = array();
			$array = explode(',', $id);
			$update_array = array();
			foreach ($array as $value) {
				$update_array['trash']  = 1;
				$trash = $this->conversation_m->trash_conversation($update_array, $value);
			}
			$this->session->set_flashdata('success', $this->lang->line("deleted"));
			// echo base_url("conversation/index");
		} else {
			$this->session->set_flashdata('error', $this->lang->line("delete_error"));
			// echo base_url("conversation/index");
		}
	}

	public function delete_trash_to_trash() {
		$id = htmlentities(escapeString($this->input->post('id')));
		if($id) {
			$array = array();
			$array = explode(',', $id);
			$update_array = array();
			foreach ($array as $value) {
				$update_array['trash']  = 2;
				$trash = $this->conversation_m->trash_conversation($update_array, $value);
			}
			$this->session->set_flashdata('success', $this->lang->line("deleted"));
		} else {
			$this->session->set_flashdata('error', $this->lang->line("delete_error"));
		}
	}

}

/* End of file conversation.php */
/* Location: .//var/www/html/school_king/mvc/controllers/conversation.php */