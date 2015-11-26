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

class MRenderPrompt extends MResultObject
{

    public function setContent()
    {
        $prompt = $this->object;
        $this->ajax->setResponseType('JSON');
        if ($this->ajax->isEmpty()) {
            $this->ajax->setId($prompt->id);
            $this->ajax->setType('prompt');
            $data = new \StdClass();
            $data->type = $prompt->property->type;
            $data->msg = $prompt->property->msg;
            $data->action1 = $prompt->property->action1;
            $data->action2 = $prompt->property->action2;
            $data->event1 = $prompt->property->event1;
            $data->event2 = $prompt->property->event2;
            $this->ajax->setData($data);
        }
        $this->content = $this->ajax->returnData();
    }

}
