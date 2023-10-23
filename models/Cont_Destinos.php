<?php

namespace Model;

class Cont_Destinos extends ActiveRecord{
    protected static $tabla = 'cont_destinos';
    protected static $columnasDB = ['dest_nombre','dest_latitud','dest_longitud','dest_situacion'];
    protected static $idTabla = 'dest_id';

    public $dest_id;
    public $dest_nombre;
    public $dest_latitud;
    public $dest_longitud;
    public $dest_situacion;

    public function __construct($args = [])
    {
        $this->dest_id = $args['dest_id'] ?? null;
        $this->dest_nombre = $args['dest_nombre'] ?? '';
        $this->dest_latitud = $args['dest_latitud'] ?? '';
        $this->dest_longitud = $args['dest_longitud'] ?? '';
        $this->dest_situacion = $args['dest_situacion'] ?? '1';
    }
}
