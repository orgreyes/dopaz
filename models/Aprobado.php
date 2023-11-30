<?php

namespace Model;

class Aprobado extends ActiveRecord
{
    protected static $tabla = 'cont_aprobados';
    protected static $columnasDB = ['apro_asp','apro_situacion'];
    protected static $idTabla = 'apro_id';

    public $apro_id;
    public $apro_asp;
    public $apro_situacion;

    public function __construct($args = [])
    {
        $this->apro_id = $args['apro_id'] ?? null;
        $this->apro_asp = $args['apro_asp'] ?? '';
        $this->apro_situacion = $args['apro_situacion'] ?? '1';
    }
}