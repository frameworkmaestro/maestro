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

namespace Maestro\Database\Platforms\PDOMySql;

use Maestro\Types\MType;

class Platform extends \Doctrine\DBAL\Platforms\MySqlPlatform
{

    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function connect()
    {
        $charset = $this->db->getConfig('charset');
        if ($charset) {
            $this->db->getConnection()->exec("SET CHARACTER SET '{$charset}'");
        }
    }

    public function getTypedAttributes()
    {
        return ''; //'lob,blob,clob,text';
    }

    public function getSetOperation($operation)
    {
        $operation = strtoupper($operation);
        $set = array('UNION' => 'UNION', 'UNION ALL' => 'UNION', 'INTERSECT' => 'INTERSECT', 'MINUS' => 'MINUS');
        return $set[$operation];
    }

    public function getNewId($sequence = 'admin')
    {
        $this->value = $this->_getNextValue($sequence);
        return $this->value;
    }

    private function _getNextValue($sequence = 'admin')
    {
        $transaction = $this->db->beginTransaction();
        $table = $this->db->getConfig('sequence.table');
        $name = $this->db->getConfig('sequence.name');
        $field = $this->db->getConfig('sequence.value');
        $sql = new \Maestro\Database\MSQL($field, $table, "({$name} = '$sequence')");
        $sql->setForUpdate(true);
        $result = $this->db->query($sql);
        $value = (\Manager::getOptions('fetchStyle') == \FETCH_NUM) ? $result[0][0] : $result[0][$field];
        $nextValue = $value + 1;
        $this->db->execute($sql->update($nextValue), $nextValue);
        $transaction->commit();
        return $value;
    }

    public function lastInsertId()
    {
        return $this->db->getConnection()->lastInsertId();
    }

    public function getMetaData($stmt)
    {
        $s = $stmt->getWrappedStatement();
        $metadata['columnCount'] = $count = $s->columnCount();
        for ($i = 0; $i < $count; $i++) {
            $meta = $s->getColumnMeta($i);
            $name = strtoupper($meta['name']);
            $metadata['fieldname'][$i] = $name;
            $metadata['fieldtype'][$name] = $this->_getMetaType($meta['pdo_type']);
            $metadata['fieldlength'][$name] = $meta['len'];
            $metadata['fieldpos'][$name] = $i;
        }
        return $metadata;
    }

    private function _getMetaType($pdo_type)
    {
        if ($pdo_type == \PDO::PARAM_BOOL) {
            $type = 'B';
        } else if ($pdo_type == \PDO::PARAM_NULL) {
            $type = ' ';
        } else if ($pdo_type == \PDO::PARAM_INT) {
            $type = 'N';
        } else if ($pdo_type == \PDO::PARAM_STR) {
            $type = 'C';
        } else if ($pdo_type == \PDO::PARAM_LOB) {
            $type = 'O';
        } else {
            $type = 'C';
        }
        return $type;
    }

    public function getSQLRange(\Maestro\Types\MRange $range)
    {
        return "LIMIT " . $range->offset . "," . $range->rows;
    }

    public function fetchAll($query)
    {
        $stmt = $query->msql->stmt->getWrappedStatement();
        return $stmt->fetchAll($query->fetchStyle);
    }

    public function fetchObject($query)
    {
        $stmt = $query->msql->stmt->getWrappedStatement();
        return $stmt->fetchObject();
    }

    public function convertToDatabaseValue($value, $type)
    {
        if(MType::hasType($type)){
            $obj = MType::getType($type);
            $value = $obj->convertToDatabaseValue($value, $this);
        }
        return $value;
    }

    public function convertToPHPValue($value, $type)
    {
        if(MType::hasType($type)){
            $obj = MType::getType($type);
            $value = $obj->convertToPHPValue($value, $this);
        }
        return $value;
    }

    public function convertColumn($value, $type)
    {
        if ($type == 'date') {
            return "DATE_FORMAT(" . $value . ",'" . $this->db->getConfig('formatDate') . "') ";
        } elseif ($type == 'timestamp') {
            return "DATE_FORMAT(" . $value . ",'" . $this->db->getConfig('formatDate') . ' ' . $this->db->getConfig('formatTime') . "') ";
        } else {
            return $value;
        }
    }

    public function convertWhere($value, $dbalType)
    {
        if ($dbalType == 'date') {
            return "DATE_FORMAT(" . $value . ",'" . $this->db->getConfig('formatDateWhere') . "') ";
        } elseif ($dbalType == 'datetime') {
            return " DATE_FORMAT(" . $value . "," . $this->db->getConfig('formatDateWhere') . ' ' . $this->db->getConfig('formatTime') . "') ";
        } else {
            return $value;
        }
    }

    public function handleTypedAttribute($attributeMap, $operation)
    {
        $method = 'handle' . $attributeMap->getType();
        $this->$method($operation);
    }

    private function handleLOB($operation)
    {

        //mdump('platform::handleLob');
    }

    private function handleBLOB($operation)
    {

        //mdump('platform::handleBLob');
    }

    private function handleText($operation)
    {

        //mdump('platform::handleText');
    }

}

?>