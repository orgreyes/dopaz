<?php

namespace Model;

class AsigPapeleria extends ActiveRecord{
    protected static $tabla = 'cont_asig_papeleria';
    protected static $columnasDB = ['asig_pap_nombre','asig_pap_puesto','asig_pap_situacion'];
    protected static $idTabla = 'asig_pap_id';

    public $asig_pap_id;
    public $asig_pap_nombre;
    public $asig_pap_puesto;
    public $asig_pap_situacion;

    public function __construct($args = [])
    {
        $this->asig_pap_id = $args['asig_pap_id'] ?? null;
        $this->asig_pap_nombre = $args['asig_pap_nombre'] ?? '';
        $this->asig_pap_puesto = $args['asig_pap_puesto'] ?? '';
        $this->asig_pap_situacion = $args['asig_pap_situacion'] ?? '1';
    }
}
