<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Contato extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Contato_Model', 'ct');
    }

    public function index_get() {
         $token = $this->input->get_request_header("token");
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->ct->get($token);
        } else {
            $data = $this->ct->getOne($id,$token);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }

    public function index_post() {
        if ((!$this->post('nome')) || (!$this->post('telefone')) || (!$this->post('usuario_id'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'nome' => $this->post('nome'),
            'telefone' => $this->post('telefone'),
            'usuario_id' => $this->post('usuario_id')
        );
        if ($this->ct->insert($data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Contato inserido com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao inserir Contato'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function index_delete() {
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $this->set_response([
                'status' => false,
                'error' => 'Parâmetros obrigatórios não fornecidos'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        if ($this->ct->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Contato deletado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar Contato'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function index_put() {
        $id = (int) $this->get('id');
        if ((!$this->put('nome')) || (!$this->put('telefone'))|| (!$this->put('usuario_id')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'nome' => $this->put('nome'),
            'telefone' => $this->put('telefone'),
            'usuario_id' => $this->put('usuario_id')
        );
        if ($this->ct->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Contato alterado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar Contato'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

}
