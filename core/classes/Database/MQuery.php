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

namespace Maestro\Database;

class MQuery
{

    /**
     * The result dataset (an numeric-indexed array of rows x fields).
     */
    public $result;

    /**
     *  The current row index.
     */
    public $row;

    /**
     * The row count.
     */
    public $rowCount;

    /**
     * the col count.
     */
    public $columnCount;

    /**
     * true if row > nrows
     */
    public $eof;

    /**
     * true if row < 0
     */
    public $bof;

    /**
     * An array with fieldname, fieldtype, fieldlength, fieldpos for the result
     */
    public $metadata;
    public $db; // mdatabase object
    public $msql; // the SQL object
    public $sql; // the SQL command string
    public $statement; // a parsed sql command - used by some drivers
    public $fetched; // true for a valid result
    public $fetchStyle; // 'assoc','num'

    public function __construct()
    {
        $this->eof = $this->bof = true;
        $this->result = array();
        $this->fetched = false;
        $this->row = -1;
        $this->fetchStyle = \Manager::getOptions('fetchStyle') ? : \FETCH_NUM;
    }

    public function fetchAll($fetchStyle = 0)
    {
        $this->fetchStyle = $fetchStyle ? : $this->fetchStyle;
        if (!$this->msql->stmt) {
            $this->msql->select();
        }
        //\Manager::getLog()->logSQL($this->msql->command . $this->msql->logParams(), $this->db);
        $this->statement = $this->msql->stmt->execute();
        $this->result = $this->db->getPlatform()->fetchAll($this);
        $this->rowCount = count($this->result);
        $this->_setMetadata();
        if ($this->rowCount) {
            $this->row = 0;
            $this->eof = $this->bof = false;
            $this->fetched = true;
        } else {
            $this->result = NULL;
            $this->row = -1;
            $this->eof = $this->bof = true;
            $this->fetched = false;
        }
        $error = $this->msql->stmt->errorCode();
        if ($error && ($error != '00000')) {
            throw new \Exception($this->msql->stmt->errorInfo());
        }
        return $this->result;
    }

    public function fetchObject()
    {
        if (!$this->msql->stmt) {
            $this->msql->select();
        }
        //\Manager::getLog()->logSQL($this->msql->command . $this->msql->logParams(), $this->db);
        $this->statement = $this->msql->stmt->execute();
        $this->result = $this->db->getPlatform()->fetchObject($this);
        return $this->result;
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function setSQL(MSQL $msql)
    {
        $this->msql = $msql;
        if (!$this->msql->db) {
            $this->msql->db = $this->db;
        }
        return $this;
    }

    public function getCommand()
    {
        if (!$this->msql->stmt) {
            $this->msql->select();
            $this->sql = $this->msql->getCommand();
        }
        return $this->sql;
    }

    public function setCommand($sqlCommand)
    {
        $this->msql->setCommand($sqlCommand);
        return $this;
    }

    public function setRange()
    {
        $numargs = func_num_args();
        if ($numargs == 1) {
            $range = func_get_arg(0);
        } elseif ($numargs == 2) {
            $range = new \Maestro\Types\MRange(func_get_arg(0), func_get_arg(1));
        }
        $this->msql->setRange($range);
        $this->resetCommand();
        return $this;
    }

    public function setParameters($parameters = NULL)
    {
        $this->msql->setParameters($parameters);
        $this->resetCommand();
        return $this;
    }

    private function resetCommand()
    {
        $this->msql->select();
        $this->sql = $this->msql->getCommand();
        return $this;
    }

    private function _setMetadata()
    {
        $platform = $this->db->getPlatform();
        $this->metadata = $platform->getMetadata($this->msql->stmt);
        $this->columnCount = $this->metadata['columnCount'];
    }

    public function count()
    {
        $select = $this->msql->select()->getCommand();
        $selectCount = preg_replace("/select (.*) from (.*)/i", "SELECT count(*) as total FROM $2", $select);
        $result = $this->db->executeQuery($selectCount);
        return ($this->fetchStyle == \FETCH_ASSOC) ? $result[0]['total'] : $result[0][0] ;
    }

    public function getCSV($fileName = '', $separator = ';')
    {
        if ($this->result) {
            $csvdump = new MCSVDump($separator);
            $csvdump->dump($this->result, $fileName);
            exit;
        }
    }

    public function movePrev()
    {
        if ($this->bof = ( --$this->row < 0)) {
            $this->row = 0;
        }
        return $this->bof;
    }

    public function moveNext()
    {
        if ($this->eof = ( ++$this->row >= $this->rowCount)) {
            $this->row = $this->rowCount - 1;
        }
        return $this->eof;
    }

    public function moveTo($row)
    {
        $inRange = (!$this->eof) && (($row < $this->rowCount) && ($row > -1));
        if ($inRange) {
            $this->row = $row;
            $this->bof = $this->eof = false;
        }
        return $inRange;
    }

    public function moveFirst()
    {
        return $this->moveTo(0);
    }

    public function moveLast()
    {
        return $this->moveTo($this->rowCount - 1);
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }

    public function getColumnCount()
    {
        return $this->columnCount;
    }

    public function getColumnName($colNumber)
    {
        return $this->metadata['fieldname'][$colNumber];
    }

    public function getColumnNames()
    {
        return $this->metadata['fieldname'];
    }

    public function getColumnNumber($colName)
    {
        return $this->metadata['fieldpos'][strtoupper($colName)];
    }

    public function getColumnNumbers($fields = null)
    {
        $fieldNames = is_null($fields) ? null : (is_string($fields) ? explode(',', $fields) : $fields);
        if ($fieldNames) {
            $numbers = array();
            foreach ($fieldNames as $fieldName) {
                $numbers[] = $this->metadata['fieldpos'][strtoupper(trim($fieldName))];
            }
            return $numbers;
        } else {
            return $this->metadata['fieldpos'];
        }
    }

    public function getValue($fieldName)
    {
        $column = ($this->fetchStyle == \FETCH_ASSOC) ? $fieldName : $this->metadata['fieldpos'][strtoupper($fieldName)];
        return $this->getResult()[$this->row][$column];
    }

    public function fields($fieldName)
    {
        $column = ($this->fetchStyle == \FETCH_ASSOC) ? $fieldName : $this->metadata['fieldpos'][strtoupper($fieldName)];
        return $this->getResult()[$this->row][$column];
    }

    public function getRowValues()
    {
        return $this->result[$this->row];
    }

    public function getRowObject()
    {
        $data = new \stdClass();
        return $this->setRowObject($data);
    }

    public function SetRowObject($object, $fieldNames = null)
    {
        if (is_null($fieldNames)) {
            if ($this->fetchStyle == \FETCH_ASSOC) {
                $fieldNames = array_keys($this->result[0]);
            } else {
                $fieldNames = $this->metadata['fieldname'];
            }
        }
        if ($this->fetchStyle == \FETCH_ASSOC) {
            foreach ($fieldNames as $fieldName) {
                $object->$fieldName = $this->result[$this->row][$fieldName];
            }
        } else {
            for ($i = 0; $i < count($fieldNames); $i++) {
                $fieldName = $fieldNames[$i];
                if (strtoupper($fieldName) == strtoupper($fieldNames[$i])) {
                    $object->$fieldName = $this->result[$this->row][$i];
                }
            }
        }
        return $object;
    }

    public function getFieldValues()
    {
        if ($this->fetchStyle == \FETCH_ASSOC) {
            return $this->getResult()[$this->row];
        } else {
            $fieldValues = array();
            for ($i = 0; $i < $this->columnCount; $i++) {
                $fieldValues[$this->metadata['fieldname'][$i]] = $this->result[$this->row][$i];
            }
            return $fieldValues;
        }
    }

    public function eof()
    {
        return (($this->eof) or ( $this->rowCount == 0));
    }

    public function bof()
    {
        return (($this->bof) or ( $this->rowCount == 0));
    }

    public function getResult($fetchStyle = 0)
    {
        if (!$this->result) {
            $this->result = $this->fetchAll($fetchStyle);
        }
        return $this->result;
    }

    public function uniqueResult()
    {
        if (!$this->result) {
            $this->result = $this->fetchAll();
        }
        return array_shift($this->result[0]);
    }

    public function chunkResult($key = 0, $value = 1, $showKeyValue = false)
    {
        $newResult = array();
        if ($rs = $this->getResult()) {
            
            if (is_string($key)) {
                $key = ($this->fetchStyle == \FETCH_NUM) ? $this->getColumnNumber(strToUpper($key)) : $key;
            }
            if (is_string($value)) {
                $value = ($this->fetchStyle == \FETCH_NUM) ? $this->getColumnNumber(strToUpper($value)) : $value;
            }
            foreach ($rs as $row) {
                $sKey = trim($row[$key]);
                $sValue = trim($row[$value]);
                $newResult[$sKey] = ($showKeyValue ? $sKey . " - " : '') . $sValue;
            }
        }
        return $newResult;
    }

    public function storeResult($key = 0, $value = 1)
    {
        $store = new \stdClass();
        $store->identifier = 'idTable';
        $store->label = 'name';
        $store->items = array();

        foreach ($this->chunkResult($key, $value) as $idTable => $nome) {
            $row = new \stdClass();
            $row->idTable = $idTable;
            $row->name = $nome;
            $store->items[] = $row;
        }
        return $store;
    }

    public function chunkResultMany($key, $values, $type = 'S', $separator = '')
    {
        // type= 'S' : string, otherwise array
        $newResult = array();
        if ($rs = $this->getResult()) {
            if (!is_array($values)) {
                $values = array($values);
            }
            foreach ($rs as $row) {
                $sKey = trim($row[$key]);
                if ($type == 'S') {
                    $sValue = '';
                    $n = count($values);
                    for ($i = 0, $j = 1; $i < $n; $i++, $j++) {
                        $sValue .= trim($row[$values[$i]]) . ($j < $n ? $separator : '');
                    }
                } else {
                    $sValue = array();
                    foreach ($values as $v)
                        $sValue[] = trim($row[$v]);
                }
                $newResult[$sKey] = $sValue;
            }
            return $newResult;
        }
    }

    public function treeResult($group, $node)
    {
        $tree = array();
        if ($rs = $this->getResult()) {
            $tree = array();
            $node = explode(',', $node);
            $group = explode(',', $group);
            foreach ($rs as $row) {
                $aNode = array();
                foreach ($node as $n) {
                    $aNode[$n] = $row[$n];
                }
                $s = '';
                foreach ($group as $g) {
                    $s .= '[$row[' . $g . ']]';
                }
                eval("\$tree$s" . "[] = \$aNode;");
            }
        }
        return $tree;
    }

    public function asXML($root = 'root', $node = 'node')
    {
        $xml = "<$root>";
        $this->moveFirst();
        while (!$this->eof) {
            $xml .= "<$node>";
            for ($i = 0; $i < $this->columnCount; $i++) {
                $fieldName = strtolower($this->metadata['fieldname'][$i]);
                $xml .= "<$fieldName>" . $this->result[$this->row][$i] . "</$fieldName>";
            }
            $this->moveNext();
            $xml .= "</$node>";
        }
        $xml .= "</$root>";
        return $xml;
    }

    public function asObjectArray($fields = null)
    {
        $this->getResult();
        $fieldNames = is_null($fields) ? null : explode(',', $fields);
        $data = array();
        $this->moveFirst();
        while (!$this->eof) {
            $object = new \stdClass();
            $this->setRowObject($object, $fieldNames);
            $this->moveNext();
            $data[] = $object;
        }
        return $data;
    }

    public function asJSON($fields = null)
    {
        return \MJSON::encode($this->asObjectArray($fields));
    }

    public function asCSV($showColumnName = false)
    {
        $this->getResult();
        $result = $this->result;
        if ($showColumnName) {
            for ($i = 0; $i < $this->columnCount; $i++) {
                $columns[] = ucfirst($this->metadata['fieldname'][$i]);
            }
            array_unshift($result, $columns);
        }
        $id = uniqid(md5(uniqid("")));  // generate a unique id to avoid name conflicts
        $fileCSV = \Manager::getFilesPath($id . '.csv', true);
        $csvDump = new \MCSVDump(\Manager::getOptions('csv'));
        $csvDump->save($result, basename($fileCSV));
        return $fileCSV;
    }

    public function each($callback)
    {
        if ($rs = $this->getResult()) {
            foreach ($rs as $i => $row) {
                $callback($row, $i);
            }
        }
    }
    
}

?>