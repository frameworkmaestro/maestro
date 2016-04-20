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

namespace Maestro\MVC;

use Maestro\Manager;


/**
 * Brief Class Description.
 * Complete Class Description.
 */
class MService extends MController {

    public function getDatabase($name) {
        return Manager::getDatabase($name);
    }

    /**
     * Coloca a conexão indicada em estado de transação e retorna um handler para a transação.
     * @return Connection
     */
    public function beginTransaction($name) {
        return $this->getDatabase($name)->beginTransaction();
    }

    /**
     *
     * @return \PHPMailer
     * @deprecated
     */
    public static function getMailer() {
        return MMailer::getMailer();
    }

    public function render($result) {
        $this->renderJSON(json_encode($result));
    }

    public function renderPrompt($type, $message) {
        $result = new \stdClass();
        $result->code = 1;
        $result->type = $type;
        $result->message = $message;
        $this->renderJSON(json_encode($result));
    }

}