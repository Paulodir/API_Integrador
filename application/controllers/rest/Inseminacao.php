<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Inseminacao extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Inseminacao_Model', 'ins');
    }

    public function index_get() {
        $token = $this->input->get_request_header("token");
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->ins->getAll($token);
        } else {
            $data = $this->ins->getOne($id, $token);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }

    public function index_post() {
        if ((!$this->post('bovino_id')) || (!$this->post('raca_id')) || (!$this->post('data'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo(s) não preenchido(s)!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'bovino_id' => $this->post('bovino_id'),
            'raca_id' => $this->post('raca_id'),
            'data' => $this->post('data')
        );
        if ($this->ins->insert($data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de inseminação inserido com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao inserir registro de inseminação!'
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
        if ($this->ins->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de inseminação deletado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar registro de inseminação!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function index_put() {
        $id = (int) $this->get('id');
        if ((!$this->put('bovino_id')) || (!$this->put('raca_id')) || (!$this->put('data')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo(s) não preenchido(s)!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'bovino_id' => $this->put('bovino_id'),
            'raca_id' => $this->put('raca_id'),
            'data' => $this->put('data')
        );
        if ($this->ins->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de inseminação alterado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar registro de inseminação!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

}
?>