<?php

namespace Model;

class AsigEvaluacion extends ActiveRecord{
    protected static $tabla = 'cont_asig_evaluaciones';
    protected static $columnasDB = ['asig_eva_nombre','asig_eva_puesto','asig_eva_situacion'];
    protected static $idTabla = 'asig_eva_id';

    public $asig_eva_id;
    public $asig_eva_nombre;
    public $asig_eva_puesto;
    public $asig_eva_situacion;

    public function __construct($args = [])
    {
        $this->asig_eva_id = $args['asig_eva_id'] ?? null;
        $this->asig_eva_nombre = $args['asig_eva_nombre'] ?? '';
        $this->asig_eva_puesto = $args['asig_eva_puesto'] ?? '';
        $this->asig_eva_situacion = $args['asig_eva_situacion'] ?? '1';
    }
}
