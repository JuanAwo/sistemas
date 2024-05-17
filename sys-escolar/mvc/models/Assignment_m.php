<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assignment_m extends MY_Model {

	protected $_table_name = 'assignment';
	protected $_primary_key = 'assignmentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "deadlinedate desc";

	function __construct() {
		parent::__construct();
	}

	function join_get_assignment($classesID, $schoolyearID) {
		$this->db->select('*');
		$this->db->from('assignment');
		$this->db->join('subject', 'subject.subjectID = assignment.subjectID AND subject.classesID = assignment.classesID', 'LEFT');
		$this->db->where('assignment.schoolyearID', $schoolyearID);
		$this->db->where('assignment.classesID', $classesID);
		$query = $this->db->get();
		return $query->result();
	}

	function get_assignment($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_assignment($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_assignment($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_assignment($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_assignment($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_assignment($id){
		parent::delete($id);
	}
}

/* End of file assignment_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/assignment_m.php */