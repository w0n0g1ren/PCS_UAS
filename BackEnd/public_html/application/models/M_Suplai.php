<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Suplai extends CI_Model {
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function insertData($data)
	{
		
		$this->db->insert('suplai',$data);
		 $result = $this->db->get_where('produk', array('id' => $data["id_produk"]));
		$result = $result->row_array();
		$stok_lama = $result["stok"];
		$stok_baru = $stok_lama + $data["jumlah"];

		$data_produk_update = array(
			"stok" => $stok_baru
		);

		$this->db->where('id',$data["id_produk"]);
		$this->db->update('produk',$data_produk_update);
	}


}