<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Usuario extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_Model', 'us');
    }

    public function index_get() {
        $id = (int) $this->get('id');
        if($id <= 0) {
            $data = $this->us->getAll();
        } else {
            $data = $this->us->getOne($id);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }

    public function index_post() {
        if ((!$this->post('nome')) || (!$this->post('email')) || (!$this->post('senha'))) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'nome' => $this->post('nome'),
            'email' => $this->post('email'),
            'senha' => $this->post('senha')
        );
        if ($this->us->insert($data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Usuário inserido com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao inserir Usuário'
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
        if ($this->us->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Usuário deletado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar Usuário'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

       public function index_put() {
        $id = (int) $this->get('id');
        if ((!$this->put('nome')) || (!$this->put('email')) || (!$this->put('senha')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo não preenchidos'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'nome' => $this->post('nome'),
            'email' => $this->put('email'),
            'senha' => $this->put('senha')
        );
        if ($this->us->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Usuaário alterado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar Usuário'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
}

?>