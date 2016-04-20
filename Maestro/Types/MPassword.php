<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 14/01/2016
 * Time: 14:08
 */

namespace Maestro\Types;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Maestro\Manager;

class MPassword extends MType
{

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        //??
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return md5($value);
    }
}