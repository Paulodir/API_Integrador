<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Bovino extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Bovino_Model', 'bov');
    }

    public function index() {
        $token = $this->input->get_request_header("token");
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->bov->getAll($token);
        } else {
            $data = $this->bov->getOne($id, $token);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }

    public function index_post() {
        if ((!$this->post('raca_id')) || (!$this->post('brinco')) || (!$this->post('nome')) || (!$this->post('nascimento')) || (!$this->post('peso')) || (!$this->post('usuario_id'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo(s) não preenchido(s)!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'raca_id' => $this->post('raca_id'),
            'brinco' => $this->post('brinco'),
            'nome' => $this->post('nome'),
            'nascimento' => $this->post('nascimento'),
            'peso' => $this->post('peso'),
            'usuario_id' => $this->post('usuario_id')
        );
        if ($this->bov->insert($data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de bovino inserido com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao inserir registro de bovino!'
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
        if ($this->bov->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de bovino deletado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar Registro de bovino!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function index_put() {
        $id = (int) $this->get('id');
        if ((!$this->put('raca_id')) || (!$this->put('brinco')) || (!$this->put('nome')) || (!$this->put('nascimento')) || (!$this->put('peso')) || (!$this->put('usuario_id')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo(s) não preenchido(s)!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'raca_id' => $this->put('raca_id'),
            'brinco' => $this->put('brinco'),
            'nome' => $this->put('nome'),
            'nascimento' => $this->put('nascimento'),
            'peso' => $this->put('peso'),
            'usuario_id' => $this->put('usuario_id')
        );
        if ($this->bov->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de bovino alterado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar registro de bovino!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
    public function ordenha() {
        $token = $this->input->get_request_header("token");
        $id = 1;
        if ($id <= 0) {
            $data = $this->bov->getAll($token);
        } else {
            $data = $this->bov->getOneOrdenha($id, $token);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }

}
