<?php
/**
 * 
 *
 * @category   SIGA
 * @package    UFJF
 * @subpackage Common_Classes
 * @copyright  Copyright (c) 2003-2011 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace exemplos\models;

class Log extends map\LogMap {

        function log($operacao, $classe, $idModel, $descricao = '', $idUsuario = '', $idModulo = '') {
        $this->operacao = $operacao;
        $this->descricao = $descricao;
        $this->classe = $classe;
        $this->idModel = $idModel;

        $this->idModulo = ($idModulo == '') ? '1' : $idModulo;
        $login = \Manager::getLogin();
        if ($idUsuario == '') {
            $login = \Manager::getLogin();
            $idUsuario = $login->getIdUser();
        }
        $this->idUsuario = $idUsuario;
        $this->save();
    }

    public function getCommand($operacao, $classe, $idModel, $descricao) {
        $this->operacao = $operacao;
        $this->descricao = $descricao;
        $this->classe = $classe;
        $this->idModel = $idModel;
        $this->idModulo = ($idModulo == '') ? '1' : $idModulo;
        $login = \Manager::getLogin();
        if ($idUsuario == '') {
            $login = \Manager::getLogin();
            $idUsuario = $login ? $login->getIdUser() : 1;
        }
        $this->idUsuario = $idUsuario;
        $this->getClassMap()->setObjectKey($this);
        $statement = $this->getClassMap()->getInsertSqlFor($this);
        return $statement->insert();
    }


}

?>