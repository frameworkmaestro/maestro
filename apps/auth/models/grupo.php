<?php

/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage vendas0
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace auth\models;

class Grupo extends map\GrupoMap {

    public static function config() {
        return array(
            'log' => array(grupo),
            'validators' => array(
                'grupo' => array('notnull'),
            ),
            'converters' => array()
        );
    }

    public function getDescription() {
        return $this->getGrupo();
    }

    public function listByFilter($filter) {
        $criteria = $this->getCriteria()->select('*')->orderBy('grupo');
        if ($filter->grupo) {
            $criteria->where("grupo LIKE '{$filter->grupo}%'");
        }
        return $criteria;
    }

    public function listUsuario() {
        $criteria = $this->getCriteria()->select('usuarios.idUsuario, usuarios.login')->orderBy('usuarios.login');
        if ($this->idGrupo) {
            $criteria->where("idGrupo = {$this->idGrupo}");
        }
        return $criteria;
    }
}
?>