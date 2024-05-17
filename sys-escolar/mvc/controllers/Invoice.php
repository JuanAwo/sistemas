<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Omnipay\Omnipay;
class Invoice extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("invoice_m");
		$this->load->model("feetypes_m");
		$this->load->model('payment_m');
		$this->load->model("classes_m");
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("section_m");
		$this->load->model("payment_settings_m");
		$this->load->model('user_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('student', $language);
		$this->lang->load('invoice', $language);
	}

	public function index() {
		$usertypeID = $this->session->userdata("usertypeID");			
		if($usertypeID == 3) {
			$username = $this->session->userdata("username");
			$student = $this->student_m->get_single_student(array("username" => $username));
			if(count($student)) {
				$this->data['invoices'] = $this->invoice_m->get_invoice_with_studentrelation_by_studentID($student->studentID);
				if(count($this->data['invoices'])) {
					$arrayPayment = array();
					$payments = $this->payment_m->get_order_by_payment(array('studentID' => $student->studentID));
					foreach ($this->data['invoices'] as $key => $invoice) {
						if(count($payments)) {
							foreach ($payments as $key => $payment) {
								if($invoice->invoiceID == $payment->invoiceID) {
									if(!array_key_exists($invoice->invoiceID, $arrayPayment)) {
										if($invoice->discount != 0) {
											$arrayPayment[$invoice->invoiceID] = (int) (($invoice->amount /100) * $invoice->discount) + $payment->paymentamount;
										} else {
											$arrayPayment[$invoice->invoiceID] = (int)$payment->paymentamount;
										}
									} else {
										$arrayPayment[$invoice->invoiceID] = (int)$payment->paymentamount += $arrayPayment[$invoice->invoiceID];
									}
								} else {
									if(!array_key_exists($invoice->invoiceID, $arrayPayment)) {
										if($invoice->discount != 0) {
											$arrayPayment[$invoice->invoiceID] = (int) (($invoice->amount /100) * $invoice->discount);
										} else {
											$arrayPayment[$invoice->invoiceID] = (int) 0;
										}
									}
								}
							}
						} else {
							if($invoice->discount != 0) {
								$arrayPayment[$invoice->invoiceID] = (int) (($invoice->amount /100) * $invoice->discount);
							} else {
								$arrayPayment[$invoice->invoiceID] = (int) 0;
							}		
						}

					}
					$this->data['payments'] = $arrayPayment;
				} else {
					$this->data['payments'] = array();
				}

				$this->data["subview"] = "invoice/index_parents";
				$this->load->view('_layout_main', $this->data);
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

			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$username = $this->session->userdata("username");
			$parent = $this->parents_m->get_single_parents(array('username' => $username));
			$this->data['students'] = $this->student_m->get_order_by_student(array('parentID' => $parent->parentsID, 'schoolyearID' => $schoolyearID));
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$checkstudent = $this->student_m->get_single_student(array('studentID' => $id));
				if(count($checkstudent)) {
					if($checkstudent->parentID == $parent->parentsID) {
						$classesID = $checkstudent->classesID;
						$this->data['set'] = $id;
						$this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('studentID' => $id, 'deleted_at' => 1));

						if(count($this->data['invoices'])) {
							$arrayPayment = array();
							$payments = $this->payment_m->get_order_by_payment(array('studentID' => $id));
							foreach ($this->data['invoices'] as $key => $invoice) {
								if(count($payments)) {
									foreach ($payments as $key => $payment) {
										if($invoice->invoiceID == $payment->invoiceID) {
											if(!array_key_exists($invoice->invoiceID, $arrayPayment)) {
												if($invoice->discount != 0) {
													$arrayPayment[$invoice->invoiceID] = (int) (($invoice->amount /100) * $invoice->discount) + $payment->paymentamount;
												} else {
													$arrayPayment[$invoice->invoiceID] = (int)$payment->paymentamount;
												}
											} else {
												$arrayPayment[$invoice->invoiceID] = (int)$payment->paymentamount += $arrayPayment[$invoice->invoiceID];
											}
										} else {
											if(!array_key_exists($invoice->invoiceID, $arrayPayment)) {
												if($invoice->discount != 0) {
													$arrayPayment[$invoice->invoiceID] = (int) (($invoice->amount /100) * $invoice->discount);
												} else {
													$arrayPayment[$invoice->invoiceID] = (int) 0;
												}
											}
										}
									}
								} else {
									if($invoice->discount != 0) {
										$arrayPayment[$invoice->invoiceID] = (int) (($invoice->amount /100) * $invoice->discount);
									} else {
										$arrayPayment[$invoice->invoiceID] = (int) 0;
									}		
								}

							}

							$this->data['payments'] = $arrayPayment;
						} else {
							$this->data['payments'] = array();
						}

						$this->data["subview"] = "invoice/index_parents";
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
				$this->data['set'] = 0;
				$this->data['invoices'] = array();
				$this->data["subview"] = "invoice/index_parents";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data['invoices'] = $this->invoice_m->get_invoice_with_studentrelation();

			if(count($this->data['invoices'])) {
				$arrayPayment = array();
				$payments = $this->payment_m->get_payment();
				foreach ($this->data['invoices'] as $key => $invoice) {
					if(count($payments)) {
						foreach ($payments as $key => $payment) {
							if($invoice->invoiceID == $payment->invoiceID) {
								if(!array_key_exists($invoice->invoiceID, $arrayPayment)) {
									if($invoice->discount != 0) {
										$arrayPayment[$invoice->invoiceID] = (int) (($invoice->amount /100) * $invoice->discount) + $payment->paymentamount;
									} else {
										$arrayPayment[$invoice->invoiceID] = (int)$payment->paymentamount;
									}
								} else {
									$arrayPayment[$invoice->invoiceID] = (int)$payment->paymentamount += $arrayPayment[$invoice->invoiceID];
								}
							} else {
								if(!array_key_exists($invoice->invoiceID, $arrayPayment)) {
									if($invoice->discount != 0) {
										$arrayPayment[$invoice->invoiceID] = (int) (($invoice->amount /100) * $invoice->discount);
									} else {
										$arrayPayment[$invoice->invoiceID] = (int) 0;
									}
									
								}
							}
						}
					} else {
						if($invoice->discount != 0) {
							$arrayPayment[$invoice->invoiceID] = (int) (($invoice->amount /100) * $invoice->discount);
						} else {
							$arrayPayment[$invoice->invoiceID] = (int) 0;
						}		
					}

				}
				$this->data['payments'] = $arrayPayment;
			} else {
				$this->data['payments'] = array();
			}

			$this->data["subview"] = "invoice/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("invoice_classesID"),
				'rules' => 'trim|required|xss_clean|max_length[11]|numeric|callback_unique_classID'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("invoice_studentID"),
				'rules' => 'trim|required|xss_clean|max_length[11]|numeric'
			),
			array(
				'field' => 'feetype',
				'label' => $this->lang->line("invoice_feetype"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'amount',
				'label' => $this->lang->line("invoice_amount"),
				'rules' => 'trim|required|xss_clean|max_length[20]|numeric|callback_valid_number'
			),
			array(
				'field' => 'discount',
				'label' => $this->lang->line("invoice_discount"),
				'rules' => 'trim|xss_clean|max_length[11]|numeric|callback_valid_number'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("invoice_date"),
				'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid'
			),

		);
		return $rules;
	}

	protected function payment_rules() {
		$rules = array(
			array(
				'field' => 'amount',
				'label' => $this->lang->line("invoice_amount"),
				'rules' => 'trim|required|xss_clean|max_length[11]|numeric|callback_valid_number|callback_unique_amount'
			),
			array(
				'field' => 'payment_method',
				'label' => $this->lang->line("invoice_paymentmethod"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_paymentmethod'
			)
		);
		return $rules;
	}


	protected function payumoney_rules() {
		$rules = array(
			array(
				'field' => 'first_name',
				'label' => $this->lang->line("first_name"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("email"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("phone"),
				'rules' => 'trim|required|xss_clean'
			)
		);
		return $rules;
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/jqueryUI/jqueryui.css',
				'assets/datepicker/datepicker.css',
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/jqueryUI/jqueryui.min.js',
				'assets/datepicker/datepicker.js',
				'assets/select2/select2.js'
			)
		);

		$this->data['classes'] = $this->classes_m->get_classes();
		$this->data['feetypes'] = $this->feetypes_m->get_feetypes();
		$classesID = $this->input->post("classesID");
		if($classesID != 0) {
			$this->data['students'] = $this->student_m->get_order_by_student(array("classesID" => $classesID, 'schoolyearID' => $this->data['siteinfos']->school_year));
		} else {
			$this->data['students'] = "empty";
		}
		$this->data['studentID'] = 0;
		if($_POST) {
			$this->data['studentID'] = $this->input->post('studentID');
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "invoice/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				if($this->input->post('discount') == '' || $this->input->post('discount') == 0) {
					$discount = 0;
				} else {
					$discount = $this->input->post('discount');
				}

				$getfeetype = $this->feetypes_m->get_single_feetypes(array('feetypes' => $this->input->post('feetype')));
				if(!count($getfeetype)) {
					$this->feetypes_m->insert_feetypes(array('feetypes' => $this->input->post('feetype')));
				}

				if($this->input->post('studentID')) {
					$array = array(
						'schoolyearID' => $this->data['siteinfos']->school_year,
						'classesID' => $this->input->post('classesID'),
						'studentID' => $this->input->post('studentID'),
						'feetype' => $this->input->post("feetype"),
						'amount' => $this->input->post("amount"),
						'discount' => $discount,
						'paidstatus' => 0,
						'userID' => $this->session->userdata('loginuserID'),
						'usertypeID' => $this->session->userdata('usertypeID'),
						'uname' => $this->session->userdata('name'), 
						'date' => date("Y-m-d", strtotime($this->input->post("date"))),
						'create_date' => date('Y-m-d'),
						'day' => date('d'),
						'month' => date('m'),
						'year' => date('Y'),
						'deleted_at' => 1
					);
					$returnID = $this->invoice_m->insert_invoice($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				 	redirect(base_url("invoice/view/$returnID"));
				} else {
					$classesID = $this->input->post('classesID');
					$getstudents = $this->student_m->get_order_by_student(array("classesID" => $classesID, 'schoolyearID' => $this->data['siteinfos']->school_year));
					foreach ($getstudents as $key => $getstudent) {
						$array = array(
							'schoolyearID' => $this->data['siteinfos']->school_year,
							'classesID' => $this->input->post('classesID'),
							'studentID' => $getstudent->studentID,
							'feetype' => $this->input->post("feetype"),
							'amount' => $this->input->post("amount"),
							'discount' => $discount,
							'paidstatus' => 0,
							'userID' => $this->session->userdata('loginuserID'),
							'usertypeID' => $this->session->userdata('usertypeID'),
							'uname' => $this->session->userdata('name'), 
							'date' => date("Y-m-d", strtotime($this->input->post("date"))),
							'create_date' => date('Y-m-d'),
							'day' => date('d'),
							'month' => date('m'),
							'year' => date('Y'),
							'deleted_at' => 1
						);
						$this->invoice_m->insert_invoice($array);
					}
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				 	redirect(base_url("invoice/index"));
				}
			}
		} else {
			$this->data["subview"] = "invoice/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/jqueryUI/jqueryui.css',
				'assets/datepicker/datepicker.css',
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/jqueryUI/jqueryui.min.js',
				'assets/datepicker/datepicker.js',
				'assets/select2/select2.js'
			)
		);

		$id = htmlentities(escapeString($this->uri->segment(3)));

		if((int)$id) {
			$this->data['feetypes'] = $this->feetypes_m->get_feetypes();
			$this->data['invoice'] = $this->invoice_m->get_single_invoice(array('invoiceID' => $id, 'deleted_at' => 1));
			$this->data['classes'] = $this->classes_m->get_classes();

			if($this->data['invoice']) {
				if($this->data['invoice']->classesID != 0) {
					$this->data['students'] = $this->student_m->get_order_by_student(array("classesID" => $this->data['invoice']->classesID));
				} else {
					$this->data['students'] = "empty";
				}
				$this->data['studentID'] = $this->data['invoice']->studentID;

				if($_POST) {
					$this->data['studentID'] = $this->input->post('studentID');
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "invoice/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$status = 0;
						$classesID = $this->input->post('classesID');
						$studentID = $this->input->post('studentID');
						$feetype = $this->input->post("feetype");
						$amount = $this->input->post("amount");
						$discount = $this->input->post("discount");
						$date = date("Y-m-d", strtotime($this->input->post("date")));

						$setDiscount = (($amount/100) * $discount);
						$payment = $this->payment_m->get_payment_by_sum($id);
						if($payment->paymentamount != NULL) {
							if($amount > ($payment->paymentamount+$setDiscount)) {
								$status = 1;
							} elseif($amount <= ($payment->paymentamount+$setDiscount)) {
								$status = 2;
							}
						} else {
							$status = 0;
						}

						$array = array(
							'classesID' => $classesID,
							'studentID' => $studentID,
							'feetype' => $feetype,
							'amount' => $amount,
							'discount' => $discount,
							'date' => $date,
							'paidstatus' => $status,
							'userID' => $this->session->userdata('loginuserID'),
							'usertypeID' => $this->session->userdata('usertypeID'),
							'uname' => $this->session->userdata('name')
						);

						$this->invoice_m->update_invoice($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					 	redirect(base_url("invoice/index"));
					}
				} else {
					$this->data["subview"] = "invoice/edit";
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
		if((int)$id) {
			$this->data['invoice'] = $this->invoice_m->get_single_invoice(array('invoiceID' => $id, 'deleted_at' => 1));

			if($this->data['invoice']) {
				$this->invoice_m->delete_invoice($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url('invoice/index'));
			} else {
				redirect(base_url('invoice/index'));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function view() {
		$usertypeID = $this->session->userdata("usertypeID");
		if($usertypeID == 3) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$username = $this->session->userdata("username");
				$getstudent = $this->student_m->get_single_student(array("username" => $username));
				$this->data["invoice"] = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($id);
				if($this->data['invoice'] && ($this->data['invoice']->studentID == $getstudent->studentID)) {
					$this->data['payments'] = $this->payment_m->get_order_by_payment(array('invoiceID' => $id));
					$this->data["student"] = $this->student_m->get_student($this->data["invoice"]->studentID);
					$this->data["subview"] = "invoice/view";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} elseif($usertypeID == 4) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$username = $this->session->userdata("username");
				$parents = $this->parents_m->get_single_parents(array('username' => $username));
				if(count($parents)) {
					$getStudents = $this->student_m->get_order_by_student(array('parentID' => $parents->parentsID));
					if(count($getStudents)) {
						$realStudent = array();
						foreach ($getStudents as $getStudent) {
							$realStudent[] = $getStudent->studentID;
						}

						$this->data["invoice"] = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($id);
						if($this->data['invoice']) {
							if(in_array($this->data['invoice']->studentID, $realStudent)) {
								$this->data["student"] = $this->student_m->get_student($this->data["invoice"]->studentID);
								$this->data['payments'] = $this->payment_m->get_order_by_payment(array('invoiceID' => $id));
								$this->data["subview"] = "invoice/view";
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
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data["invoice"] = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($id);
				if($this->data["invoice"]) {
					$this->data['payments'] = $this->payment_m->get_order_by_payment(array('invoiceID' => $id));
					$this->data["student"] = $this->student_m->get_student($this->data["invoice"]->studentID);
					$this->data["subview"] = "invoice/view";
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
		if(permissionChecker('invoice_view')) {
			$usertypeID = $this->session->userdata("usertypeID");
			if($usertypeID == 3) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				if((int)$id) {
					$username = $this->session->userdata("username");
					$getstudent = $this->student_m->get_single_student(array("username" => $username));
					$this->data["invoice"] = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($id);
					if($this->data['invoice'] && ($this->data['invoice']->studentID == $getstudent->studentID)) {
						$this->data['payments'] = $this->payment_m->get_order_by_payment(array('invoiceID' => $id));
						$this->data["student"] = $this->student_m->get_student($this->data["invoice"]->studentID);
						$this->printview($this->data, 'invoice/print_preview');
					} else {
						$this->data["subview"] = "error";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} elseif($usertypeID == 4) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				if((int)$id) {
					$username = $this->session->userdata("username");
					$parents = $this->parents_m->get_single_parents(array('username' => $username));
					if(count($parents)) {
						$getStudents = $this->student_m->get_order_by_student(array('parentID' => $parents->parentsID));
						if(count($getStudents)) {
							$realStudent = array();
							foreach ($getStudents as $getStudent) {
								$realStudent[] = $getStudent->studentID;
							}

							$this->data["invoice"] = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($id);
							if($this->data['invoice']) {
								if(in_array($this->data['invoice']->studentID, $realStudent)) {
									$this->data["student"] = $this->student_m->get_student($this->data["invoice"]->studentID);
									$this->data['payments'] = $this->payment_m->get_order_by_payment(array('invoiceID' => $id));
									$this->printview($this->data, 'invoice/print_preview');
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
				$id = htmlentities(escapeString($this->uri->segment(3)));
				if((int)$id) {
					$this->data["invoice"] = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($id);
					if($this->data["invoice"]) {
						$this->data['payments'] = $this->payment_m->get_order_by_payment(array('invoiceID' => $id));
						$this->data["student"] = $this->student_m->get_student($this->data["invoice"]->studentID);
						$this->printview($this->data, 'invoice/print_preview');
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
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail() {
		$id = $this->input->post('id');
		if((int)$id) {
			$this->data["invoice"] = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($id);
			if($this->data["invoice"]) {
				$this->data['payments'] = $this->payment_m->get_order_by_payment(array('invoiceID' => $id));
				$this->data["student"] = $this->student_m->get_student($this->data["invoice"]->studentID);
				$email = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');
				$this->viewsendtomail($this->data, 'invoice/print_preview', $email, $subject, $message);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function payment() {
		if(permissionChecker('invoice_view')) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
                    'assets/datepicker/datepicker.css',
				),
				'js' => array(
                    'assets/datepicker/datepicker.js',
					'assets/select2/select2.js'
				)
			);
			if($this->input->post('payment_method') != '0') {
				$this->data['setPaymentMethod'] = $this->input->post('payment_method');
			} else {
				$this->data['setPaymentMethod'] = '';
			}

			$usertypeID = $this->session->userdata('usertypeID');
			if($usertypeID == 1 || $usertypeID == 5) {
				$id = htmlentities(escapeString($this->uri->segment(3)));
				if((int)$id) {
					$this->data['invoice'] = $this->invoice_m->get_single_invoice(array('invoiceID' => $id, 'deleted_at' => 1));
					if($this->data['invoice']) {
						if($this->data['invoice']->paidstatus != 2) {
                            $api_config = array();
                            $get_configs = $this->payment_settings_m->get_order_by_config();
                            foreach ($get_configs as $key => $get_key) {
                                $api_config[$get_key->config_key] = $get_key->value;
                            }
                            $this->data['payment_settings'] = $api_config;
                            $this->data['payment'] = $this->payment_m->get_payment_by_sum($id);
							$this->data['dueamount'] = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $this->data['payment']->paymentamount));
							
							if($_POST) {
								$rules = $this->payment_rules();
								$this->form_validation->set_rules($rules);
								if($this->form_validation->run() == FALSE) {
									$this->data["subview"] = "invoice/payment";
									$this->load->view('_layout_main', $this->data);
								} else {
									if($this->input->post('payment_method') == 'Cash' || $this->input->post('payment_method') == 'Cheque') {
										$payment_array = array(
											"invoiceID" => $id,
											'schoolyearID' => $this->data['siteinfos']->school_year,
											"studentID"	=> $this->data['invoice']->studentID,
											"paymentamount" => $this->input->post('amount'),
											"paymenttype" => $this->input->post('payment_method'),
											"paymentdate" => date('Y-m-d'),
											"paymentday" => date('d'),
											"paymentmonth" => date('m'),
											"paymentyear" => date('Y'),
											'userID' => $this->session->userdata('loginuserID'),
											'usertypeID' => $this->session->userdata('usertypeID'),
											'uname' => $this->session->userdata('name'),
											'transactionID' => 'CASHANDCHEQUE'.rand(1, 99999999999999999999999)

										);
										$this->payment_m->insert_payment($payment_array);
										if($this->data['dueamount'] <= $this->input->post('amount')) {
											$this->invoice_m->update_invoice(array('paidstatus' => 2), $id);
										} else {
											$this->invoice_m->update_invoice(array('paidstatus' => 1), $id);
										}
										$this->session->set_flashdata('success', 'Se cobró correctamente');
										redirect(base_url('invoice/view/'.$id));
									} elseif($this->input->post('payment_method') == 'Stripe') {
										$rulesStrip = $this->strip_rules();
										$this->form_validation->set_rules($rulesStrip);
										if($this->form_validation->run() == FALSE) {
											$this->data["subview"] = "invoice/payment";
											$this->load->view('_layout_main', $this->data);
										} else {
											$this->post_data = $this->input->post();
											$this->post_data['id'] = $this->uri->segment(3);
											$this->invoice_data = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($this->post_data['id']);
											$this->stripe($this->uri->segment(3), $this->input->post());
										}
									} else {
										$this->session->set_flashdata('error', 'You are not authorized');
										redirect(base_url("invoice/payment/$id"));
									}
								}
							} else {
								$this->data["subview"] = "invoice/payment";
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
				if($usertypeID == 3) {
					$id = htmlentities(escapeString($this->uri->segment(3)));
					if((int)$id) {
						$api_config = array();
                        $get_configs = $this->payment_settings_m->get_order_by_config();
                        foreach ($get_configs as $key => $get_key) {
                            $api_config[$get_key->config_key] = $get_key->value;
                        }
                        $this->data['payment_settings'] = $api_config;

						$username = $this->session->userdata("username");
						$getstudent = $this->student_m->get_single_student(array("username" => $username));
						$this->data['invoice'] = $this->invoice_m->get_single_invoice(array('invoiceID' => $id, 'deleted_at' => 1));
						if($this->data['invoice']) {
							if($this->data['invoice']->paidstatus != 2) {
								$this->data['payment'] = $this->payment_m->get_payment_by_sum($id);
								$this->data['dueamount'] = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $this->data['payment']->paymentamount));
								if($this->data['invoice'] && ($this->data['invoice']->studentID == $getstudent->studentID)) {
									if($_POST) {
										$rules = $this->payment_rules();
										$this->form_validation->set_rules($rules);
										if($this->form_validation->run() == FALSE) {
											$this->data["subview"] = "invoice/payment";
											$this->load->view('_layout_main', $this->data);
										} else {
											if($this->input->post('payment_method') == 'Stripe') {
												$rulesStrip = $this->strip_rules();
												$this->form_validation->set_rules($rulesStrip);
												if($this->form_validation->run() == FALSE) {
													$this->data["subview"] = "invoice/payment";
													$this->load->view('_layout_main', $this->data);
												} else {
													$this->post_data = $this->input->post();
													$this->post_data['id'] = $this->uri->segment(3);
													$this->invoice_data = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($this->post_data['id']);
													$this->stripe($this->uri->segment(3), $this->input->post());
												}
											} elseif($this->input->post('payment_method') == 'Payumoney') {
												$rulesPayumoney = $this->payumoney_rules();
												$this->form_validation->set_rules($rulesPayumoney);
												if($this->form_validation->run() == FALSE) {
													$this->data["subview"] = "invoice/payment";
													$this->load->view('_layout_main', $this->data);
												} else {
													$this->post_data = $this->input->post();
			                                        $this->post_data['id'] = $this->uri->segment(3);
			                                        $this->invoice_data = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($this->post_data['id']);
			                                        $this->payumoney($this->uri->segment(3), $this->input->post());
												}
											} else {
												$this->session->set_flashdata('error', 'You are not authorized');
  												redirect(base_url("invoice/payment/$id"));
											}
										}
									} else {
										$this->data["subview"] = "invoice/payment";
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
				} elseif($usertypeID == 4) {
					$id = htmlentities(escapeString($this->uri->segment(3)));
                    $api_config = array();
                    $get_configs = $this->payment_settings_m->get_order_by_config();
                    foreach ($get_configs as $key => $get_key) {
                        $api_config[$get_key->config_key] = $get_key->value;
                    }
                    $this->data['payment_settings'] = $api_config;

                    if((int)$id) {
						$username = $this->session->userdata("username");
						$parents = $this->parents_m->get_single_parents(array('username' => $username));
						if(count($parents)) {
							$getStudents = $this->student_m->get_order_by_student(array('parentID' => $parents->parentsID));
							if(count($getStudents)) {
								$realStudent = array();
								foreach ($getStudents as $getStudent) {
									$realStudent[] = $getStudent->studentID;
								}

								$this->data["invoice"] = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($id);
								if($this->data['invoice']) {
									if(in_array($this->data['invoice']->studentID, $realStudent)) {
										if($this->data['invoice']->paidstatus != 2) {
											$this->data['payment'] = $this->payment_m->get_payment_by_sum($id);
											$this->data['dueamount'] = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $this->data['payment']->paymentamount));
											if($_POST) {
												$rules = $this->payment_rules();
												$this->form_validation->set_rules($rules);
												if($this->form_validation->run() == FALSE) {
													$this->data["subview"] = "invoice/payment";
													$this->load->view('_layout_main', $this->data);
												} else {
													if($this->input->post('payment_method') == 'Stripe') {
														$rulesStrip = $this->strip_rules();
														$this->form_validation->set_rules($rulesStrip);
														if($this->form_validation->run() == FALSE) {
															$this->data["subview"] = "invoice/payment";
															$this->load->view('_layout_main', $this->data);
														} else {
															$this->post_data = $this->input->post();
															$this->post_data['id'] = $this->uri->segment(3);
															$this->invoice_data = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($this->post_data['id']);
															$this->stripe($this->uri->segment(3), $this->input->post());
														}
													} elseif($this->input->post('payment_method') == 'Payumoney') {
														$rulesPayumoney = $this->payumoney_rules();
														$this->form_validation->set_rules($rulesPayumoney);
														if($this->form_validation->run() == FALSE) {
															$this->data["subview"] = "invoice/payment";
															$this->load->view('_layout_main', $this->data);
														} else {
															$this->post_data = $this->input->post();
					                                        $this->post_data['id'] = $this->uri->segment(3);
					                                        $this->invoice_data = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($this->post_data['id']);
					                                        $this->payumoney($this->uri->segment(3), $this->input->post());
														}
													} else {
														$this->session->set_flashdata('error', 'You are not authorized');
		  												redirect(base_url("invoice/payment/$id"));
													}
												}
											} else {
												$this->data["subview"] = "invoice/payment";
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
							$this->data["subview"] = "error";
							$this->load->view('_layout_main', $this->data);
						}
					} else {
						$this->data["subview"] = "error";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$id = htmlentities(escapeString($this->uri->segment(3)));
                    $api_config = array();
                    $get_configs = $this->payment_settings_m->get_order_by_config();
                    foreach ($get_configs as $key => $get_key) {
                        $api_config[$get_key->config_key] = $get_key->value;
                    }
                    $this->data['payment_settings'] = $api_config;

                    if((int)$id) {
						$this->data['invoice'] = $this->invoice_m->get_single_invoice(array('invoiceID' => $id, 'deleted_at' => 1));
						if($this->data['invoice']) {
							if($this->data['invoice']->paidstatus != 2) {
								$this->data['payment'] = $this->payment_m->get_payment_by_sum($id);
								$this->data['dueamount'] = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $this->data['payment']->paymentamount));
								if($_POST) {
									$rules = $this->payment_rules();
									$this->form_validation->set_rules($rules);
									if($this->form_validation->run() == FALSE) {
										$this->data["subview"] = "invoice/payment";
										$this->load->view('_layout_main', $this->data);
									} else {
										if($this->input->post('payment_method') == 'Stripe') {
											$rulesStrip = $this->strip_rules();
											$this->form_validation->set_rules($rulesStrip);
											if($this->form_validation->run() == FALSE) {
												$this->data["subview"] = "invoice/payment";
												$this->load->view('_layout_main', $this->data);
											} else {
												$this->post_data = $this->input->post();
												$this->post_data['id'] = $this->uri->segment(3);
												$this->invoice_data = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($this->post_data['id']);
												$this->stripe($this->uri->segment(3), $this->input->post());
											}
										} elseif($this->input->post('payment_method') == 'Payumoney') {
											$rulesPayumoney = $this->payumoney_rules();
											$this->form_validation->set_rules($rulesPayumoney);
											if($this->form_validation->run() == FALSE) {
												$this->data["subview"] = "invoice/payment";
												$this->load->view('_layout_main', $this->data);
											} else {
												$this->post_data = $this->input->post();
		                                        $this->post_data['id'] = $this->uri->segment(3);
		                                        $this->invoice_data = $this->invoice_m->get_invoice_with_studentrelation_by_invoiceID($this->post_data['id']);
		                                        $this->payumoney($this->uri->segment(3), $this->input->post());
											}
										} else {
											$this->session->set_flashdata('error', 'You are not authorized');
											redirect(base_url("invoice/payment/$id"));
										}
									}
								} else {
									$this->data["subview"] = "invoice/payment";
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
			}	
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}
  
    public function generateHash($array)
    {
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        if(
            empty($array['key'])
            || empty($array['txnid'])
            || empty($array['amount'])
            || empty($array['firstname'])
            || empty($array['email'])
            || empty($array['phone'])
            || empty($array['productinfo'])
            || empty($array['surl'])
            || empty($array['furl'])
        )
        {
            return false;
        } else {
            $hash         = '';
            $salt = $array['salt'];
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';
            foreach($hashVarsSeq as $hash_var) {
                $hash_string .= isset($array[$hash_var]) ? $array[$hash_var] : '';
                $hash_string .= '|';
            }
            $hash_string .= $salt;
            $hash = strtolower(hash('sha512', $hash_string));
            return $hash;
        }
    }

 
	public function getCancelPayment() {
		$params = $this->session->userdata('params');
		redirect(base_url('invoice/view/'.$params['invoice_id']));
	}


    public function stripe($invoice, $request)
    {
        if ($request['payment_method'] == 'Stripe') {
            if ($request['card_number'] && $request['cvv'] && $request['expire_month'] && $request['expire_year']) {
                $api_config = array();
                $get_configs = $this->payment_settings_m->get_order_by_config();
                foreach ($get_configs as $key => $get_key) {
                    $api_config[$get_key->config_key] = $get_key->value;
                }
                $this->data['set_key'] = $api_config;
                try {
                    $gateway = Omnipay::create('Stripe');
                    $gateway->setApiKey($api_config['stripe_secret']);
                    $gateway->setTestMode($api_config['stripe_demo']);


                    $formData = array('number' => $request['card_number'], 'expiryMonth' => $request['expire_month'], 'expiryYear' => $request['expire_year'], 'cvv' => $request['cvv']);
                    $response = $gateway->purchase(array(
                            'amount' 	=> number_format((float)$request['amount'], 2, '.', ''),
                            'invoice'   => $invoice,
                            'currency' 	=> $this->data["siteinfos"]->currency_code,
                            'card' => $formData)
                    )->send();

                    if ($response->isSuccessful()) {
                        // payment was successful: update database
                        if($response->getData()['status']==="succeeded") {
                            $dbTransactionID = $this->payment_m->get_single_payment(array('transactionID' => $response->getData()['id']));
                            if(!count($dbTransactionID)) {
                                $this->data['invoice'] = $this->invoice_m->get_single_invoice(array('invoiceID' => $invoice, 'deleted_at' => 1));
                                $this->data['payment'] = $this->payment_m->get_payment_by_sum($invoice);
                                $this->data['dueamount'] = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $this->data['payment']->paymentamount));

                                $payment_array = array(
                                    'schoolyearID' => $this->data['siteinfos']->school_year,
                                    "invoiceID" => $invoice,
                                    "studentID"	=> $this->data['invoice']->studentID,
                                    "paymentamount" => floatval($request['amount']),
                                    "paymenttype" => 'Stripe',
                                    "paymentdate" => date('Y-m-d'),
                                    "paymentday" => date('d'),
                                    "paymentmonth" => date('m'),
                                    "paymentyear" => date('Y'),
                                    'userID' => $this->session->userdata('loginuserID'),
                                    'usertypeID' => $this->session->userdata('usertypeID'),
                                    'uname' => $this->session->userdata('name'),
                                    'transactionID' => $response->getData()['id']
                                );

                                $this->payment_m->insert_payment($payment_array);
                                if(floatval($this->data['dueamount']) <= floatval($request['amount'])) {
                                    $this->invoice_m->update_invoice(array('paidstatus' => 2), $invoice);
                                } else {
                                    $this->invoice_m->update_invoice(array('paidstatus' => 1), $invoice);
                                }
                                $this->session->set_flashdata('success', 'Payment successful!');
                                redirect(base_url("invoice/view/".$invoice));
                            }

                        }
                    } elseif ($response->isRedirect()) {
                        // redirect to offsite payment gateway
                        $response->redirect();
                    } else {
                        // payment failed: display message to customer
                        $this->session->set_flashdata('error', "Something went wrong!");
                        redirect(base_url('invoice/payment/'.$invoice));
                    }
                } catch(\Exception $ex){
                    $this->session->set_flashdata('error', $ex->getMessage());
                    redirect(base_url('invoice/payment/'.$invoice));
                }
            } else {
                $this->session->set_flashdata('error', "Something went wrong!");
                redirect(base_url('invoice/payment/'.$invoice));
            }
        }
    }


	function unique_amount() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$this->data['invoice'] = $this->invoice_m->get_single_invoice(array('invoiceID' => $id, 'deleted_at' => 1));
		if($this->data['invoice']) {
			if($this->data['invoice']->paidstatus != 2) {
				$this->data['payment'] = $this->payment_m->get_payment_by_sum($id);
				$this->data['dueamount'] = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $this->data['payment']->paymentamount));
				if($this->input->post('amount') > $this->data['dueamount']) {
					$this->form_validation->set_message("unique_amount", "%s es mayor que la cantidad debida.");
					return FALSE;
				}
				return TRUE;
			} else {
				return FALSE;
			}	
		} else {
			return FALSE;
		}
	}

	function call_all_student() {
		$classesID = $this->input->post('id');
		if((int)$classesID) {
			echo "<option value='". 0 ."'>". $this->lang->line('invoice_all_student') ."</option>";
			$students = $this->student_m->get_order_by_student(array('classesID' => $classesID, 'schoolyearID' => $this->data['siteinfos']->school_year));
			foreach ($students as $key => $student) {
				echo "<option value='". $student->studentID ."'>". $student->name ."</option>";
			}
		} else {
			echo "<option value='". 0 ."'>". $this->lang->line('invoice_all_student') ."</option>";
		}
	}

	public function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("invoice/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("invoice/index"));
		}
	}

	function date_valid($date) {
		if(strlen($date) <10) {
			$this->form_validation->set_message("date_valid", "%s no es valido este formato valid dd-mm-yyyy");
	     	return FALSE;
		} else {
	   		$arr = explode("-", $date);
	        $dd = $arr[0];
	        $mm = $arr[1];
	        $yyyy = $arr[2];
	      	if(checkdate($mm, $dd, $yyyy)) {
	      		return TRUE;
	      	} else {
	      		$this->form_validation->set_message("date_valid", "%s no es valido este formato valid dd-mm-yyyy");
	     		return FALSE;
	      	}
	    }
	}

	function unique_classID() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classID", "%s es requerido");
	     	return FALSE;
		}
		return TRUE;
	}

	function valid_number() {
		if($this->input->post('amount') && $this->input->post('amount') < 0) {
			$this->form_validation->set_message("valid_number", "%s es un número invalido.");
			return FALSE;
		}
		return TRUE;
	}

	function unique_paymentmethod() {
		if($this->input->post('payment_method') === '0') {
			$this->form_validation->set_message("unique_paymentmethod", "El metodo de pago es requerido.");
					return FALSE;
		} else {
			$api_config = array();
			$get_configs = $this->payment_settings_m->get_order_by_config();
			foreach ($get_configs as $key => $get_key) {
				$api_config[$get_key->config_key] = $get_key->value;
			}
			if($this->input->post('payment_method') == 'Cash' || $this->input->post('payment_method') == 'Cheque') {
				return TRUE;
			} elseif($this->input->post('payment_method') == 'Stripe' && $api_config['stripe_status'] == 1) {
				if($api_config['stripe_secret'] ==""){
					$this->form_validation->set_message("unique_paymentmethod", " configuraciones de Stripe es requerido");
					return FALSE;
				}
				return TRUE;

			} else {
				$this->form_validation->set_message("unique_paymentmethod", "Payment settings required");
				return FALSE;
			}
		}
	}
}

/* End of file invoice.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/invoice.php */
