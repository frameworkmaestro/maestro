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

namespace Maestro\MVC\Results;

use Maestro\Manager;

class MNotFound extends MResultException
{

    public function setContent()
    {
        mdump('Executing MNotFound');
        try {
            $errorHtml = $this->fetch("404");
            if (Manager::isAjaxCall()) {
                $this->ajax->setType('page');
                $this->ajax->setData($errorHtml);
                $this->ajax->setResponseType('JSON');
                $this->content = $this->ajax->returnData();
            } else {
                $this->content = $errorHtml;
            }
        } catch (\Maestro\Services\Exception\EMException $e) {
            
        }
    }

    public function getOutput()
    {
        Manager::$response->status = \Maestro\Services\HTTP\MStatusCode::NOT_FOUND;
        return parent::getOutput();
    }

}

