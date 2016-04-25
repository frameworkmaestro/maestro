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
use Maestro\Manager;
use Nette\NotImplementedException;

abstract class MType
{
    /**
     * The map of supported Maestro mapping types.
     *
     * @var array
     */
    private static $_typesMap = array(
    );
    /**
     * The map of origin for mapping types: doctrine or maestro
     *
     * @var array
     */
    private static $_typesOrigin = array(
    );
    /**
     * {@inheritdoc}
     */
    public static function getType($type)
    {
        /*
        if(Type::hasType($type)) {
            return Type::getType($type);
        }else if(MType::hasType($type)){
            $class = Manager::getConf('types')[$type];
            return new $class();
        }
        */
        if (MType::hasType($type)) {
            if (self::$_typesOrigin[$type] == 'doctrine') {
                return Type::getType($type);
            } else {
                $class = self::$_typesMap[$type];
                return new $class();
            }
        }
        return false;
    }

    public static function hasType($type)
    {
        if (isset(self::$_typesMap[$type])) {
            return true;
        } else {
            $className = Manager::getConf('types')[$type];
            if (isset($className)) {
                self::$_typesMap[$type] = $className;
                self::$_typesOrigin[$type] = 'maestro';
                return true;
            } elseif (Type::hasType($type)) {
                self::$_typesMap[$type] = Type::getTypesMap()[$type];
                self::$_typesOrigin[$type] = 'doctrine';
                return true;
            }
        }
        return false;
    }

    public abstract function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform);

    public abstract function convertToPHPValue($value, AbstractPlatform $platform);

    public abstract function convertToDatabaseValue($value, AbstractPlatform $platform);

    public function getName()
    {
        return get_class($this);
    }
}