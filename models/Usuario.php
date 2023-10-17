<?php

namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla = 'mper';
    protected static $idTabla = 'per_catalogo'; //nombre de la tablaX
    protected static $columnasDB = ['per_catalogo','per_nom1', 'per_nom2', 'per_ape1', 'per_ape2'];

    public $per_catalogo;
    public $per_nom1;
    public $per_nom2;
    public $per_ape1;
    public $per_ape2;

    public function __construct($args = []){
        $this->per_catalogo = $args['per_catalogo'] ?? null;
        $this->per_nom1 = $args['per_nom1'] ?? '';
        $this->per_nom2 = $args['per_nom2'] ?? '';
        $this->per_ape1 = $args['per_ape1'] ?? '';
        $this->per_ape2 = $args['per_ape2'] ?? '';
    }
}