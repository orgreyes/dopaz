<?php

namespace Model;

class AsigGrado extends ActiveRecord{
    protected static $tabla = 'asig_grado_puesto';
    protected static $columnasDB = ['asig_grado','asig_puesto','asig_grado_situacion'];
    protected static $idTabla = 'asig_grado_id';

    public $asig_grado_id;
    public $asig_grado;
    public $asig_puesto;
    public $asig_grado_situacion;

    public function __construct($args = [])
    {
        $this->asig_grado_id = $args['asig_grado_id'] ?? null;
        $this->asig_grado = $args['asig_grado'] ?? '';
        $this->asig_puesto = $args['asig_puesto'] ?? '';
        $this->asig_grado_situacion = $args['asig_grado_situacion'] ?? '1';
    }
}
