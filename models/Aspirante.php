<?php

namespace Model;

class Aspirante extends ActiveRecord{
    protected static $tabla = 'cont_aspirantes';
    protected static $columnasDB = ['asp_nombre','asp_contingente','asp_anio','asp_puesto','asp_grado','asp_situacion'];
    protected static $idTabla = 'asp_id';

    public $asp_id;
    public $asp_nombre;
    public $asp_contingente;
    public $asp_anio;
    public $asp_puesto;
    public $asp_grado;
    public $asp_situacion;

    public function __construct($args = [])
    {
        $this->asp_id = $args['asp_id'] ?? null;
        $this->asp_nombre = $args['asp_nombre'] ?? '';
        $this->asp_contingente = $args['asp_contingente'] ?? '';
        $this->asp_anio = date('Y');
        $this->asp_puesto = $args['asp_puesto'] ?? '';
        $this->asp_grado = $args['asp_grado'] ?? '';
        $this->asp_situacion = $args['asp_situacion'] ?? '1';
    }
}
