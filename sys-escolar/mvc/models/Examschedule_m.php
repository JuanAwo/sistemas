<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Classes_m.php";

class Examschedule_m extends MY_Model {

	protected $_table_name = 'examschedule';
	protected $_primary_key = 'examscheduleID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classesID asc";

	function __construct() {
		parent::__construct();
	}

	function get_classes() {
        $class = new Classes_m;
        return $class->get_order_by_classes();
	}

	function get_exam() {
		$this->db->select('*')->from('exam')->order_by('exam asc');
		$query = $this->db->get();
		return $query->result();
	}


	function get_subject($id) {
		$query = $this->db->get_where('subject', array('classesID' => $id));
		return $query->result();
	}

	function get_join_all($id, $schoolyearID) {
		$date = date("Y-m-d");
		$this->db->select('*');
		$this->db->from('examschedule');
		$this->db->where(array('examschedule.classesID' => $id, 'schoolyearID' => $schoolyearID))->order_by('edate');
		$this->db->join('exam', 'exam.examID = examschedule.examID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = examschedule.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = examschedule.sectionID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = examschedule.subjectID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_join_all_wsection($id, $sectionID, $schoolyearID) {
		$date = date("Y-m-d");
		$this->db->select('*');
		$this->db->from('examschedule');
		$this->db->where(array('examschedule.classesID' => $id, 'examschedule.sectionID' => $sectionID, 'schoolyearID' => $schoolyearID));
		$this->db->join('exam', 'exam.examID = examschedule.examID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = examschedule.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = examschedule.sectionID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = examschedule.subjectID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_examschedule($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_examschedule($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_examschedule($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_examschedule($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_examschedule($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_examschedule($id){
		parent::delete($id);
	}	

}

/* End of file examschedule_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/examschedule_m.php */