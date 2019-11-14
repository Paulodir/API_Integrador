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

    public function getOrdenha($apikey, $inicio, $fim) {
        $this->db->select(self::table . ".brinco, SUM(leite) AS 'Leite Ordenhado',SUM(descarte) AS 'Leite Descartado',(SUM(leite) - SUM(descarte)) AS total, AVG(leite) AS 'Média Ordenha', AVG(descarte) AS 'Média Descarte'");
        $this->db->join('ordenha', self::table . ".id = ordenha.bovino_id AND coleta BETWEEN '" . $inicio . "' AND '" . $fim . "'", 'inner');
        $this->db->join('usuario', self::table . '.usuario_id = usuario.id', 'inner');
        $this->db->join('token', 'token.usuario_id = usuario.id', 'inner');
        $this->db->where(array('token.apikey' => $apikey));
        $this->db->group_by('bovino.id');
        $query = $this->db->get(self::table);
        //echo $this->db->last_query();exit;
        return $query->result();
    }

    public function getOneOrdenha($id, $apikey, $inicio, $fim) {
        if ($id > 0) {
            $this->db->select(self::table . ".brinco, COALESCE (bovino.nome,'Vaca') As nome, SUM(leite) AS 'Leite Ordenhado',SUM(descarte) AS 'Leite Descartado',(SUM(leite) - SUM(descarte)) AS total, AVG(leite) AS 'Média Ordenha', AVG(descarte) AS 'Média Descarte'");
            $this->db->join('ordenha', self::table . ".id = ordenha.bovino_id AND coleta BETWEEN '" . $inicio . "' AND '" . $fim . "'", 'inner');
            $this->db->join('usuario', self::table . '.usuario_id = usuario.id', 'inner');
            $this->db->join('token', 'token.usuario_id = usuario.id', 'inner');
            $this->db->where(array('token.apikey' => $apikey, (self::table) . '.id' => $id));
            $this->db->group_by('bovino.id');
            $query = $this->db->get(self::table);
            //echo $this->db->last_query();exit;
            return $query->row(0);
        } else {
            return false;
        }
    }

    public function getRacao($apikey) {
        $query = $this->db->query("SELECT bovino.id,bovino.brinco,bovino.nome,bovino.usuario_id,
            (SELECT mediaproducao.media FROM mediaproducao WHERE id=bovino.id)
            mediaproducao,alimentacao.racao FROM alimentacao, bovino, token 
            WHERE minimo <=(SELECT mediaproducao.media FROM mediaproducao WHERE id=bovino.id) 
            AND maximo>=(SELECT mediaproducao.media FROM mediaproducao WHERE id=bovino.id)
            AND`token`.`apikey` = '" . $apikey . "' AND bovino.usuario_id=token.usuario_id
            GROUP BY bovino.id");
        //echo $this->db->last_query();exit;
        return $query->result();
    }

    public function getOneRacao($id, $apikey) {
        if ($id > 0) {
            $query = $this->db->query("SELECT bovino.id,bovino.brinco,bovino.nome,bovino.usuario_id,
            (SELECT mediaproducao.media FROM mediaproducao WHERE id=bovino.id)
            mediaproducao,alimentacao.racao FROM alimentacao, bovino, token 
            WHERE minimo <=(SELECT mediaproducao.media FROM mediaproducao WHERE id=bovino.id) 
            AND maximo>=(SELECT mediaproducao.media FROM mediaproducao WHERE id=bovino.id)
            AND`token`.`apikey` = '" . $apikey . "' AND bovino.id=" . $id . " GROUP BY bovino.id");
            //echo $this->db->last_query();exit;
            return $query->row(0);
        } else {
            return false;
        }
    }

    public function getRelatorio($apikey) {
        $query = $this->db->query("SELECT DISTINCT bovino.nome, bovino.id,
            (SELECT COALESCE (MAX(`data`),'N/A') FROM partos WHERE bovino.`id` = partos.`bovino_id`) AS 'Ultimo Parto',
            (SELECT MAX(`data`) FROM inseminacao WHERE bovino.`id` = inseminacao.`bovino_id`) AS 'Ultima Inseminação',
            IF (((SELECT MAX(`data`) FROM partos WHERE bovino.`id` = partos.`bovino_id`) <(SELECT MAX(`data`) FROM inseminacao WHERE bovino.`id` = inseminacao.`bovino_id`))
            OR ((SELECT MAX(`data`) FROM partos WHERE bovino.`id` = partos.`bovino_id`) IS NULL),'Prenha', 'Em espera') AS `STATUS`,
            CASE
            WHEN (SELECT MAX(`data`) FROM partos WHERE bovino.`id` = partos.`bovino_id`)>(SELECT MAX(`data`) FROM inseminacao WHERE bovino.`id` = inseminacao.`bovino_id`)
            THEN DATEDIFF ((SELECT MAX(`data`) FROM partos WHERE bovino.`id` = partos.`bovino_id`),
            (SELECT MAX(`data`) FROM inseminacao WHERE bovino.`id` = inseminacao.`bovino_id`))
            ELSE '0' END AS vazia
            FROM bovino
            LEFT  JOIN partos ON bovino.`id` = partos.`bovino_id`
            INNER JOIN inseminacao ON bovino.`id` = inseminacao.`bovino_id`
            INNER JOIN `usuario` ON `bovino`.`usuario_id` = `usuario`.`id`
            INNER JOIN `token` ON `token`.`usuario_id` = `usuario`.`id`
            WHERE `token`.`apikey` = '" . $apikey . "' AND  inseminacao.`data` IS  NOT  NULL
            ORDER BY vazia DESC");
        //echo $this->db->last_query();exit;
        return $query->result();
    }

    public function getOneRelatorio($id, $apikey) {
        if ($id > 0) {
            $query = $this->db->query("SELECT DISTINCT bovino.nome, bovino.id,
            (SELECT COALESCE (MAX(`data`),'N/A') FROM partos WHERE bovino.`id` = partos.`bovino_id`) AS 'Ultimo Parto',
            (SELECT MAX(`data`) FROM inseminacao WHERE bovino.`id` = inseminacao.`bovino_id`) AS 'Ultima Inseminação',
            IF (((SELECT MAX(`data`) FROM partos WHERE bovino.`id` = partos.`bovino_id`) <(SELECT MAX(`data`) FROM inseminacao WHERE bovino.`id` = inseminacao.`bovino_id`))
            OR ((SELECT MAX(`data`) FROM partos WHERE bovino.`id` = partos.`bovino_id`) IS NULL),'Prenha', 'Em espera') AS `STATUS`,
            CASE
            WHEN (SELECT MAX(`data`) FROM partos WHERE bovino.`id` = partos.`bovino_id`)>(SELECT MAX(`data`) FROM inseminacao WHERE bovino.`id` = inseminacao.`bovino_id`)
            THEN DATEDIFF ((SELECT MAX(`data`) FROM partos WHERE bovino.`id` = partos.`bovino_id`),
            (SELECT MAX(`data`) FROM inseminacao WHERE bovino.`id` = inseminacao.`bovino_id`))
            ELSE '0' END AS vazia
            FROM bovino
            LEFT  JOIN partos ON bovino.`id` = partos.`bovino_id`
            INNER JOIN inseminacao ON bovino.`id` = inseminacao.`bovino_id`
            INNER JOIN `usuario` ON `bovino`.`usuario_id` = `usuario`.`id`
            INNER JOIN `token` ON `token`.`usuario_id` = `usuario`.`id`
            WHERE `token`.`apikey` = '" . $apikey . "' AND bovino.id=" . $id . " AND  inseminacao.`data` IS  NOT  NULL
            ORDER BY vazia DESC");
            //echo $this->db->last_query();exit;
            return $query->row(0);
        } else {
            return false;
        }
    }

}

?>