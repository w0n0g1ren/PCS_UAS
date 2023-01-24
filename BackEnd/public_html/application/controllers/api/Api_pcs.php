<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/Firebase/JWT/JWT.php';
use \Firebase\JWT\JWT;


class Api_pcs extends REST_Controller {

    private $secret_key = "intinyabebasaja";

    function __construct(){
        parent::__construct();
        $this->load->model('M_admin');
        $this->load->model('M_produk');
        $this->load->model('M_distributor');
        $this->load->model('M_transaksi');
        $this->load->model('M_item_transaksi');
        $this->load->model('M_Suplai');
    }

	
// API ADMIN

    public function index_get()
    {
        $this->cekToken();
       
        $data= $this->M_admin->getData();

        $result = array(
            "success" => true,
            "message" => "data di temukan",
            "data" => $data
        );

        echo json_encode($result);
    }

    public function index_post()
    {
        $this->cekToken();

        $validation_message = [];
        if($this->input->post("email")==""){
            array_push($validation_message,"email tidak boleh kosong");
        }
        if($this->input->post("email")!="" && !filter_var($this->input->post("email"),FILTER_VALIDATE_EMAIL)){
            array_push($validation_message,"format email tidak valid");
        }
        if($this->input->post("password")==""){
            array_push($validation_message,"password tidak boleh kosong");
        }
        if($this->input->post("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }
        if(count($validation_message)>0){
            $data_json = array(
                "success" =>false,
                "message" =>"id tidak valid",
                "data" => $validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }
        
        $data = array(
            'email' => $this->post('email'),
            'password' => md5($this->post('password')),
            'nama' => $this->post('nama')
        );

        $insert = $this->M_admin->insertData($data);

        if($insert){
            $this->response($data, 200);
        }else{
            $this->response($data, 502);
            }
}

    public function admin_delete(){
        $this->cekToken();

        $id = $this->delete("id");
        $result = $this->M_admin->deleteAdmin($id);

        if(empty($result)){
            $data_json = array(
                "success" =>false,
                "message" =>"id tidak valid",
                "data" => null
            );
            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json = array(
            "success" =>true,
            "message" =>"delete berhasil",
            "data" => array(
                "admin" =>$result
            )
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function admin_put(){
       $this->cekToken();

        $validation_message = [];

        if($this->put("id")==""){
            array_push($validation_message,"id tidak boleh kosong");
        }
        if($this->put("email")==""){
            array_push($validation_message,"email tidak boleh kosong");
        }
        if($this->put("email")!="" && !filter_var($this->put("email"),FILTER_VALIDATE_EMAIL)){
            array_push($validation_message,"format email tidak valid");
        }
        if($this->put("password")==""){
            array_push($validation_message,"password tidak boleh kosong");
        }
        if($this->put("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }
        if(count($validation_message)>0){
            $data_json = array(
                "success" =>false,
                "message" =>"id tidak valid",
                "data" => $validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos
        $data = array(
            "email" => $this->put("email"),
            "password" =>md5($this->put("password")),
            "nama" =>$this->put("nama")
        );

        $id = $this->put("id");

        $result = $this->M_admin->updateAdmin($data,$id);

        $data_json = array(
                "success" =>true,
                "message" =>"update berhasil",
                "data" => array(
                    "admin" =>$result
                )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }



//API Produk

    public function produk_get()
    {
        $this->cekToken();
       
        $data= $this->M_produk->getData();

        $result = array(
            "success" => true,
            "message" => "data di temukan",
            "data" => $data
        );

        echo json_encode($result);
    }

    

    public function produk_post()
    {
        $this->cekToken();
        $validation_message = [];
        if($this->input->post("admin_id")==""){
            array_push($validation_message,"admin_id tidak boleh kosong");
        }
        if($this->input->post("admin_id")!="" && !$this->M_admin->cekAdminExist($this->input->post("admin_id"))){
            array_push($validation_message,"admin_id tidak ada");
        }
        if($this->input->post("distributor_id")==""){
            array_push($validation_message,"distributor_id tidak boleh kosong");
        }
        if($this->input->post("distributor_id")!="" && !$this->M_distributor->cekDistributorExist($this->input->post("distributor_id"))){
            array_push($validation_message,"distributor_id tidak ada");
        }
        if($this->input->post("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }
        if($this->input->post("harga")==""){
            array_push($validation_message,"harga tidak boleh kosong");
        }
        if($this->input->post("harga")!="" && !is_numeric($this->input->post("harga"))){
            array_push($validation_message,"harga Harus angka");
        }
        if($this->input->post("stok")==""){
            array_push($validation_message,"stok tidak boleh kosong");
        }
        if($this->input->post("stok")!="" && !is_numeric($this->input->post("stok"))){
            array_push($validation_message,"stok harus angka");
        }
        if(count($validation_message)>0){
            $data_json = array(
                "success" =>false,
                "message" =>"id tidak valid",
                "data" => $validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data = array(
            'admin_id' => $this->post('admin_id'),
            'distributor_id' => $this->post('distributor_id'),
            'nama' => $this->post('nama'),
            'harga' => $this->post('harga'),
            'stok' => $this->post('stok')
        );

        $insert = $this->M_produk->insertData($data);

        if($insert){
            $this->response($data, 200);
        }else{
            $this->response($data, 502);
            }
}

    public function produk_delete(){
        $this->cekToken();

        $id = $this->delete("id");
        $result = $this->M_produk->deleteProduk($id);

        if(empty($result)){
            $data_json = array(
                "success" =>false,
                "message" =>"id tidak valid",
                "data" => null
            );
            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json = array(
            "success" =>true,
            "message" =>"delete berhasil",
            "data" => array(
                "produk" =>$result
            )
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function produk_put(){
       $this->cekToken();

        $validation_message = [];
        if($this->put("id")==""){
            array_push($validation_message,"id tidak boleh kosong");
        }
        if($this->put("admin_id")==""){
            array_push($validation_message,"admin_id tidak boleh kosong");
        }
        if($this->put("admin_id")!="" && !$this->M_admin->cekAdminExist($this->put("admin_id"))){
            array_push($validation_message,"admin_id tidak ada");
        }
        if($this->put("distributor_id")==""){
            array_push($validation_message,"distributor_id tidak boleh kosong");
        }
        if($this->put("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }
        if($this->put("harga")==""){
            array_push($validation_message,"harga tidak boleh kosong");
        }
        if($this->put("harga")!="" && !is_numeric($this->put("harga"))){
            array_push($validation_message,"harga Harus angka");
        }
        if($this->put("stok")==""){
            array_push($validation_message,"stok tidak boleh kosong");
        }
        if($this->put("stok")!="" && !is_numeric($this->put("stok"))){
            array_push($validation_message,"stok harus angka");
        }
        if(count($validation_message)>0){
            $data_json = array(
                "success" =>false,
                "message" =>"id tidak valid",
                "data" => $validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos
        $data = array(
            'admin_id' => $this->put('admin_id'),
            'distributor_id' => $this->put('distributor_id'),
            'nama' => $this->put('nama'),
            'harga' => $this->put('harga'),
            'stok' => $this->put('stok')
        );

        $id = $this->put("id");

        $result = $this->M_produk->updateProduk($data,$id);

        $data_json = array(
                "success" =>true,
                "message" =>"update berhasil",
                "data" => array(
                    "admin" =>$result
                )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }



//API Distributor
    public function distributor_get()
    {
        $this->cekToken();
       
        $data= $this->M_distributor->getData();

        $result = array(
            "success" => true,
            "message" => "data di temukan",
            "data" => $data
        );

        echo json_encode($result);
    }

    public function distributor_post()
    {
        $this->cekToken();
        $data = array(
            'nama_perusahaan' => $this->post('nama_perusahaan')
        );

        $insert = $this->M_distributor->insertData($data);

        if($insert){
            $this->response($data, 200);
        }else{
            $this->response($data, 502);
            }
}

    public function distributor_delete(){
        $this->cekToken();

        $id = $this->delete("id");
        $result = $this->M_distributor->deleteDistributor($id);

        if(empty($result)){
            $data_json = array(
                "success" =>false,
                "message" =>"id tidak valid",
                "data" => null
            );
            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        $data_json = array(
            "success" =>true,
            "message" =>"delete berhasil",
            "data" => array(
                "produk" =>$result
            )
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function distributor_put(){
       $this->cekToken();

        $validation_message = [];
        if($this->put("id")==""){
            array_push($validation_message,"id tidak boleh kosong");
        }
        if($this->put("nama_perusahaan")==""){
            array_push($validation_message,"nama_perusahaan tidak boleh kosong");
        }
       
        if(count($validation_message)>0){
            $data_json = array(
                "success" =>false,
                "message" =>"id tidak valid",
                "data" => $validation_message
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }

        //jika lolos
        $data = array(
            "nama_perusahaan" => $this->put("nama_perusahaan")
        );

        $id = $this->put("id");

        $result = $this->M_distributor->updateDistributor($data,$id);

        $data_json = array(
                "success" =>true,
                "message" =>"update berhasil",
                "data" => array(
                    "admin" =>$result
                )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }



// API Transaksi


public function transaksi_get()
{
    $this->cekToken();
   
    $data= $this->M_transaksi->getData();

    $result = array(
        "success" => true,
        "message" => "data di temukan",
        "data" => $data
    );

    echo json_encode($result);
}

public function transaksi_bulan_ini_get()
{
    $this->cekToken();
   
    $data= $this->M_transaksi->getDataTransaksiBulanIni();

    $result = array(
        "success" => true,
        "message" => "data di temukan",
        "data" => $data
    );

    echo json_encode($result);
}

public function transaksi_post()
{
    $this->cekToken();
    $validation_message = [];
    if($this->input->post("admin_id")==""){
        array_push($validation_message,"admin_id tidak boleh kosong");
    }
    if($this->input->post("admin_id")!="" && !$this->M_admin->cekAdminExist($this->input->post("admin_id"))){
        array_push($validation_message,"admin_id tidak ada");
    }
    if($this->input->post("total")==""){
        array_push($validation_message,"total tidak boleh kosong");
    }
    if($this->input->post("total")!="" && !is_numeric($this->input->post("total"))){
        array_push($validation_message,"total Harus angka");
    }
    if(count($validation_message)>0){
        $data_json = array(
            "success" =>false,
            "message" =>"id tidak valid",
            "data" => $validation_message
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
    }

    $data = array(
        'admin_id' => $this->post('admin_id'),
        'tanggal' => date("Y-m-d H:i:s"),
        'total' => $this->post('total')
    );

    $insert = $this->M_transaksi->insertData($data);

    if($insert){
        $this->response($data, 200);
    }else{
        $this->response($data, 502);
        }
}

public function transaksi_delete(){
    $this->cekToken();

    $id = $this->delete("id");
    $result = $this->M_transaksi->deleteTransaksi($id);

    if(empty($result)){
        $data_json = array(
            "success" =>false,
            "message" =>"id tidak valid",
            "data" => null
        );
        $this->response($data_json,REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
    }

    $data_json = array(
        "success" =>true,
        "message" =>"delete berhasil",
        "data" => array(
            "produk" =>$result
        )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
}

public function transaksi_put(){
   $this->cekToken();

    $validation_message = [];
    if($this->put("id")==""){
        array_push($validation_message,"id tidak boleh kosong");
    }
    if($this->put("admin_id")==""){
        array_push($validation_message,"admin_id tidak boleh kosong");
    }
    if($this->put("admin_id")!="" && !$this->M_admin->cekAdminExist($this->put("admin_id"))){
        array_push($validation_message,"admin_id tidak ada");
    }
    if($this->put("total")==""){
        array_push($validation_message,"total tidak boleh kosong");
    }
    if($this->put("total")!="" && !is_numeric($this->put("total"))){
        array_push($validation_message,"total Harus angka");
    }
   
    if(count($validation_message)>0){
        $data_json = array(
            "success" =>false,
            "message" =>"id tidak valid",
            "data" => $validation_message
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
    }

    //jika lolos
    $data = array(
        'admin_id' => $this->put('admin_id'),
        'tanggal' => date("Y-m-d H:i:s"),
        'total' => $this->put('total')
    );

    $id = $this->put("id");

    $result = $this->M_transaksi->updateTransaksi($data,$id);

    $data_json = array(
            "success" =>true,
            "message" =>"update berhasil",
            "data" => array(
                "admin" =>$result
            )
    );

    $this->response($data_json,REST_Controller::HTTP_OK);
}


//API Item_transaksi


public function item_transaksi_get()
{
    $this->cekToken();
   
    $data= $this->M_item_transaksi->getData();

    $result = array(
        "success" => true,
        "message" => "data di temukan",
        "data" => $data
    );

    echo json_encode($result);
}

public function item_transaksi_by_transaksi_id_get()
{
    $this->cekToken();
   
    $data= $this->M_item_transaksi->getItemTransaksiByTransaksiID($this->input->get('transaksi_id'));

    $result = array(
        "success" => true,
        "message" => "data di temukan",
        "data" => $data
    );

    echo json_encode($result);
}

// public function transaksi_bulan_ini_get()
// {
//     $this->cekToken();
   
//     $data= $this->M_transaksi->getDataTransaksiBulanIni();

//     $result = array(
//         "success" => true,
//         "message" => "data di temukan",
//         "data" => $data
//     );

//     echo json_encode($result);
// }

public function item_transaksi_post()
{
    $this->cekToken();
    $validation_message = [];
    if($this->input->post("transaksi_id")==""){
        array_push($validation_message,"transaksi_id tidak boleh kosong");
    }
    if($this->input->post("transaksi_id")!="" && !$this->M_transaksi->cekTransaksiExist($this->input->post("transaksi_id"))){
        array_push($validation_message,"transaksi_id tidak ada");
    }
    if($this->input->post("produk_id")==""){
        array_push($validation_message,"produk_id tidak boleh kosong");
    }
    if($this->input->post("produk_id")!="" && !$this->M_produk->cekProdukExist($this->input->post("produk_id"))){
        array_push($validation_message,"produk_id tidak ada");
    }
    if($this->input->post("qty")==""){
        array_push($validation_message,"qty tidak boleh kosong");
    }
    if($this->input->post("qty")!="" && !is_numeric($this->input->post("qty"))){
        array_push($validation_message,"qty Harus angka");
    }
    if($this->input->post("harga_saat_transaksi")==""){
        array_push($validation_message,"harga_saat_transaksi tidak boleh kosong");
    }
    if($this->input->post("harga_saat_transaksi")!="" && !is_numeric($this->input->post("harga_saat_transaksi"))){
        array_push($validation_message,"harga_saat_transaksi Harus angka");
    }
    if(count($validation_message)>0){
        $data_json = array(
            "success" =>false,
            "message" =>"id tidak valid",
            "data" => $validation_message
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
    }

    $data = array(
        'transaksi_id' => $this->post('transaksi_id'),
        'produk_id' => $this->post('produk_id'),
        'qty' => $this->post('qty'),
        'harga_saat_transaksi' => $this->post('harga_saat_transaksi'),
        'sub_total' => $this->post('qty') * $this->post('harga_saat_transaksi')
    );

    $insert = $this->M_item_transaksi->insertData($data);

    if($insert){
        $this->response($data, 200);
    }else{
        $this->response($data, 502);
        }
}


public function item_transaksi_by_transaksi_id_delete(){
    $this->cekToken();

    $transaksi_id = $this->delete("transaksi_id");
    $result = $this->M_item_transaksi->deleteItemTransaksibyTransaksiID($transaksi_id);

    if(empty($result)){
        $data_json = array(
            "success" =>false,
            "message" =>"id tidak valid",
            "data" => null
        );
        $this->response($data_json,REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
    }

    $data_json = array(
        "success" =>true,
        "message" =>"delete berhasil",
        "data" => array(
            "produk" =>$result
        )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
}


public function item_transaksi_delete(){
    $this->cekToken();

    $id = $this->delete("id");
    $result = $this->M_item_transaksi->deleteItemTransaksi($id);

    if(empty($result)){
        $data_json = array(
            "success" =>false,
            "message" =>"id tidak valid",
            "data" => null
        );
        $this->response($data_json,REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
    }

    $data_json = array(
        "success" =>true,
        "message" =>"delete berhasil",
        "data" => array(
            "produk" =>$result
        )
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
}

public function item_transaksi_put(){
   $this->cekToken();

    $validation_message = [];
    if($this->put("id")==""){
        array_push($validation_message,"id tidak boleh kosong");
    }
    if($this->put("transaksi_id")==""){
        array_push($validation_message,"transaksi_id tidak boleh kosong");
    }
    if($this->put("transaksi_id")!="" && !$this->M_transaksi->cekTransaksiExist($this->put("transaksi_id"))){
        array_push($validation_message,"transaksi_id tidak ada");
    }
    if($this->put("produk_id")==""){
        array_push($validation_message,"produk_id tidak boleh kosong");
    }
    if($this->put("produk_id")!="" && !$this->M_produk->cekProdukExist($this->put("produk_id"))){
        array_push($validation_message,"produk_id tidak ada");
    }
    if($this->put("qty")==""){
        array_push($validation_message,"qty tidak boleh kosong");
    }
    if($this->put("qty")!="" && !is_numeric($this->put("qty"))){
        array_push($validation_message,"qty Harus angka");
    }
    if($this->put("harga_saat_transaksi")==""){
        array_push($validation_message,"harga_saat_transaksi tidak boleh kosong");
    }
    if($this->put("harga_saat_transaksi")!="" && !is_numeric($this->put("harga_saat_transaksi"))){
        array_push($validation_message,"harga_saat_transaksi Harus angka");
    }
   
    if(count($validation_message)>0){
        $data_json = array(
            "success" =>false,
            "message" =>"id tidak valid",
            "data" => $validation_message
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
    }

    //jika lolos
    $data = array(
        'transaksi_id' => $this->put('transaksi_id'),
        'produk_id' => $this->put('produk_id'),
        'qty' => $this->put('qty'),
        'harga_saat_transaksi' => $this->put('harga_saat_transaksi'),
        'sub_total' => $this->put('qty') * $this->put('harga_saat_transaksi')
    );

    $id = $this->put("id");

    $result = $this->M_item_transaksi->updateItemTransaksi($data,$id);

    $data_json = array(
            "success" =>true,
            "message" =>"update berhasil",
            "data" => array(
                "data" =>$result
            )
    );

    $this->response($data_json,REST_Controller::HTTP_OK);
}



// API Suplai

public function suplai_post()
{
   
    $validation_message = [];
    if($this->input->post("id_produk")==""){
        array_push($validation_message,"produk_id tidak boleh kosong");
    }
    if($this->input->post("id_produk")!="" && !$this->M_produk->cekProdukExist($this->input->post("id_produk"))){
        array_push($validation_message,"produk_id tidak ada");
    }
    if($this->input->post("id_distributor")==""){
        array_push($validation_message,"id_distributor  tidak boleh kosong");
    }
    if($this->input->post("id_distributor")!="" && !$this->M_distributor->cekDistributorExist($this->input->post("id_distributor"))){
        array_push($validation_message,"id_distributor tidak ada");
    }
    if($this->input->post("harga")==""){
        array_push($validation_message,"harga tidak boleh kosong");
    }
    if($this->input->post("harga")!="" && !is_numeric($this->input->post("harga"))){
        array_push($validation_message,"harga Harus angka");
    }
    if($this->input->post("jumlah")==""){
        array_push($validation_message,"harga_saat_transaksi tidak boleh kosong");
    }
    if($this->input->post("jumlah")!="" && !is_numeric($this->input->post("jumlah"))){
        array_push($validation_message,"harga_saat_transaksi Harus angka");
    }
    if(count($validation_message)>0){
        $data_json = array(
            "success" =>false,
            "message" =>"id tidak valid",
            "data" => $validation_message
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
    }

    $data = array(
        
        'id_produk' => $this->post('id_produk'),
        'total' => $this->post('harga') * $this->post('jumlah'),
        'jumlah' => $this->post('jumlah'),
        'id_distributor' =>  $this->post('id_distributor')
    );

    $insert = $this->M_Suplai->insertData($data);

    if($insert){
        $this->response($data, 200);
    }else{
        $this->response($data, 502);
        }
}

//API Login
    public function cekToken(){
        try{
            $token = $this->input->get_request_header('Authorization');

            if(!empty($token)){
                $token = explode(' ',$token)[1];
            }

            $token_decode = JWT::decode($token,$this->secret_key,array('HS256'));

        } catch (Exception $e){
            $data_json = array(
                "success" => false,
                "message" => "Token tidak valid",
                "error_code"=> 1204,
                "data" => null
            );

            $this->response ($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }
    }




    public function login_post(){
        $data = array(
            "email" => $this->input->post("email"),
            "password" => md5($this->input->post("password"))
        );

        $result = $this->M_admin->cekLoginAdmin($data);

        if(empty($result)){
            $data_json = array(
            "success" => false,
                "message" => "email dan password tidak valid",
                "error_code"=> 1308,
                "data" => null
            );

            $this->response ($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
            
        }else{
            $date = new Datetime();

            $payload ["id"] = $result["id"];
            $payload ["email"] = $result["email"];
            $payload ["iat"] = $date->getTimestamp();
            $payload ["exp"] = $date->getTimestamp() + 3600;

            $data_json = array(
                "success" => true,
                    "message" => "autentikasi berhasil",
                    "data" => array(
                        "admin" => $result,
                        "token" => JWT::encode($payload,$this->secret_key)
                    )
            );
            $this->response ($data_json,REST_Controller::HTTP_OK);
        }
        
    }

}
