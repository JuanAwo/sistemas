<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instruction_m extends MY_Model {

    protected $_table_name = 'instruction';
    protected $_primary_key = 'instructionID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "instructionID asc";

    function __construct() {
        parent::__construct();
    }

    function get_instruction($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_instruction($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_instruction($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_instruction($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_instruction($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_instruction($id){
        parent::delete($id);
    }
}
