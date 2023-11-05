<?php

namespace Model;

class Mision extends ActiveRecord{
    protected static $tabla = 'cont_misiones_contingente';
    protected static $columnasDB = ['mis_nombre','mis_latitud','mis_longitud','mis_situacion'];
    protected static $idTabla = 'mis_id';

    public $mis_id;
    public $mis_nombre;
    public $mis_latitud;
    public $mis_longitud;
    public $mis_situacion;

    public function __construct($args = [])
    {
        $this->mis_id = $args['mis_id'] ?? null;
        $this->mis_nombre = $args['mis_nombre'] ?? '';
        $this->mis_latitud = $args['mis_latitud'] ?? '';
        $this->mis_longitud = $args['mis_longitud'] ?? '';
        $this->mis_situacion = $args['mis_situacion'] ?? '1';
    }
}
