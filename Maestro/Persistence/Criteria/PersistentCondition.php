<?php
/* Copyright [2011, 2012, 2013] da Universidade Federal de Juiz de Fora
 * Este arquivo é parte do programa Framework Maestro.
 * O Framework Maestro é um software livre; você pode redistribuí-lo e/ou 
 * modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada 
 * pela Fundação do Software Livre (FSF); na versão 2 da Licença.
 * Este programa é distribuído na esperança que possa ser  útil, 
 * mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL 
 * em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título
 * "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software
 * Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

namespace Maestro\Persistence\Criteria;

use Maestro;

class PersistentCondition {

    private $operand1;
    private $operator;
    private $operand2;

    public function __construct($operand1, $operator, $operand2) {
        $this->operand1 = $operand1;
        $this->operator = $operator;
        $this->operand2 = $operand2;
    }

    public function setCriteria($criteria){
        $this->criteria = $criteria;
    }

    public function getSql() {
        $condition = "(";
        $condition .= $this->criteria->getOperand($this->operand1)->getSqlWhere();
        $condition .= ' ' . $this->operator . ' ';
        $condition .= $this->criteria->getOperand($this->operand2)->getSqlWhere();
        $condition .= ")";
        return $condition;
    }

}

?>