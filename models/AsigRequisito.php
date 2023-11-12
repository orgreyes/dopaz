<?php

namespace Model;

class AsigRequisito extends ActiveRecord{
    protected static $tabla = 'cont_asig_requisitos';
    protected static $columnasDB = ['asig_req_puesto','asig_req_requisito','asig_req_situacion'];
    protected static $idTabla = 'asig_req_id';

    public $asig_req_id;
    public $asig_req_puesto;
    public $asig_req_requisito;
    public $asig_req_situacion;

    public function __construct($args = [])
    {
        $this->asig_req_id = $args['asig_req_id'] ?? null;
        $this->asig_req_puesto = $args['asig_req_puesto'] ?? '';
        $this->asig_req_requisito = $args['asig_req_requisito'] ?? '';
        $this->asig_req_situacion = $args['asig_req_situacion'] ?? '1';
    }
}
