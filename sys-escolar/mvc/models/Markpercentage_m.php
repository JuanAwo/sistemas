<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class markpercentage_m extends MY_Model {

	protected $_table_name = 'markpercentage';
	protected $_primary_key = 'markpercentageID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "markpercentageID asc";

	function __construct() {
		parent::__construct();
	}

    function change_order($column, $sort) {
        $this->_order_by    = $column.' '.$sort;
    }

	function get_markpercentage($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_markpercentage($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_markpercentage($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_markpercentage($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_markpercentage($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_markpercentage($id){
		parent::delete($id);
	}
}

/* End of file category_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/category_m.php */