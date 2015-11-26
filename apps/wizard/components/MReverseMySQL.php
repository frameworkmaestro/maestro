<?php

class MReverseMySQL {

    public $fileScript;
    public $baseDir;
    private $nodes;
    private $className;
    private $appName;
    private $moduleName;
    private $databaseName;
    private $package;
    public $generatedMaps;
    public $errors;

    public function setBaseDir($dir) {
        $this->baseDir = $dir;
    }

    public function setFile($file) {
        $this->fileScript = $file;
    }

    public function setAppName($name) {
        $this->appName = $name;
    }

    public function setModuleName($name) {
        $this->moduleName = $name;
    }

    public function setDatabaseName($name) {
        $this->databaseName = $name;
    }

    public function generate() {
        $db = Manager::getDatabase($this->databaseName);
        $pf = $db->getPlatForm();
        $queryTables = $db->getQueryCommand($pf->getListTablesSQL());
        foreach ($queryTables->getResult() as $tables) {
            $table = $tables[0];
            $classes[$table]['name'] = $table;
            $queryColumns = $db->getQueryCommand($pf->getListTableColumnsSQL($table));
            $pk = 0;
            $binding = false;
            foreach ($queryColumns->getResult() as $column) {
                $col = "attributes['" . $column[0] . "'] = " . '"' . $column[0] . ',' . $this->getType($column[1]) . ',';
                $col .= (($column[2] == 'NO') ? 'not null' : '' ) . ',';
                if ($column[3] == 'PRI') {
                    ++$pk;
                }
                $col .= (($column[3] == 'PRI') ? 'primary' : (($column[3] == 'MUL') ? 'foreign' : '' ) );
                $col .= (($column[5] == 'auto_increment') ? ',identity' : '' ) . '"';
                $classes[$table]['columns'][] = $col;
            }
            if ($pk > 1) {
                $binding = $classes[$table]['binding'] = true;
            }
            $queryFK = $db->getQueryCommand($pf->getListTableForeignKeysSQL($table));
            foreach ($queryFK->getResult() as $fk) {
                $associationName = str_ireplace('fk_', '', $fk[0]);
                $associationName = str_ireplace('has_', '', $associationName);
                $associationName = str_ireplace("{$table}_", '', $associationName);
                $associationName = str_ireplace(array('1', '2', '3'), '', $associationName);
                $associationName = strtolower($associationName);
                if (!$binding) {
                    $assoc = "association['" . $associationName . "'] = " . '"\\' . $this->appName . "\\models\\" . $fk[2] . ',';
                    $assoc .= "oneToOne," . $fk[1] . ':' . $fk[3] . '"';
                    $classes[$table]['associations'][$associationName] = $assoc;
                    // cria a inversa oneToMany
                    $associationName2 = (($fk[2] != $associationName) ? $table . $associationName : $table) . 's';
                    $assoc2 = "association['" . $associationName2 . "'] = " . '"\\' . $this->appName . "\\models\\" . $table . ',oneToMany,' . $fk[3] . ':' . $fk[1] . '"';
                    $classes[$fk[2]]['associations'][$associationName2] = $assoc2;
                } else {
                    $classes[$table]['associations'][$associationName] = $fk[2];
                }
            }
            if ($binding) { // cria as inversas manyToMany
                foreach ($classes[$table]['associations'] as $associationName => $tableRef) {
                    foreach ($classes[$table]['associations'] as $associationName2 => $tableRef2) {
                        if ($associationName != $associationName2) {
                            $assoc2 = "association['" . $associationName2 . 's' . "'] = " . '"\\' . $this->appName . "\\models\\" . $tableRef2 . ',manyToMany,' . $table  . '"';
                            $classes[$tableRef]['associations'][$associationName . 's'] = $assoc2;
                        }
                    }
                }
            }
        }

        $dbName = $this->databaseName;
        $moduleName = $this->moduleName;
        $document = array();

        $document[] = "[globals]";
        $document[] = "database = \"{$dbName}\"";
        $document[] = "app = \"{$this->appName}\"";
        $document[] = "module = \"{$this->moduleName}\"";
        $document[] = '';

        foreach ($classes as $class) {
            if (($class['name'] != '') && (!$class['binding'])) {
                $document[] = '[' . $class['name'] . ']';
                $document[] = 'table = "' . $class['name'] . '"';
                foreach ($class['columns'] as $column) {
                    $document[] = $column;
                }
                foreach ($class['associations'] as $association) {
                    $document[] = $association;
                }
                $document[] = '';
            }
        }

        $map = implode("\n", $document);
        $filename = $this->baseDir . '/' . $this->fileScript;
        file_put_contents($filename, $map);
    }

    private function getType($dbType) {
        $type = 'string';
        $dbType = preg_replace("/\(.*\)/", "", $dbType);
        if ($dbType == 'int') {
            $type = 'integer';
        }
        return $type;
    }

}

?>