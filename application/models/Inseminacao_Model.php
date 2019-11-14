<?php

class Inseminacao_Model extends CI_Model {

    const table = 'inseminacao';

    public function getAll($apikey) {
        $this->db->select(self::table . '.*, ADDDATE(`data`,INTERVAL 222 DAY) AS secagem, ADDDATE(`data`,INTERVAL 259 DAY) AS pre_parto, ADDDATE(`data`,INTERVAL 282 DAY) AS previsao');
        $this->db->join('bovino', self::table . '.bovino_id = bovino.id', 'inner');
        $this->db->join('usuario', 'bovino.usuario_id = usuario.id', 'inner');
        $this->db->join('token', 'token.usuario_id = usuario.id', 'inner');
        $this->db->where(array('token.apikey' => $apikey));
        $query = $this->db->get(self::table);
        return $query->result();
    }

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

    public function getInseminacaoParto($apikey) {
        $query = $this->db->query("SELECT bovino.nome,bovino.id,
            (SELECT COUNT(*) FROM inseminacao WHERE bovino.id = inseminacao.bovino_id) AS `inseminacoes`,
            (SELECT COUNT(*) FROM partos WHERE bovino.`id` = partos.bovino_id AND nascido='1') AS `partos`,
            (SELECT COUNT(*) FROM partos WHERE bovino.`id` = partos.bovino_id AND nascido='0') AS `abortos`,
            (SELECT COUNT(*) FROM partos WHERE bovino.id = partos.bovino_id) AS `total` FROM inseminacao
            INNER JOIN bovino ON bovino.id = inseminacao.bovino_id
            LEFT  JOIN partos ON bovino.id = partos.bovino_id
            INNER JOIN usuario ON bovino.usuario_id = usuario.id
            INNER JOIN token ON token.usuario_id = `usuario`.id
            WHERE token.apikey = '" . $apikey . "' AND  inseminacao.`data` IS  NOT  NULL
            GROUP BY bovino.id ORDER BY inseminacoes DESC");
        //echo $this->db->last_query();exit;
        return $query->result();
    }

    public function getOneInseminacaoParto($id, $apikey) {
        if ($id > 0) {
            $query = $this->db->query("SELECT bovino.nome,bovino.id,
            (SELECT COUNT(*) FROM inseminacao WHERE bovino.id = inseminacao.bovino_id) AS `inseminacoes`,
            (SELECT COUNT(*) FROM partos WHERE bovino.`id` = partos.bovino_id AND nascido='1') AS `partos`,
            (SELECT COUNT(*) FROM partos WHERE bovino.`id` = partos.bovino_id AND nascido='0') AS `abortos`,
            (SELECT COUNT(*) FROM partos WHERE bovino.id = partos.bovino_id) AS `total` FROM inseminacao
            INNER JOIN bovino ON bovino.id = inseminacao.bovino_id
            LEFT  JOIN partos ON bovino.id = partos.bovino_id
            INNER JOIN usuario ON bovino.usuario_id = usuario.id
            INNER JOIN token ON token.usuario_id = `usuario`.id
            WHERE token.apikey = '" . $apikey . "' AND bovino.id=" . $id . " AND  inseminacao.`data` IS  NOT  NULL
            GROUP BY bovino.id ORDER BY inseminacoes DESC");
            //echo $this->db->last_query();exit;
            return $query->row(0);
        } else {
            return false;
        }
    }

}

?>