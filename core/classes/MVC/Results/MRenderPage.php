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

class MRenderPage extends MResultObject
{

    public function setContent()
    {
        mtrace('Executing MRenderPage');
        if ($this->object) {
            $this->page->setContent($this->object);
        }
        if (Manager::isAjaxCall()) {
            $this->content = $this->page->generate();
        } else  {
            $this->content = $this->page->render();
        }
    }
    
    public function getOutput()
    {
        Manager::$response->setHeader('Content-type', 'text/html; charset=UTF-8');
        return parent::getOutput();
    }
    

}
