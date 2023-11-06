<?php

namespace Model;

class AsigMision extends ActiveRecord{
    protected static $tabla = 'cont_asig_misiones';
    protected static $columnasDB = ['asig_contingente','asig_mision','asig_situacion'];
    protected static $idTabla = 'asig_id';

    public $asig_id;
    public $asig_contingente;
    public $asig_mision;
    public $asig_situacion;

    public function __construct($args = [])
    {
        $this->asig_id = $args['asig_id'] ?? null;
        $this->asig_contingente = $args['asig_contingente'] ?? '';
        $this->asig_mision = $args['asig_mision'] ?? '';
        $this->asig_situacion = $args['asig_situacion'] ?? '1';
    }
}
