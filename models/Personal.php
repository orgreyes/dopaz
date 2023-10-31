<?php

namespace Model;

class Personal extends ActiveRecord{
    protected static $tabla = 'cont_personal';
    protected static $columnasDB = ['per_catalogo','per_dpi','per_nom1','per_nom2','per_ape1','per_ape2','per_grado','per_arma','per_genero','per_puesto','per_fecha_regis','per_situacion'];
    protected static $idTabla = 'per_id';

    public $per_id;
    public $per_catalogo;
    public $per_dpi;
    public $per_nom1;
    public $per_nom2;
    public $per_ape1;
    public $per_ape2;
    public $per_grado;
    public $per_arma;
    public $per_genero;
    public $per_puesto;
    public $per_fecha_regis;
    public $per_situacion;

    public function __construct($args = [])
    {
        $this->per_id = $args['per_id'] ?? null;
        $this->per_catalogo = $args['per_catalogo'] ?? '';
        $this->per_dpi = $args['per_dpi'] ?? '';
        $this->per_nom1 = $args['per_nom1'] ?? '';
        $this->per_nom2 = $args['per_nom2'] ?? '';
        $this->per_ape1 = $args['per_ape1'] ?? '';
        $this->per_ape2 = $args['per_ape2'] ?? '';
        $this->per_grado = $args['per_grado'] ?? '';
        $this->per_arma = $args['per_arma'] ?? '';
        $this->per_genero = $args['per_genero'] ?? '';
        $this->per_puesto = $args['per_puesto'] ?? '';
        $this->per_fecha_regis = date('Y-m-d');
        $this->per_situacion = $args['per_situacion'] ?? '1';
    }
}
