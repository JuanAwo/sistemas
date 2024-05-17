<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_salary extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("manage_salary_m");
        $this->load->model("teacher_m");
        $this->load->model("user_m");
        $this->load->model("systemadmin_m");
        $this->load->model("salary_template_m");
        $this->load->model("hourly_template_m");
        $this->load->model("salaryoption_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('manage_salary', $language);
    }

    public function index() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );

        $this->data['roles'] = $this->usertype_m->get_usertype();
        $setrole = htmlentities(escapeString($this->uri->segment(3)));
        
        if(!isset($setrole)) {
            $setrole = 0;
            $this->data['setrole'] = $setrole;
        } else {
            $this->data['setrole'] = $setrole;
        }

        if($setrole == 1) {
            $this->data['users'] = $this->systemadmin_m->get_systemadmin();
            $this->data['managesalary'] = pluck($this->manage_salary_m->get_order_by_manage_salary(array('usertypeID' => 1)), 'userID');
         } elseif($setrole == 2) {
            $this->data['users'] = $this->teacher_m->get_teacher();
            $this->data['managesalary'] = pluck($this->manage_salary_m->get_order_by_manage_salary(array('usertypeID' => 2)), 'userID');
        } else {
            $this->data['users'] = $this->user_m->get_order_by_user(array('usertypeID' => $setrole));
            $this->data['managesalary'] = pluck($this->manage_salary_m->get_order_by_manage_salary(array('usertypeID' => $setrole)), 'userID');
        }

        $this->data["subview"] = "manage_salary/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'salary',
                'label' => $this->lang->line("manage_salary_salary"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_salary'
            ),
            array(
                'field' => 'template',
                'label' => $this->lang->line("manage_salary_template"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_template'
            )
        );
        return $rules;
    }

    public function add() {
        $userID     = htmlentities(escapeString($this->uri->segment(3)));
        $usertypeID = htmlentities(escapeString($this->uri->segment(4)));
        
        if((int)$userID && (int) $usertypeID) {
            
            if($usertypeID == 1) {
                $user = $this->systemadmin_m->get_single_systemadmin(array('usertypeID' => $usertypeID, 'systemadminID' => $userID));
            } elseif($usertypeID == 2) {
                $user = $this->teacher_m->get_single_teacher(array('usertypeID' => $usertypeID, 'teacherID' => $userID));
            } else {
                $user = $this->user_m->get_single_user(array('usertypeID' => $usertypeID, 'userID' => $userID));
            }

            if(count($user)) {
                $manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $usertypeID, 'userID' => $userID));
                if(!count($manageSalary)) {

                    $this->data['usertypeID'] = $usertypeID;

                    if($this->input->post('salary')) {
                        $salary = $this->input->post('salary');
                        $this->data['salaryID'] = $salary;
                        if($salary == 1) {
                            $this->data['alltemplate'] = $this->salary_template_m->get_salary_template();
                        } elseif($salary == 2) {
                            $this->data['alltemplate'] = $this->hourly_template_m->get_hourly_template();
                        }
                    } else {
                        $this->data['alltemplate'] = array();
                        $this->data['salaryID'] = 0;
                    }
                    
                    if($_POST) {
                        $rules = $this->rules();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $this->data['form_validation'] = validation_errors();
                            $this->data["subview"] = "manage_salary/add";
                            $this->load->view('_layout_main', $this->data);
                        } else {
                            $array = array(
                                'userID'            => $userID,
                                'usertypeID'        => $usertypeID,
                                'salary'            => $this->input->post("salary"),
                                'template'          => $this->input->post("template"),
                                'create_date'       => date("Y-m-d h:i:s"),
                                'modify_date'       => date("Y-m-d h:i:s"),
                                'create_userID'     => $this->session->userdata('loginuserID'),
                                'create_username'   => $this->session->userdata('username'),
                                'create_usertype'   => $this->session->userdata('usertype')
                            );
                            $this->manage_salary_m->insert_manage_salary($array);
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("manage_salary/index/$usertypeID"));
                        }
                    } else {
                        $this->data["subview"] = "manage_salary/add";
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

    public function edit() {
        $userID     = htmlentities(escapeString($this->uri->segment(3)));
        $usertypeID = htmlentities(escapeString($this->uri->segment(4)));
        
        if((int)$userID && (int) $usertypeID) {
            
            if($usertypeID == 1) {
                $user = $this->systemadmin_m->get_single_systemadmin(array('usertypeID' => $usertypeID, 'systemadminID' => $userID));
            } elseif($usertypeID == 2) {
                $user = $this->teacher_m->get_single_teacher(array('usertypeID' => $usertypeID, 'teacherID' => $userID));
            } else {
                $user = $this->user_m->get_single_user(array('usertypeID' => $usertypeID, 'userID' => $userID));
            }

            if(count($user)) {
                $this->data['usertypeID'] = $usertypeID;

                $manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $usertypeID, 'userID' => $userID));
                if(count($manageSalary)) {
                    $this->data['manage_salary'] = $manageSalary;
                    if($this->input->post('salary') || $manageSalary->salary) {
                        if($manageSalary->salary) {
                            $salary = $manageSalary->salary;
                        } else {
                            $salary = $this->input->post('salary');
                        }
                        $this->data['salaryID'] = $salary;
                        if($salary == 1) {
                            $this->data['alltemplate'] = $this->salary_template_m->get_salary_template();
                        } elseif($salary == 2) {
                            $this->data['alltemplate'] = $this->hourly_template_m->get_hourly_template();
                        }
                    } else {
                        $this->data['alltemplate'] = array();
                        $this->data['salaryID'] = 0;
                    }
                    
                    if($_POST) {
                        $rules = $this->rules();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $this->data['form_validation'] = validation_errors();
                            $this->data["subview"] = "manage_salary/edit";
                            $this->load->view('_layout_main', $this->data);
                        } else {
                            $array = array(
                                'salary'            => $this->input->post("salary"),
                                'template'          => $this->input->post("template"),
                                'modify_date'       => date("Y-m-d h:i:s"),
                            );

                            $this->manage_salary_m->update_manage_salary($array, $manageSalary->manage_salaryID);
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("manage_salary/index/$usertypeID"));
                        }
                    } else {
                        $this->data["subview"] = "manage_salary/edit";
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

    public function view() {
        $userID = htmlentities(escapeString($this->uri->segment(3)));
        $usertypeID = htmlentities(escapeString($this->uri->segment(4)));

        if((int)$userID && (int) $usertypeID) {
            
            $this->data['usertypeID'] = $usertypeID;
            $this->data['userID'] = $userID;

            if($usertypeID == 1) {
                $user = $this->systemadmin_m->get_single_systemadmin(array('usertypeID' => $usertypeID, 'systemadminID' => $userID));
            } elseif($usertypeID == 2) {
                $user = $this->teacher_m->get_single_teacher(array('usertypeID' => $usertypeID, 'teacherID' => $userID));
            } else {
                $user = $this->user_m->get_single_user(array('usertypeID' => $usertypeID, 'userID' => $userID));
            }

            if(count($user)) {
                $this->data['usertype'] = $this->usertype_m->get_usertype($user->usertypeID);
                $this->data['user'] = $user;
                $manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $usertypeID, 'userID' => $userID));
                if(count($manageSalary)) {
                    $this->data['manage_salary'] = $manageSalary;
                    if($manageSalary->salary == 1) {

                        $this->data['salary_template'] = $this->salary_template_m->get_single_salary_template(array('salary_templateID' => $manageSalary->template));
                        if($this->data['salary_template']) {

                            $this->db->order_by("salary_optionID", "asc");
                            $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salary_templateID' => $manageSalary->template));

                            $grosssalary = 0;
                            $totaldeduction = 0;
                            $netsalary = $this->data['salary_template']->basic_salary;
                            $grosssalarylist = array();
                            $totaldeductionlist = array();

                            if(count($this->data['salaryoptions'])) {
                                foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                    if($salaryOption->option_type == 1) {
                                        $netsalary += $salaryOption->label_amount;
                                        $grosssalary += $salaryOption->label_amount;
                                        $grosssalarylist[$salaryOption->label_name] = $salaryOption->label_amount;
                                    } elseif($salaryOption->option_type == 2) {
                                        $netsalary -= $salaryOption->label_amount;
                                        $totaldeduction += $salaryOption->label_amount;
                                        $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                                    }
                                }
                            }

                            $this->data['grosssalary'] = $grosssalary;
                            $this->data['totaldeduction'] = $totaldeduction;
                            $this->data['netsalary'] = $netsalary;

                            $this->data["subview"] = "manage_salary/view";
                            $this->load->view('_layout_main', $this->data);
                        } else {
                            $this->data["subview"] = "error";
                            $this->load->view('_layout_main', $this->data);
                        }
                    } elseif($manageSalary->salary == 2) {
                        $this->data['hourly_salary'] = $this->hourly_template_m->get_single_hourly_template(array('hourly_templateID'=> $manageSalary->template));
                        if(count($this->data['hourly_salary'])) {

                            $this->data['grosssalary'] = 0;
                            $this->data['totaldeduction'] = 0;
                            $this->data['netsalary'] = $this->data['hourly_salary']->hourly_rate;

                            $this->data["subview"] = "manage_salary/view";
                            $this->load->view('_layout_main', $this->data);
                        } else {
                            $this->data["subview"] = "error";
                            $this->load->view('_layout_main', $this->data);
                        }
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
        $userID = htmlentities(escapeString($this->uri->segment(3)));
        $usertypeID = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$userID && (int)$usertypeID) {

            if($usertypeID == 1) {
                $user = $this->systemadmin_m->get_single_systemadmin(array('usertypeID' => $usertypeID, 'systemadminID' => $userID));
            } elseif($usertypeID == 2) {
                $user = $this->teacher_m->get_single_teacher(array('usertypeID' => $usertypeID, 'teacherID' => $userID));
            } else {
                $user = $this->user_m->get_single_user(array('usertypeID' => $usertypeID, 'userID' => $userID));
            }

            if(count($user)) {
                $this->data['manage_salary'] = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $usertypeID, 'userID' => $userID));
                if($this->data['manage_salary']) {
                    $this->manage_salary_m->delete_manage_salary($this->data['manage_salary']->manage_salaryID);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("manage_salary/index/$usertypeID"));
                } else {
                    redirect(base_url("manage_salary/index"));
                }
            } else {
                redirect(base_url("manage_salary/index"));
            }
        } else {
            redirect(base_url("manage_salary/index"));
        }
    }

    public function role_list() {
        $role = $this->input->post('id');
        if((int)$role) {
            $string = base_url("manage_salary/index/$role");
            echo $string;
        } else {
            echo base_url("manage_salary/index");
        }
    }

    public function templatecall() {
        $salary = $this->input->post('salary');

        if($salary == 1) {
            $salaryTemplates = $this->salary_template_m->get_salary_template();
            if(count($salaryTemplates)) {
                echo '<option value="0">'.$this->lang->line('manage_salary_select_template').'</option>';
                foreach ($salaryTemplates as $salaryTemplateKey => $salaryTemplate) { 
                    echo '<option value="'.$salaryTemplate->salary_templateID.'">'.$salaryTemplate->salary_grades.'</option>';
                }
            } else {
                echo '<option value="0">'.$this->lang->line('manage_salary_select_template').'</option>';
            }
        } elseif($salary == 2) {
            $salaryTemplates = $this->hourly_template_m->get_hourly_template();
            if(count($salaryTemplates)) {
                echo '<option value="0">'.$this->lang->line('manage_salary_select_template').'</option>';
                foreach ($salaryTemplates as $salaryTemplateKey => $salaryTemplate) { 
                    echo '<option value="'.$salaryTemplate->hourly_templateID.'">'.$salaryTemplate->hourly_grades.'</option>';
                }
            } else {
                echo '<option value="0">'.$this->lang->line('manage_salary_select_template').'</option>';
            }
        }
    }

    public function unique_salary() {
        if($this->input->post('salary') == 0) {
            $this->form_validation->set_message("unique_salary", "%s es requerido");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_template() {
        if($this->input->post('template') == 0) {
            $this->form_validation->set_message("unique_template", "%s es requerido");
            return FALSE;
        }
        return TRUE;
    }

}
