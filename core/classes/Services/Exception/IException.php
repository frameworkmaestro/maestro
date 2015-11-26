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

namespace Maestro\Services\Exception;

use Maestro\Manager;

interface IException
{

    /**
     * Mensagem de exceção.
     */
    public function getMessage();

    /**
     * Código da exceção (user-defined).
     */
    public function getCode();

    /**
     * Nome do arquivo fonte.
     */
    public function getFile();

    /**
     * Linha do arquivo fonte.
     */
    public function getLine();

    /**
     * Array do backtrace().
     */
    public function getTrace();

    /**
     * String formatada do trace.
     */
    public function getTraceAsString();

    /**
     * String formatada para exibição.
     */
    public function __toString();
}
