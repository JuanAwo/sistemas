<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissionlog extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("permissionlog_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('permissionlog', $language);
        redirect('dashboard/index');
    }

    public function index() {
        $this->data['permissionlogs'] = $this->permissionlog_m->get_order_by_permissionlog();
        $this->data["subview"] = "permissionlog/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("permissionlog_name"),
                'rules' => 'trim|required|xss_clean|max_length[50]'
            ),
            array(
                'field' => 'name',
                'label' => $this->lang->line("permissionlog_name"),
                'rules' => 'trim|required|xss_clean|max_length[50]'
            ),
            array(
                'field' => 'name',
                'label' => $this->lang->line("permissionlog_name"),
                'rules' => 'trim|required|xss_clean|max_length[50]'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "/permissionlog/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "name" => $this->input->post("name"),
                    "description" => $this->input->post("description"),
                    "active" => $this->input->post("active")
                );
                $this->permissionlog_m->insert_permissionlog($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("permissionlog/index"));
            }
        } else {
            $this->data["subview"] = "/permissionlog/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['permissionlog'] = $this->permissionlog_m->get_single_permissionlog(array('permissionID' => $id));
            if($this->data['permissionlog']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/permissionlog/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "name" => $this->input->post("name"),
                            "description" => $this->input->post("description"),
                            "active" => $this->input->post("active")
                        );

                        $this->permissionlog_m->update_permissionlog($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("permissionlog/index"));
                    }
                } else {
                    $this->data["subview"] = "/permissionlog/edit";
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
            $this->data['permissionlog'] = $this->permissionlog_m->get_single_permissionlog(array('permissionID' => $id));
            if($this->data['permissionlog']) {
                $this->permissionlog_m->delete_permissionlog($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("permissionlog/index"));
            } else {
                redirect(base_url("permissionlog/index"));
            }
        } else {
            redirect(base_url("permissionlog/index"));
        }
    }
}
