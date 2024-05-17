<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Make_payment_m extends MY_Model {

    protected $_table_name = 'make_payment';
    protected $_primary_key = 'make_paymentID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "make_paymentID asc";

    function __construct() {
        parent::__construct();
    }

    function get_make_payment($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_make_payment($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_make_payment($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_make_payment($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_make_payment($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_make_payment($id){
        parent::delete($id);
    }
}
