<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_transaksi extends CI_Model {
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function getData()
	{
		$this->db->select('transaksi.id,transaksi.admin_id,admin.nama as nama_admin,transaksi.tanggal,transaksi.total');
		$this->db->from('transaksi');
		$this->db->join('admin', 'admin.id = transaksi.admin_id');
        $data = $this->db->get();
        return $data->result_array();
	}

	public function getDataTransaksiBulanIni()
	{
		$this->db->select('transaksi.id,transaksi.admin_id,admin.nama as nama_admin,transaksi.tanggal,transaksi.total');
		$this->db->from('transaksi');
		$this->db->join('admin', 'admin.id = transaksi.admin_id');
		$this->db->where('month(tanggal)', date('m'));
        $data = $this->db->get();
        return $data->result_array();
	}
	
	function insertData($data)
	{
		
		$this->db->insert('transaksi',$data);
	}

	public function updateTransaksi ($data,$id){
		$this->db->where('id',$id);
		$this->db->update('transaksi',$data);

		$result = $this->db->get_where('transaksi',array('id'=>$id));
		return $result->row_array();
	}

	public function deleteTransaksi($id){
		$result = $this->db->get_where('transaksi',array('id'=>$id));
		$this->db->where('id',$id);
		$this->db->delete('transaksi');

		return $result->row_array();
	}

	public function cekTransaksiExist($id){

		$data = array(
			"id" => $id
		);

		$this->db->where($data);
		$result = $this ->db->get('transaksi');

		if(empty($result->row_array())){
			return false;
		}
		return true;
	}

}

