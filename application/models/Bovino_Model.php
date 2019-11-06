<?php

class Bovino_Model extends CI_Model {

    const table = 'bovino';

    public function getAll($apikey) {
        $this->db->select(self::table . '.*');
        $this->db->join('usuario', self::table . '.usuario_id = usuario.id', 'inner');
        $this->db->join('token', 'token.usuario_id = usuario.id', 'inner');
        $this->db->where(array('token.apikey' => $apikey));
        $query = $this->db->get(self::table);
        return $query->result();
    }

    public function getOne($id, $apikey) {
        if ($id > 0) {
            $this->db->select(self::table . '.*');
            $this->db->join('usuario', self::table . '.usuario_id = usuario.id', 'inner');
            $this->db->join('token', 'token.usuario_id = usuario.id', 'inner');
            $this->db->where(array('token.apikey' => $apikey, (self::table) . '.id' => $id));
            $query = $this->db->get(self::table);
            //echo $this->db->last_query();exit;
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

    public function getOneOrdenha($id, $apikey) {
        if ($id > 0) {
            $this->db->select(self::table . ".brinco, SUM(leite) AS 'Leite Ordenhado',SUM(descarte) AS 'Leite Descartado',(SUM(leite) - SUM(descarte)) AS total, AVG(leite) AS 'Média Ordenha', AVG(descarte) AS 'Média Descarte'");
            $this->db->join('ordenha', self::table . '.id = ordenha.bovino_id ' . " AND coleta BETWEEN '2000-01-01' AND '2000-01-31'", 'inner');
            $this->db->join('usuario', self::table . '.usuario_id = usuario.id', 'inner');
            $this->db->join('token', 'token.usuario_id = usuario.id', 'inner');
            $this->db->where(array('token.apikey' => $apikey, (self::table) . '.id' => $id));
            $query = $this->db->get(self::table);
            //echo $this->db->last_query();exit;
            return $query->row(0);
        } else {
            return false;
        }
    }

}

?>