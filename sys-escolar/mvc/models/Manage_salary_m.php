<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_salary_m extends MY_Model {

    protected $_table_name = 'manage_salary';
    protected $_primary_key = 'manage_salaryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "manage_salaryID asc";

    function __construct() {
        parent::__construct();
    }

    function get_manage_salary($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_manage_salary($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_manage_salary($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_manage_salary($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_manage_salary($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_manage_salary($id){
        parent::delete($id);
    }
}
