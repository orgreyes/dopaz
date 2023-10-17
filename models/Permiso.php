<?php

namespace Model;

class Permiso extends ActiveRecord{
    protected static $tabla = 'niveles_autocom';
    protected static $columnasDB = ['AUT_CODIGO','AUT_PLAZA','AUT_PERMISO'];

    public $aut_codigo;
    public $aut_plaza;
    public $aut_permiso;

    public function __construct($args = [])
    {
        $this->aut_codigo = $args['aut_codigo'] ?? '';
        $this->aut_plaza = $args['aut_plaza'] ?? '';
        $this->aut_permiso = $args['aut_permiso'] ?? '';
    }
}