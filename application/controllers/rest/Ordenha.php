<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Ordenha extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Ordenha_Model', 'ord');
    }

    public function index_get() {
        $token = $this->input->get_request_header("token");
        $id = (int) $this->get('id');
        if ($id <= 0) {
            $data = $this->ord->getAll($token);
        } else {
            $data = $this->ord->getOne($id, $token);
        }
        $this->set_response($data, REST_Controller_Definitions::HTTP_OK);
    }

    public function index_post() {
        if ((!empty($this->post('bovino_id'))) && (!empty($this->post('leite'))) && (($this->post('descarte')) != '') && (!empty($this->post('coleta')))) {
            $data = array(
                'bovino_id' => $this->post('bovino_id'),
                'leite' => $this->post('leite'),
                'descarte' => $this->post('descarte'),
                'coleta' => $this->post('coleta')
            );
            if ($this->ord->insert($data)) {
                $this->set_response([
                    'status' => true,
                    'message' => 'Registro de ordenha inserido com successo!'
                        ], REST_Controller_Definitions::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => false,
                    'error' => 'Falha ao inserir registro de ordenha!'
                        ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            }
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Campo(s) não preenchido(s)!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
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
        if ($this->ord->delete($id)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de ordenha deletado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao deletar registro de ordenha!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function index_put() {
        $id = (int) $this->get('id');
        if ((!$this->put('bovino_id')) || (!$this->put('leite')) || (!$this->put('descarte')) || (!$this->put('coleta')) || ($id <= 0)) {
            $this->set_response([
                'status' => false,
                'error' => 'Campo(s) não preenchido(s)!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $data = array(
            'bovino_id' => $this->put('bovino_id'),
            'leite' => $this->put('leite'),
            'descarte' => $this->put('descarte'),
            'coleta' => $this->put('coleta')
        );
        if ($this->ord->update($id, $data)) {
            $this->set_response([
                'status' => true,
                'message' => 'Registro de ordenha alterado com successo!'
                    ], REST_Controller_Definitions::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'error' => 'Falha ao alterar registro de ordenha!'
                    ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

}
