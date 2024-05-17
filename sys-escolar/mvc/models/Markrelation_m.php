<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class markrelation_m extends MY_Model {

    protected $_table_name = 'markrelation';
    protected $_primary_key = 'markrelationID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "markrelationID asc";

    function __construct() {
        parent::__construct();
    }

    function change_order($column, $sort) {
        $this->_order_by    = $column.' '.$sort;
    }

    function get_markrelation($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_order_by_markrelation($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function get_single_markrelation($array=NULL) {
        $query = parent::get_single($array);
        return $query;
    }

    function insert_markrelation($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_markrelation($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    function update_mark_with_condition($array, $id) {
		$this->db->update($this->_table_name, $array, $id);
		return $this->db->affected_rows();
	}

    public function delete_markrelation($id){
        parent::delete($id);
    }

    public function get_all_mark_with_relation($classID, $yearID)
    {
        $this->db->select('*');
		$this->db->from('mark m');
		$this->db->join('markrelation mr', 'm.markID = mr.markID', 'LEFT');
		$this->db->where('m.classesID', $classID);
		$this->db->where('m.schoolyearID', $yearID);
		$query = $this->db->get();
		return $query->result();
    }
}

/* End of file markrelation_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/markrelation_m.php */
