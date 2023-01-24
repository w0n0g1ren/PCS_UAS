<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_item_transaksi extends CI_Model {
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function getData()
	{
		$this->db->select('item_transaksi.id,item_transaksi.transaksi_id,item_transaksi.produk_id,produk.nama,item_transaksi.qty,item_transaksi.harga_saat_transaksi,item_transaksi.sub_total');
		$this->db->from('item_transaksi');
		$this->db->join('produk', 'produk.id = item_transaksi.produk_id');
        $data = $this->db->get();
        return $data->result_array();
	}

	public function getItemTransaksiByTransaksiID($transaksi_id)
	{
		$this->db->select('item_transaksi.id,item_transaksi.transaksi_id,item_transaksi.produk_id,produk.nama,item_transaksi.qty,item_transaksi.harga_saat_transaksi,item_transaksi.sub_total');
		$this->db->from('item_transaksi');
		$this->db->join('produk', 'produk.id = item_transaksi.produk_id');
		$this->db->where('item_transaksi.transaksi_id', $transaksi_id);
        $data = $this->db->get();
        return $data->result_array();
	}
	
	function insertData($data)
	{
	
		$this->db->insert('item_transaksi',$data);
		
		//mengubah stok
		$result = $this->db->get_where('produk', array('id' => $data["produk_id"]));
		$result = $result->row_array();
		$stok_lama = $result["stok"];
		$stok_baru = $stok_lama - $data["qty"];

		$data_produk_update = array(
			"stok" => $stok_baru
		);

		$this->db->where('id',$data["produk_id"]);
		$this->db->update('produk',$data_produk_update);

	}

	public function updateItemTransaksi ($data,$id){
		$this->db->where('id',$id);
		$this->db->update('item_transaksi',$data);

		$result = $this->db->get_where('item_transaksi',array('id'=>$id));
		return $result->row_array();
	}

	public function deleteItemTransaksi($id){
		$result = $this->db->get_where('item_transaksi',array('id'=>$id));
		$this->db->where('id',$id);
		$this->db->delete('item_transaksi');

		return $result->row_array();
	}

	public function deleteItemTransaksibyTransaksiID($transaksi_id){
		$result = $this->db->get_where('item_transaksi',array('transaksi_id'=>$transaksi_id));
		$this->db->where('transaksi_id',$transaksi_id);
		$this->db->delete('item_transaksi');

		return $result->result_array();
	}

}

