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
namespace Maestro\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Classe utilitária para trabalhar com Blob.
 *
 * @category    Maestro
 * @package     Core
 * @subpackage  Types
 * @version     1.0
 * @since       1.0
 */
class MBlob extends Mtype
{
    private $value;

    public function __construct($value)
    {
        $this->setValue($value);
    }

    public static function create($value)
    {
        return new MCPF($value);
    }

    public function getValue()
    {
        return $this->value ?: '';
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getPlainValue()
    {
        return $this->value;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        //Same as regular string
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform = NULL)
    {
        return MFile::file($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform = NULL)
    {
        return $value;
    }

}
