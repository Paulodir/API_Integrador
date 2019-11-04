<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Partos extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Partos_Model', 'par');
    }

    public function index_get() {
        $token = $this->input->get_request_header("token");
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->par->getAll($token);
        } else {
            $data = $this->par->getOne($id, $token);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }

    public function index_post() {
        if ((!$this->post('bovino_id')) || (!$this->post('data')) || (!$this->post('nascido'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo(s) não preenchido(s)!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'bovino_id' => $this->post('bovino_id'),
            'data' => $this->post('data'),
            'nascido' => $this->post('nascido')
        );
        if ($this->par->insert($data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de partos inserido com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao inserir registro de partos!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function index_delete() {
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $this->set_response([
                'status' => false,
                'error' => 'Informações obrigatórias não fornecidas!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        if ($this->par->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de partos deletado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar registro de partos!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function index_put() {
        $id = (int) $this->get('id');
        if ((!$this->put('bovino_id')) || (!$this->put('data')) || (!$this->put('nascido')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo(s) não preenchido(s)!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'bovino_id' => $this->put('bovino_id'),
            'data' => $this->put('data'),
            'nascido' => $this->put('nascido')
        );
        if ($this->par->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de partos alterado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar registro de partos!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

}
