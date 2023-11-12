<?php

namespace Model;

class Requisito extends ActiveRecord{
    protected static $tabla = 'cont_requisitos';
    protected static $columnasDB = ['req_nombre','req_situacion'];
    protected static $idTabla = 'req_id';

    public $req_id;
    public $req_nombre;
    public $req_situacion;

    public function __construct($args = [])
    {
        $this->req_id = $args['req_id'] ?? null;
        $this->req_nombre = $args['req_nombre'] ?? '';
        $this->req_situacion = $args['req_situacion'] ?? '1';
    }
}
