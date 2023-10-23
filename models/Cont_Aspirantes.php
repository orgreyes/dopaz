<?php

namespace Model;

class Cont_Aspirantes extends ActiveRecord{
    protected static $tabla = 'cont_aspirantes';
    protected static $columnasDB = ['asp_catalogo','asp_dpi','asp_nom1','asp_nom2','asp_ape1','asp_ape2','asp_genero','asp_situacion'];
    protected static $idTabla = 'asp_id';

    public $asp_id;
    public $asp_catalogo;
    public $asp_dpi;
    public $asp_nom1;
    public $asp_nom2;
    public $asp_ape1;
    public $asp_ape2;
    public $asp_genero;
    public $asp_situacion;

    public function __construct($args = [])
    {
        $this->asp_id = $args['asp_id'] ?? null;
        $this->asp_catalogo = $args['asp_catalogo'] ?? '';
        $this->asp_dpi = $args['asp_dpi'] ?? '';
        $this->asp_nom1 = $args['asp_nom1'] ?? '';
        $this->asp_nom2 = $args['asp_nom2'] ?? '';
        $this->asp_ape1 = $args['asp_ape1'] ?? '';
        $this->asp_ape2 = $args['asp_ape2'] ?? '';
        $this->asp_genero = $args['asp_genero'] ?? '';
        $this->asp_situacion = $args['asp_situacion'] ?? '1';
    }
}
