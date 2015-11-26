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

class Transacao extends map\TransacaoMap {

    public static function config() {
        return array(
            'log' => array(transacao),
            'validators' => array(
                'transacao' => array('notnull'),
            ),
            'converters' => array()
        );
    }

    public function getDescription() {
        return $this->getTransacao();
    }

    public function listByFilter($filter) {
        $criteria = $this->getCriteria()->select('*')->orderBy('transacao');
        if ($filter->transacao) {
            $criteria->where("transacao LIKE '{$filter->transacao}%'");
        }
        return $criteria;
    }

    public function listGrupos() {
        $criteria = $this->getCriteria()->select("acessos.idAcesso,acessos.grupo.idGrupo,acessos.grupo.grupo,acessos.direito")->orderBy("acessos.grupo.grupo");
        if ($this->idTransacao) {
            $criteria->where("idTransacao = {$this->idTransacao}");
        }
        return $criteria;
    }

    public function deleteAcesso($delete) {
        try {
            $transaction = $this->beginTransaction();
            if (is_array($delete)) {
                foreach ($delete as $id) {
                    Acesso::create($id)->delete();
                }
            } else {
                Acesso::create($delete)->delete();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new EModelException('Erro ao exlcuir Grupo da Transacao');
        }
    }

}

?>