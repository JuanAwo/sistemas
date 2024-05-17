<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exam_type_m extends MY_Model {

    protected $_table_name = 'online_exam_type';
    protected $_primary_key = 'onlineExamTypeID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "examTypeNumber asc";

    function __construct() {
        parent::__construct();
    }

    function get_exam_type($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_exam_type($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_exam_type($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_exam_type($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_exam_type($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_exam_type($id){
        parent::delete($id);
    }
}
