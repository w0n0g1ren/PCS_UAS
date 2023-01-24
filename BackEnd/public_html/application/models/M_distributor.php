<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_distributor extends CI_Model {
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function getData()
	{
		
        $data = $this->db->get('distributor');
        return $data->result_array();
	}
	
	function insertData($data)
	{
		
		$this->db->insert('distributor',$data);
	}

	public function updateDistributor ($data,$id){
		$this->db->where('id',$id);
		$this->db->update('distributor',$data);

		$result = $this->db->get_where('distributor',array('id'=>$id));
		return $result->row_array();
	}

	public function deleteDistributor($id){
		$result = $this->db->get_where('distributor',array('id'=>$id));
		$this->db->where('id',$id);
		$this->db->delete('distributor');

		return $result->row_array();
	}

	public function cekDistributorExist($id){

		$data = array(
			"id" =>$id
		);

		$this->db->where($data);
		$result = $this ->db->get('distributor');

		if(empty($result->row_array())){
			return false;
		}
		return true;
	}

}

