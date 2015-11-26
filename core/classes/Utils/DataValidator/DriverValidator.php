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
 *  $Id: Notnull.php 1080 2007-02-10 18:17:08Z romanb $
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
 * Doctrine_Validator_Driver
 *
 * @package     Doctrine
 * @subpackage  Validator
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision: 1080 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class DriverValidator
{
    /**
     * @var array $_args     an array of plugin specific args
     */
    public $args;
    public $invoker;
    public $field;

    /**
     * __get
     * an alias for getOption
     *
     * @param string $arg
     */
    public function __get($arg)
    {
        if (isset($this->args[$arg])) {
            return $this->args[$arg];
        }
        return null;
    }

    /**
     * __isset
     *
     * @param string $arg
     */
    public function __isset($arg)
    {
        return isset($this->args[$arg]);
    }

    /**
     * sets given value to an argument
     *
     * @param $arg          the name of the option to be changed
     * @param $value        the value of the option
     * @return Doctrine_Validator_Driver    this object
     */
    public function __set($arg, $value)
    {
        $this->args[$arg] = $value;
        
        return $this;
    }

    /**
     * returns the value of an argument
     *
     * @param $arg          the name of the option to retrieve
     * @return mixed        the value of the option
     */
    public function getArg($arg)
    {
        if ( ! isset($this->args[$arg])) {
            throw new Doctrine_Validator_Exception('Unknown option ' . $arg);
        }
        
        return $this->args[$arg];
    }

    /**
     * sets given value to an argument
     *
     * @param $arg          the name of the option to be changed
     * @param $value        the value of the option
     * @return Doctrine_Validator_Driver    this object
     */
    public function setArg($arg, $value)
    {
        $this->args[$arg] = $value;
        
        return $this;
    }

    /**
     * returns all args and their associated values
     *
     * @return array    all args as an associative array
     */
    public function getArgs()
    {
        return $this->args;
    }

    public function __toString()
    {
        $className = get_class($this);
        if (strpos($className, 'Doctrine_Validator_') === 0) { 
            return strtolower(substr($className, 19));
        } else {
            return $className;
        }
    }
} }
    }
}<?php
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
 *  $Id: Notnull.php 1080 2007-02-10 18:17:08Z romanb $
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
 * Doctrine_Validator_Driver
 *
 * @package     Doctrine
 * @subpackage  Validator
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision: 1080 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class DriverValidator
{
    /**
     * @var array $_args     an array of plugin specific args
     */
    public $args;
    public $invoker;
    public $field;

    /**
     * __get
     * an alias for getOption
     *
     * @param string $arg
     */
    public function __get($arg)
    {
        if (isset($this->args[$arg])) {
            return $this->args[$arg];
        }
        return null;
    }

    /**
     * __isset
     *
     * @param string $arg
     */
    public function __isset($arg)
    {
        return isset($this->args[$arg]);
    }

    /**
     * sets given value to an argument
     *
     * @param $arg          the name of the option to be changed
     * @param $value        the value of the option
     * @return Doctrine_Validator_Driver    this object
     */
    public function __set($arg, $value)
    {
        $this->args[$arg] = $value;
        
        return $this;
    }

    /**
     * returns the value of an argument
     *
     * @param $arg          the name of the option to retrieve
     * @return mixed        the value of the option
     */
    public function getArg($arg)
    {
        if ( ! isset($this->args[$arg])) {
            throw new Doctrine_Validator_Exception('Unknown option ' . $arg);
        }
        
        return $this->args[$arg];
    }

    /**
     * sets given value to an argument
     *
     * @param $arg          the name of the option to be changed
     * @param $value        the value of the option
     * @return Doctrine_Validator_Driver    this object
     */
    public function setArg($arg, $value)
    {
        $this->args[$arg] = $value;
        
        return $this;
    }

    /**
     * returns all args and their associated values
     *
     * @return array    all args as an associative array
     */
    public function getArgs()
    {
        return $this->args;
    }

    public function __toString()
    {
        $className = get_class($this);
        if (strpos($className, 'Doctrine_Validator_') === 0) { 
            return strtolower(substr($className, 19));
        } else {
            return $className;
        }
    }
} }
    }
}