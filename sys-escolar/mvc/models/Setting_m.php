<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_m extends MY_Model {

	protected $_table_name = 'setting';
	protected $_primary_key = 'fieldoption';
	protected $_primary_filter = 'intval';
	protected $_order_by = "option asc";

	function __construct() {
		parent::__construct();
	}


	function get_setting($id) {
		$compress = array();
		$query = $this->db->get('setting');
		foreach ($query->result() as $row) {
		    $compress[$row->fieldoption] = $row->value;
		}
		return (object) $compress;
	}

	function get_setting_array() {
		$compress = array();
		$query = $this->db->get('setting');
		foreach ($query->result() as $row) {
		    $compress[$row->fieldoption] = $row->value;
		}
		return $compress;
	}

	function get_setting_where($data) {
		$this->db->where('fieldoption', $data);
		$query = $this->db->get('setting');
		return $query->row();
	}

	function insertorupdate($arrays) {
		foreach ($arrays as $key => $array) {
			$this->db->query("INSERT INTO setting (fieldoption, value) VALUES ('".$key."', '".$array."') ON DUPLICATE KEY UPDATE fieldoption='".$key."' , value='".$array."'");
		}
		return TRUE;
	}

	public function delete_setting($optionname){
		$this->db->delete('setting', array('fieldoption' => $optionname));
		return TRUE;
	}

	public function insert_setting($array) {
		$this->db->insert('setting', $array);
		return TRUE; 
	}

	public function get_markpercentage() {
		$query = $this->db->query("SELECT * FROM setting WHERE fieldoption LIKE 'mark%' AND value=1");
		return $query->result();
	}

	// function get_order_by_setting($array=NULL) {
	// 	$query = parent::get_order_by($array);
	// 	return $query;
	// }

	// function insert_setting($array) {
	// 	$error = parent::insert($array);
	// 	return TRUE;
	// }

	// function update_setting($data, $id = NULL) {
	// 	parent::update($data, $id);
	// 	return $id;
	// }

	
}

/* End of file setting_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/setting_m.php */
