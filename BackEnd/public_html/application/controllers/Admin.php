<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function index()
	{
		echo "hello";
	}

    public function getAdmin()
    {
        $this->load->model('M_admin');
        $data= $this->M_admin->getData();

        $result = array(
            "success" => true,
            "message" => "data di temukan",
            "data" => $data
        );

        echo json_encode($result);
    }

    public function addData()
    {
        $this->load->model('M_admin');
        $insert = $this->M_admin->insertData;

        if($insert){
            $this->response($data, 200);
        } else{
            $this->response($data, 502);
        }
    }

}
