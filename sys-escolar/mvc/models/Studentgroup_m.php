<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentgroup_m extends MY_Model {

    protected $_table_name = 'studentgroup';
    protected $_primary_key = 'studentgroupID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "studentgroupID asc";

    function __construct() {
        parent::__construct();
    }

    function get_studentgroup($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_studentgroup($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_studentgroup($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_studentgroup($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_studentgroup($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_studentgroup($id){
        parent::delete($id);
    }
}
