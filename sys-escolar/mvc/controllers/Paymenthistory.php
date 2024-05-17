<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paymenthistory extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('feetypes_m');
		$this->load->model('invoice_m');
		$this->load->model('payment_m');
		$this->load->model('student_m');
		$this->load->model('parents_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('paymenthistory', $language);	
	}

	protected function payment_rules() {
		$usertypeID = $this->session->userdata('usertypeID');
		$rules = array(
			array(
				'field' => 'amount',
				'label' => $this->lang->line("paymenthistory_amount"),
				'rules' => 'trim|required|xss_clean|max_length[11]|numeric|callback_valid_number|callback_unique_amount'


			),
			array(
				'field' => 'payment_method',
				'label' => $this->lang->line("paymenthistory_paymentmethod"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_paymentmethod'
			)
		);
		return $rules;
	}

	public function index() {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 3) {
			$username = $this->session->userdata("username");
			$student = $this->student_m->get_single_student(array("username" => $username));
			if(count($student)) {
				$this->data['payments'] = $this->payment_m->get_payment_with_studentrelation_by_studentID($student->studentID);
				$this->data["subview"] = "paymenthistory/index_parents";
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
						$this->data['set'] = $id;
						$this->data['payments'] = $this->payment_m->get_payment_with_studentrelation_by_studentID($id);
						$this->data["subview"] = "paymenthistory/index_parents";
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
				$this->data['payments'] = array();
				$this->data['set'] = 0;
				$this->data["subview"] = "paymenthistory/index_parents";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data['payments'] = $this->payment_m->get_payment_with_studentrelation();
			$this->data["subview"] = "paymenthistory/index";
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
		if((int)$id) {
			$this->data['payment'] = $this->payment_m->get_payment($id);
			if(count($this->data['payment'])) {
				if($this->data['payment']->paymenttype != "Paypal" && $this->data['payment']->paymenttype != 'Stripe' && $this->data['payment']->paymenttype != 'PayUmoney') {
					$this->data['invoice'] = $this->invoice_m->get_invoice($this->data['payment']->invoiceID);
					if(count($this->data['invoice'])) {
						if($_POST) {
							$rules = $this->payment_rules();
							$this->form_validation->set_rules($rules);
							if ($this->form_validation->run() == FALSE) {
								echo validation_errors();
								$this->data["subview"] = "paymenthistory/edit";
								$this->load->view('_layout_main', $this->data);
							} else {
								$paidstatus = 0;
								$this->data['getDbPayment'] = $this->payment_m->get_payment_by_sum_for_edit($this->data['payment']->invoiceID, $id);

								$this->data['dueamount'] = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $this->data['getDbPayment']->paymentamount));
								if($this->input->post('amount') >= $this->data['dueamount']) {
									$paidstatus = 2;
								} else {
									$paidstatus = 1;
								}

								$paymentArray = array(
									'paymentamount' => $this->input->post('amount'),
									'paymentdate' => date('Y-m-d'),
									'userID' => $this->session->userdata('loginuserID'),
									'usertypeID' => $this->session->userdata('usertypeID'), 
									'uname' => $this->session->userdata('name'),
								);

								$this->payment_m->update_payment($paymentArray, $id);
								$this->invoice_m->update_invoice(array('paidstatus' => $paidstatus), $this->data['invoice']->invoiceID);
								$this->session->set_flashdata('success', $this->lang->line('menu_success'));
								redirect(base_url("paymenthistory/index"));
							}
						} else {
							$this->data["subview"] = "paymenthistory/edit";
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
		if((int)$id) {
			$this->data['payment'] = $this->payment_m->get_payment($id);
			if(count($this->data['payment'])) {
				if($this->data['payment']->paymenttype != "Paypal" && $this->data['payment']->paymenttype != 'Stripe' && $this->data['payment']->paymenttype != 'PayUmoney') {
					$this->data['invoice'] = $this->invoice_m->get_invoice($this->data['payment']->invoiceID);
					if(count($this->data['invoice'])) {
						$paidstatus = 0;
						$this->data['getDbPayment'] = $this->payment_m->get_payment_by_sum_for_edit($this->data['payment']->invoiceID, $id);

						$calculationAmount = ($this->data['invoice']->amount - $this->data['getDbPayment']->paymentamount);

						$this->data['dueamount'] = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $this->data['getDbPayment']->paymentamount));
						if($calculationAmount == $this->data['invoice']->amount) {
							$paidstatus = 0;
						} else {
							$paidstatus = 1;
						}

						$this->payment_m->delete_payment($id);
						$this->invoice_m->update_invoice(array('paidstatus' => $paidstatus), $this->data['invoice']->invoiceID);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("paymenthistory/index"));
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

	function valid_number() {
		if($this->input->post('amount') && $this->input->post('amount') < 0) {
			$this->form_validation->set_message("valid_number", "%s es un número invalido.");
			return FALSE;
		}
		return TRUE;
	}

	function unique_amount() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$this->data['payment'] = $this->payment_m->get_payment($id);
		if(count($this->data['payment'])) {
			$this->data['invoice'] = $this->invoice_m->get_single_invoice(array('invoiceID' => $this->data['payment']->invoiceID));
			if($this->data['invoice']) {
				$this->data['getDbPayment'] = $this->payment_m->get_payment_by_sum_for_edit($this->data['payment']->invoiceID, $id);
				$this->data['dueamount'] = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $this->data['getDbPayment']->paymentamount));
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

	function unique_paymentmethod() {
		if($this->input->post('payment_method') === '0') {
			$this->form_validation->set_message("unique_paymentmethod", "%s es requerido");
	     	return FALSE;
		}
		return TRUE;
	}

	public function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("paymenthistory/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("paymenthistory/index"));
		}
	}
}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */
