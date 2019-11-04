<?php

class Inseminacao_Model extends CI_Model {

    const table = 'inseminacao';

    public function getOne($id, $apikey) {
        if ($id > 0) {
            $this->db->select(self::table . '.*, ADDDATE(`data`,INTERVAL 222 DAY) AS secagem, ADDDATE(`data`,INTERVAL 259 DAY) AS pre_parto, ADDDATE(`data`,INTERVAL 282 DAY) AS previsao');
            $this->db->join('bovino', self::table . '.bovino_id = bovino.id', 'inner');
            $this->db->join('usuario', 'bovino.usuario_id = usuario.id', 'inner');
            $this->db->join('token', 'token.usuario_id = usuario.id', 'inner');
            $this->db->where(array('token.apikey' => $apikey, (self::table) . '.id' => $id));
            $query = $this->db->get(self::table);
            return $query->row(0);
        } else {
            return false;
        }
    }

    public function getAll($apikey) {
        $this->db->select(self::table . '.*, ADDDATE(`data`,INTERVAL 222 DAY) AS secagem, ADDDATE(`data`,INTERVAL 259 DAY) AS pre_parto, ADDDATE(`data`,INTERVAL 282 DAY) AS previsao');
        $this->db->join('bovino', self::table . '.bovino_id = bovino.id', 'inner');
        $this->db->join('usuario', 'bovino.usuario_id = usuario.id', 'inner');
        $this->db->join('token', 'token.usuario_id = usuario.id', 'inner');
        $this->db->where(array('token.apikey' => $apikey));
        $query = $this->db->get(self::table);
        return $query->result();
    }

    public function insert($data = array()) {
        $this->db->insert(self::table, $data);
        return $this->db->affected_rows();
    }

    public function delete($id) {
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->delete(self::table);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }

    public function update($id, $data = array()) {
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(self::table, $data);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }

}

?>