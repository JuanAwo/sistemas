<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reset extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper("form");
		$this->load->library("email");
		$this->load->library("form_validation");
		$this->load->helper("url");
	}

	protected function rules() {
		$rules = array(
				 array(
					'field' => 'newpassword',
					'label' => "New Password",
					'rules' => "trim|required|xss_clean|min_length[4]|max_length[40]|matches[repassword]"
				),
				array(
					'field' => 'repassword',
					'label' => "Re-Password",
					'rules' => "trim|required|xss_clean|min_length[4]|max_length[40]"
				)
			);
		return $rules;
	}

	protected function rules_email() {
		$rules = array(
				 array(
					'field' => 'email',
					'label' => "Email",
					'rules' => "trim|required|xss_clean|max_length[40]|valid_email"
				)
			);
		return $rules;
	}

	public function index() {
		$this->load->database();
		$this->load->model("reset_m");
		$this->load->library('session');
		$array = array();
		$reset_key = "";
		$tmp_url = "";
		$i = 0;
		$this->data['form_validation'] = "No";
		$this->data['siteinfos'] = $this->reset_m->get_site();

		if($_POST) {
			$rules = $this->rules_email();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->data["subview"] = 'reset/index';
				$this->load->view('_layout_reset', $this->data);
			} else {
				$email = $this->input->post('email');
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				foreach ($tables as $table) {
					$dbuser = $this->reset_m->get_table_users($table, $email);
					if(count($dbuser)) {
						$rand = rand(1, 9999999999);
						$reset_key = $this->reset_m->hash($rand.date('y-m-d h i s').$dbuser->usertype.$dbuser->username.$dbuser->name);
						$tmp_url = base_url("reset/password/".$reset_key);
						$array['permition'][$i] = 'yes';
					} else {
						$array['permition'][$i] = 'no';
					}
					$i++;
				}

				if(in_array('yes', $array['permition'])) {
					$dbreset = $this->reset_m->get_reset();
					if(count($dbreset)) {
						if($this->db->truncate('reset')) {
							$this->reset_m->insert_reset(array('keyID' => $reset_key, 'email' => $email));
						} else {
							$this->session->set_flashdata('reset_error', 'Restaurar la contraseña esta desactivado.');
						}
					} else {
						$this->reset_m->insert_reset(array('keyID' => $reset_key, 'email' => $email));
					}

					$this->email->from($this->data['siteinfos']->email, $this->data['siteinfos']->sname);
					$this->email->to($email);
					$this->email->subject('Restaurar Contraseña - usuario de Lesson');
					$message = '<body>
                            	<p>
                            	Nos han solicitado restablecer su contraseña, si realmente fue usted por favor haga 
								clic en el siguiente enlace:                         
                            	</p>
                            	</body>' .$tmp_url;
					$this->email->message($message);

					if($this->email->send()) {
						$this->session->set_flashdata('reset_send', 'Mensaje enviado.');
		    		} else {
		    			$this->session->set_flashdata('reset_error', 'Error al enviar mensaje.');
		    		}
				} else {
					$this->session->set_flashdata('reset_error', 'No existe este correo electrónico.');
				}

				$this->load->helper("url");
				redirect(base_url("reset/index"));
			}
		} else {
			$this->data["subview"] = 'reset/index';
			$this->load->view('_layout_reset', $this->data);
		}
	}

	public function password() {
		$this->load->model("reset_m");
		$this->load->library('session');
		$array = array();
		$i = 0;
		$key = $this->uri->segment(3);
		$this->data['siteinfos'] = $this->reset_m->get_site();

		if(!empty($key)) {
			$dbreset = $this->reset_m->get_reset(1);
			if(count($dbreset)) {
				if($key == $dbreset->keyID) {
					$this->data['form_validation'] = "No";
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data['form_validation'] = validation_errors();
							$this->data["subview"] = "reset/add";
							$this->load->view('_layout_reset', $this->data);
						} else {
							$password = $this->input->post('newpassword');
							$email = $dbreset->email;

							$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
							foreach ($tables as $table) {
								$dbuser = $this->reset_m->get_table_users($table, $email);
								if(count($dbuser)) {
									$data = array('password' => $this->reset_m->hash($password));
									$this->db->update($table, $data, "email = '".$email."'");
									$this->session->set_flashdata('reset_success', 'Se actualizó tu contraseña');
									$this->db->truncate('reset');
									$array['permition'][$i] = 'yes';
								} else {
									$array['permition'][$i] = 'no';
								}
								$i++;
							}

							if(in_array('yes', $array['permition'])) {
								redirect(base_url("signin/index"));
							}
						}
					} else {
						$this->data["subview"] = "reset/add";
						$this->load->view('_layout_reset', $this->data);
					}
				} else {
					echo "<p> El tiempo para restaurar la contraseña expiró. </p>";
				}
			} else {
				echo "<p> El tiempo para restaurar la contraseña expiró. </p>";
			}
		} else {
			echo "<p> El tiempo para restaurar la contraseña expiró. </p>";
		}
	}

}



/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */
