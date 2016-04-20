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

namespace Maestro\Database\Platforms\OCI8;

use Maestro\Types\MCPF,
    Maestro\Types\MCNPJ;
        
class Platform extends \Doctrine\DBAL\Platforms\OraclePlatform {

    public $db;
    public $executeMode = OCI_COMMIT_ON_SUCCESS;

    public function __construct($db) {
        $this->db = $db;
    }

    public function connect() {
        $nlsLang = $this->db->getConfig('nls_lang');
        if ($nlsLang) {
            putenv("NLS_LANG=" . $nlsLang);
            //mdump("NLS_LANG=" . $nlsLang);
        }
//        $nlsDate = $this->db->getConfig('formatDate');
        $nlsDate = 'YYYY/MM/DD';
        $nlsTime = $this->db->getConfig('formatTime');
        putenv("NLS_DATE_FORMAT=" . $nlsDate . ' ' . $nlsTime);
        $this->db->getConnection()->exec("alter session set NLS_DATE_FORMAT='" . $nlsDate . ' ' . $nlsTime . "'");
        $this->db->getConnection()->exec("alter session set NLS_TIMESTAMP_FORMAT='" . $nlsDate . ' ' . $nlsTime . "'");
    }

    public function getTypedAttributes() {
        return 'blob'; //'lob,blob,clob,text';
    }

    public function getSetOperation($operation) {
        $operation = strtoupper($operation);
        $set = array('UNION' => 'UNION', 'UNION ALL' => 'UNION ALL', 'INTERSECT' => 'INTERSECT', 'MINUS' => 'MINUS');
        return $set[$operation];
    }

    public function getNewId($sequence = 'admin', $tableGenerator = 'cm_sequence') {
        $this->value = $this->getNextValue($sequence);
        return $this->value;
    }

    public function getNextValue($sequence = 'admin', $tableGenerator = 'cm_sequence') {
        $sql = new \database\MSQL($sequence . '.nextval as value', 'dual');
        $result = $this->db->query($sql);
        $value = $result[0][0];
        return $value;
    }

    public function getMetadata($stmt) {
        $s = $stmt->getWrappedStatement();
        $metadata['columnCount'] = $count = $s->columnCount();
        for ($i = 0; $i < $count; $i++) {
            $meta = $this->_getColumnMeta($s->getHandle(), $i);
            $name = strtoupper($meta['name']);
            $metadata['fieldname'][$i] = $name;
            $metadata['fieldtype'][$name] = $meta['type'];
            $metadata['fieldlength'][$name] = $meta['len'];
            $metadata['fieldpos'][$name] = $i;
        }
        return $metadata;
    }

    private function _getColumnMeta($stmt, $columnIndex = 0) {
        $meta['name'] = \strtoupper((oci_field_name($stmt, $columnIndex + 1)));
        $meta['len'] = oci_field_size($stmt, $columnIndex + 1);
        $type = oci_field_type($stmt, $columnIndex + 1);
        $rType = 'C';
        if ($type == "VARCHAR") {
            $rType = 'C';
        } elseif ($type == "CHAR") {
            $rType = 'C';
        } elseif ($type == "NUMBER") {
            $rType = 'N';
        } elseif ($type == "DATE") {
            $rType = 'D';
        } elseif ($type == "TIMESTAMP") {
            $rType = 'D';
        } elseif ($type == "BLOB") {
            $rType = 'O';
        } elseif ($type == "CLOB") {
            $rType = 'O';
        }
        $meta['type'] = $rType;
        return $meta;
    }

    public function getSQLRange(\Maestro\Types\MRange $range) {
        return "";
    }

    public function fetchAll($query) {
        $offset = $query->msql->range ? $query->msql->range->offset : 0;
        $maxrows = $query->msql->range ? $query->msql->range->rows : -1;
        $fetchStyle = $query->fetchStyle + OCI_FETCHSTATEMENT_BY_ROW + OCI_RETURN_LOBS;
        $stmt = $query->msql->stmt->getWrappedStatement()->getHandle();
        $rowCount = oci_fetch_all($stmt, $result, $offset, $maxrows, $fetchStyle);
        if ($rowCount === false) {
            throw new EDatabaseQueryException(oci_error($stmt));
        }
        return $result;
    }

    public function fetchObject($query) {
        $stmt = $query->msql->stmt->getWrappedStatement()->getHandle();
        return oci_fetch_object($stmt);
    }

    public function convertToDatabaseValue($value, $type, &$bindingType) {
        if ($value === NULL) {
            return $value;
        }
        if ($type == '') {
            if (is_object($value)) {
                $type = substr(strtolower(get_class($value)), 1);
            }
        }
        if ($type == 'blob') {
            $bindingType = \PDO::PARAM_LOB;
            return "EMPTY_BLOB()";
        }  elseif ($type == 'date') {
            return $value->format('Y/m/d');
        } elseif ($type == 'timestamp') {
            return $value->format('Y/m/d H:i:s');
        } elseif ($type == 'currency') {
            return $value->getValue();
        } elseif (($type == 'decimal') || ($type == 'float')) {
            return str_replace('.', ',', $value);
        } elseif ($type == 'boolean') {
            return (empty($value) ? '0' : '1');
        } elseif ($type == 'cpf') {
            return $value->getPlainValue();
        } elseif ($type == 'cnpj') {
            return $value->getPlainValue();
        } else {
            return $value;
        }
    }

    public function convertToPHPValue($value, $type) {
        if ($type == 'date') {
            return $value;
        } elseif ($type == 'timestamp') {
            return $value;
        } elseif ($type == 'currency') {
            return \Manager::currency($value);
        } elseif ($type == 'cnpj') {
            return MCNPJ::create($value);
        } elseif ($type == 'cpf') {
            return MCPF::create($value);
        } elseif ($type == 'boolean') {
            return $value;
        } elseif (($type == 'decimal') || ($type == 'float')) {
            return str_replace(',', '.', $value);
        } elseif ($type == 'blob') {
            $parsedValue = '';
            if (is_resource($value) or is_resource($value->descriptor)) {
                while (!$value->eof()) {
                    $parsedValue .= $value->read(2000);
                }
                $value = MFile::file($parsedValue);
            } else {
                $value = MFile::file($value);
            }

            return $value;
        } elseif ($type == 'clob'){
            return is_a($value, '\OCI-Lob') ? $value->load() : $value;
        } else {
            return $value;
        }
    }

    public function convertColumn($value, $dbalType) {
        if ($dbalType == 'date') {
            return "TO_CHAR(" . $value . ",'" . $this->db->getConfig('formatDate') . "') ";
        } elseif ($dbalType == 'timestamp') {
            return "TO_CHAR(" . $value . ",'" . $this->db->getConfig('formatDate') . ' ' . $this->db->getConfig('formatTime') . "') ";
        } else {
            return $value;
        }
    }

    public function convertWhere($value, $dbalType) {
        if ($type == '') {
            if (is_object($value)) {
                $type = substr(strtolower(get_class($value)), 1);
            }
        }
        if ($type == 'date') {
            return "TO_DATE('" . $value->format('Y-m-d') . "','YYYY-MM-DD') ";
        } elseif ($type == 'timestamp') {
            return "TO_DATE('" . $value->format('Y-m-d H:i:s') . "','YYYY-MM-DD HH24:MI:SS') ";
        } else {
            return $value;
        }
    }

    public function handleTypedAttribute($attributeMap, $operation, $object) {
        $method = 'handle' . $attributeMap->getType();
        $this->$method($attributeMap, $operation, $object);
    }

    private function handleBLOB($attributeMap, $operation, $object) {
        //mdump('platform::handleBLOB');
        $classMap = $attributeMap->getClassMap();
        $statement = $classMap->getSelectStatement();
        $statement->addParameter($object->getId());
        $statement->setForUpdate(true);
        $query = $this->db->getQuery($statement);
        $file = $attributeMap->getValue($object);
        $value = $file ? $file->getValue() : '';
        $column = $attributeMap->getColumnName();
        if (($operation == 'insert') || ($operation == 'update')) {
            $row = $query->fetchObject();
            $column = strtoupper($column);
            if(isset($row->$column)) {
                $row->$column->truncate();
                $row->$column->save($value);
                $row->$column->free();
                $logger = $this->db->getConnection()->getConfiguration()->getSQLLogger();
                $logger->startQuery('BLOB ' . $operation . ' - column ' . $column);
            }
        }
        if (($operation == 'select')) { // handled directly by oci_fetch_all (query)
        }
    }

}

function handleText($attributeMap, $operation) {
    //mdump('platform::handleText');
}

?>