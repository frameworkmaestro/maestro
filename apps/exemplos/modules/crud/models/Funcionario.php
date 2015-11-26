<?php
/**
 * 
 *
 * @category   SIGA
 * @package    UFJF
 * @subpackage exemplos
 * @copyright  Copyright (c) 2003-2011 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace crud\models;

class Funcionario extends map\FuncionarioMap {

    public static function config() {
        return array(
            'log' => array(),
            'validators' => array(
                'idPessoa' => array('not null'),
            ),
            'converters' => array()
        );
    }

}

?>