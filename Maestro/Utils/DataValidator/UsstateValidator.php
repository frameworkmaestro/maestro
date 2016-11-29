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
/*
 *  $Id: Usstate.php 7490 2010-03-29 19:53:27Z jwage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Validator_Usstate
 *
 * @package     Doctrine
 * @subpackage  Validator
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision: 7490 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
namespace Maestro\Utils\DataValidator;

class UsstateValidator extends DriverValidator
{
    private static $states = array(
                'AK' => true,
                'AL' => true,
                'AR' => true,
                'AZ' => true,
                'CA' => true,
                'CO' => true,
                'CT' => true,
                'DC' => true,
                'DE' => true,
                'FL' => true,
                'GA' => true,
                'HI' => true,
                'IA' => true,
                'ID' => true,
                'IL' => true,
                'IN' => true,
                'KS' => true,
                'KY' => true,
                'LA' => true,
                'MA' => true,
                'MD' => true,
                'ME' => true,
                'MI' => true,
                'MN' => true,
                'MO' => true,
                'MS' => true,
                'MT' => true,
                'NC' => true,
                'ND' => true,
                'NE' => true,
                'NH' => true,
                'NJ' => true,
                'NM' => true,
                'NV' => true,
                'NY' => true,
                'OH' => true,
                'OK' => true,
                'OR' => true,
                'PA' => true,
                'PR' => true,
                'RI' => true,
                'SC' => true,
                'SD' => true,
                'TN' => true,
                'TX' => true,
                'UT' => true,
                'VA' => true,
                'VI' => true,
                'VT' => true,
                'WA' => true,
                'WI' => true,
                'WV' => true,
                'WY' => true
            );
    public function getStates()
    {
        return self::$states;
    }

    /**
     * checks if given value is a valid US state code
     *
     * @param string $args
     * @return boolean
     */
    public function validate($value)
    {
        if (is_null($value)) {
            return true;
        }
        return isset(self::$states[$value]);
    }
}