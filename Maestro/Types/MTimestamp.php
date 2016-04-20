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

use Doctrine\DBAL\Platforms\AbstractPlatform,
    Maestro\Manager,
    Maestro\Utils\MKrono;

class MTimestamp extends MDate
{

    public function __construct($datetime = NULL, $format = '')
    {
        parent::__construct($datetime, ($format ? : Manager::getOptions('formatTimestamp')));
    }

    public static function getSysTime($format = 'd/m/Y H:i:s')
    {
        return new MTimestamp(date($format));
    }

    public function invert()
    {
        $dateTime = explode(" ", $this->format());
        return MKrono::invertDate($dateTime[0]) . ' ' . $dateTime[1];
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        // TODO: Implement getSQLDeclaration() method.
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new MTimestamp($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->format('Y-m-d H:i:s');
    }

}

?>
