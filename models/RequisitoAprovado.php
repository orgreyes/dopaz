<?php

namespace Model;

class RequisitoAprovado extends ActiveRecord{
    protected static $tabla = 'cont_req_aprovado';
    protected static $columnasDB = ['apro_ingreso','apro_requisito','apro_id_requisito','apro_situacion'];
    protected static $idTabla = 'apro_id';

    public $apro_id;
    public $apro_ingreso;
    public $apro_requisito;
    public $apro_id_requisito;
    public $apro_situacion;

    public function __construct($args = [])
    {
        $this->apro_id = $args['apro_id'] ?? null;
        $this->apro_ingreso = $args['apro_ingreso'] ?? '';
        $this->apro_requisito = $args['apro_requisito'] ?? '';
        $this->apro_id_requisito = $args['apro_id_requisito'] ?? '';
        $this->apro_situacion = $args['apro_situacion'] ?? '1';
    }
}
