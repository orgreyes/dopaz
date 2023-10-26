<?php

namespace Model;

class Evaluacion extends ActiveRecord{
    protected static $tabla = 'cont_evaluaciones';
    protected static $columnasDB = ['eva_nombre','eva_situacion'];
    protected static $idTabla = 'eva_id';

    public $eva_id;
    public $eva_nombre;
    public $eva_situacion;

    public function __construct($args = [])
    {
        $this->eva_id = $args['eva_id'] ?? null;
        $this->eva_nombre = $args['eva_nombre'] ?? '';
        $this->eva_situacion = $args['eva_situacion'] ?? '1';
    }
}
