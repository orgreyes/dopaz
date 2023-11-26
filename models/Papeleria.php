<?php

namespace Model;

class Papeleria extends ActiveRecord{
    protected static $tabla = 'cont_papeleria';
    protected static $columnasDB = ['pap_nombre','pap_situacion'];
    protected static $idTabla = 'pap_id';

    public $pap_id;
    public $pap_nombre;
    public $pap_situacion;

    public function __construct($args = [])
    {
        $this->pap_id = $args['pap_id'] ?? null;
        $this->pap_nombre = $args['pap_nombre'] ?? '';
        $this->pap_situacion = $args['pap_situacion'] ?? '1';
    }
}
