<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Login_model', 'login');
    }

    public function index() {

        $post = json_decode(file_get_contents("php://input"));

        if (empty($post->email) || empty($post->senha)) {
            $this->output
                    ->set_status_header(400)
                    ->set_output(json_encode(array('status' => false, 'error' => 'Preencha todos os campos'), 
                            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $login = $this->login->get(array('email' => $post->email, 'senha' => $post->senha));
            if ($login) {
                $this->output
                        ->set_status_header(200)
                        ->set_output(json_encode(array('id' => $login->id, 'nome' => $login->nome, 'email' => $login->email,'token'=>$login->apikey), 
                                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            } else {
                $this->output
                        ->set_status_header(400)
                        ->set_output(json_encode(array('status' => false, 'error' => 'Usuário não encontrado'), 
                                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        }
    }

    public function cadastro() {
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post->email) || empty($post->senha) || empty($post->nome)) {
            $this->output
                    ->set_status_header(400)
                    ->set_output(json_encode(array('status' => false, 'error' => 'Preencha todos os campos'), 
                            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $insert = $this->login->insert(array('email' => $post->email, 'senha' => $post->senha, 'nome' => $post->nome));
            if ($insert > 0) {
                $newToken = md5('salt'.$insert);
                $this->login->insertApiKey(array('usuario_id' => $insert, 'apikey' =>$newToken));
                $this->output
                        ->set_status_header(200)
                        ->set_output(json_encode(
                                array(
                                    'id' => "$insert",
                                    'email' => $post->email,
                                    'nome' => $post->nome,
                                    'token' =>$newToken
                        ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            } else {
                $this->output
                        ->set_status_header(400)
                        ->set_output(json_encode(array('status' => false, 'error' => 'Falha no cadastro'), 
                                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        }
    }

}

?>