<?php

class MWizardScript {

    public $fileScript;
    public $baseDir;
    public $errors;
    public $ini;
    private $className;
    private $moduleName;
    private $databaseName;
    public $generatedMaps;

    public function setBaseDir($dir) {
        $this->baseDir = $dir;
    }

    public function setFile($file) {
        $this->fileScript = $file;
    }

    public function generate() {
        $this->errors = array();
        $this->ini = parse_ini_file($this->fileScript, true);
        $tab = '    ';
        $dbName = $this->ini['globals']['database'];
        $appName = $this->ini['globals']['app'];
        $moduleName = $this->ini['globals']['module'] ? : $appName;
        $actions[] = $tab . "'{$moduleName}' => array('{$moduleName}', '{$moduleName}/main/main', '{$moduleName}IconForm', '', A_ACCESS, array(";

        foreach ($this->ini as $className => $node) {
            $lowerClassName = strtolower($className);
            /*
            $originalClassName = $className;
            $className = strtolower($className);
            */
            if ($className == 'globals')
                continue;
            $properties = $methods = $validators = '';
            mdump('handleClass = ' . $className);
            $extends = $node['extends'];
            $log = $node['log'];

            if ($node['type'] == 'enumeration') {
                $consts = $modelName = $tableName = $properties = '';
                $attributes = $node['attributes'];
                foreach ($attributes as $attributeName => $attributeData) {
                    if ($attributeName == 'model') {
                        $modelName = $attributeData;
                    }
                    if ($attributeName == 'table') {
                        $tableName = $attributeData;
                    }
                    if (($attributeName == 'model') || ($attributeName == 'table')) {
                        $attributeData = "\"{$attributeData}\"";
                    }
                    $properties .= "\n    protected static \$" . $attributeName . " = " . $attributeData . ";";
                }

                if ($tableName) {
                    $sessionId = Manager::getSession()->getId();
                    $url = Manager::getAppURL($appName, $moduleName . '/tabelageral/getenumeration/' . $tableName . "?ajaxResponseType=JSON", true);
                    //mdump($url);
                    if ($stream = fopen($url, 'r')) {
                        $result = MJSON::decode(stream_get_contents($stream));
                        $constants = $result['data']['result']['items'];
                        //mdump($constants);
                        foreach ($constants as $value) {
                            $consts .= "\n    const " . str_replace(' ','_',$value['name']) . " = " . $value['idTable'] . ";";
                        }
                        fclose($stream);
                    }
                } else {
                    $constants = $node['constants'];
                    foreach ($constants as $constantName => $constantData) {
                        $consts .= "\n    const " . $constantName . " = " . $constantData . ";";
                    }
                }

                $var = array();
                $var['class'] = $className;
                $var['model'] = $className;
                $var['module'] = $moduleName ? : $appName;
                $var['moduleName'] = $moduleName;
                $var['default'] = $node['default'] ? : 'DEFAULT';
                $var['constants'] = $consts;
                $var['properties'] = $properties;
                $var['comment'] = $comment;
                $var['package'] = $appName;
                $var['extends'] = $extends ? : '\MEnumBase';
                $var['description'] = $description;
                $this->generateEnumeration($className, $var);
                continue;
            }


            $document = $ormmap = $docassoc = $docattr = $attributes = array();
            $document[] = '';
            $document[] = $tab . 'public static function ORMMap() {';
            $document[] = '';
            $ormmap[] = $tab . $tab . 'return array(';
            $ormmap[] = $tab . $tab . $tab . "'class' => \get_called_class(),";
            $ormmap[] = $tab . $tab . $tab . "'database' => " . (substr($dbName,0,1) == "\\" ? $dbName . ',' : "'{$dbName}',");
            $tableName = $node['table'];
            $ormmap[] = $tab . $tab . $tab . "'table' => '{$tableName}',";
            if ($extends) {
                $ormmap[] = $tab . $tab . $tab . "'extends' => '{$extends}',";
            }

            $pk = '';
            $getterSetter = "\n\n    /**\n     * Getters/Setters\n     */";
            $attributes = $node['attributes'];
            foreach ($attributes as $attributeName => $attributeData) {
                $isPK = false;
                $at = explode(',', $attributeData);
                // atData:
                // 0 - column
                // 1 - type
                // 2 - null or not null
                // 3 - key type
                // 4 - generator
                $attribute = $tab . $tab . $tab . "'{$attributeName}' => array(";
                $attribute .= "'column' => '{$at[0]}'";
                if ($at[3]) {
                    $attribute .= ",'key' => '{$at[3]}'";
                    $isPK = $at[3] == 'primary';
                    if ($isPK) {
                        $pk = $attributeName;
                        if ($at[4]) {
                            $attribute .= ",'idgenerator' => '{$at[4]}'";
                        } else {
                            $attribute .= ",'idgenerator' => 'identity'";
                        }
                    }
                }
                if (($at[2] == 'not null') && (!$isPK)) {
                    $validators .= "\n    " . $tab . $tab . $tab . "'{$attributeName}' => array('notnull'),";
                }
                $attrType = $at[1];
                $attribute .= ",'type' => '{$attrType}'),";
                $properties .= "\n    /**\n     * {$attrComment}\n     * @var {$attrType} \n     */";
                $properties .= "\n    protected " . "\$" . $attributeName . ";";
                $getterSetter .= "\n    public function get" . ucfirst($attributeName) . "() {\n        return \$this->{$attributeName};\n    }\n";

                $setterBody = '';
                $lowerAttrType = strtolower($attrType);
                if ($lowerAttrType == 'currency') {
                    $setterBody = "if (!(\$value instanceof \\MCurrency)) {\n            \$value = new \\MCurrency((float) \$value);\n        }\n        ";
                } elseif ($lowerAttrType == 'date') {
                    $setterBody = "if (!(\$value instanceof \\MDate)) {\n            \$value = new \\MDate(\$value);\n        }\n        ";
                } elseif ($lowerAttrType == 'timestamp') {
                    $setterBody = "if (!(\$value instanceof \\MTimeStamp)) {\n            \$value = new \\MTimeStamp(\$value);\n        }\n        ";
                } elseif ($lowerAttrType == 'cpf') {
                    $setterBody = "if (!(\$value instanceof \\MCPF)) {\n            \$value = new \\MCPF(\$value);\n        }\n        ";
                } elseif ($lowerAttrType == 'cnpj') {
                    $setterBody = "if (!(\$value instanceof \\MCNPJ)) {\n            \$value = new \\MCNPJ(\$value);\n        }\n        ";
                } elseif ($lowerAttrType == 'boolean') {
                    $setterBody = "\$value = ((\$value != '0') && (\$value != 0) && (\$value != '')) ? '1' : '0';\n        ";
                } elseif (strpos($lowerAttrType, 'enum') !== false) {
                    $setterBody = "\$valid = false;\n".
                        "        if (empty(\$value)) {\n".
                        "            \$config = \$this->config();\n".
                        "            \$valid = !array_search('notnull',\$config['validators']['{$attributeName}']);\n".
                        "        }\n".
                        "        if (!(\$valid || {$attrType}Map::isValid(\$value))) {\n".
                        "            throw new \EModelException('Valor inválido para a Enumeração {$attrType}');\n".
                        "        }\n        ";
                }

                if ($isPK) {
                    //$setterBody .= "\$this->{$attributeName} = (\$value ? : NULL);";
                    $setterBody .= "\$this->{$attributeName} = \$value;";
                } else {
                    $setterBody .= "\$this->{$attributeName} = \$value;";
                }

                $getterSetter .= "\n    public function set" . ucfirst($attributeName) . "(\$value) {\n        {$setterBody}\n    }\n";

                $docattr[] = $tab . $attribute;
            }

            $description = $node['description'] ? : $pk;

            $properties .= "\n\n    /**\n     * Associations\n     */";

            $docassoc = array();
            $associations = $node['associations'];
            if (is_array($associations)) {
                foreach ($associations as $associationName => $associationData) {
                    $assoc = explode(',', $associationData);
                    // assoc:
                    // 0 - toClass
                    // 1 - cardinality
                    // 2 - keys or associative
                    $association = $tab . $tab . $tab . "'{$associationName}' => array(";
                    $association .= "'toClass' => '{$assoc[0]}'";
                    $association .= ", 'cardinality' => '{$assoc[1]}' ";
                    if ($assoc[1] == 'manyToMany') {
                        $association .= ", 'associative' => '{$assoc[2]}'), ";
                    } else {
                        $association .= ", 'keys' => '{$assoc[2]}'), ";
                    }

                    $properties .= "\n    protected " . "\$" . $associationName . ";";
                    $type = $params['cardinality'] == 'oneToOne' ? $params['toClass'] : 'Association';
                    $methods .= "    /**\n     *\n     * @return {$type}\n     */\n    public function get" . ucfirst($associationName) . "() {\n        if (is_null(\$this->{$associationName})){\n            \$this->retrieveAssociation(\"{$associationName}\");\n        }\n        return  \$this->{$associationName};\n    }\n";
                    $methods .= "    /**\n     *\n     * @param {$type} \$value\n     */\n    public function set" . ucfirst($associationName) . "(\$value) {\n        \$this->{$associationName} = \$value;\n    }\n";
                    $methods .= "    /**\n     *\n     * @return {$type}\n     */\n    public function getAssociation" . ucfirst($associationName) . "() {\n        \$this->retrieveAssociation(\"{$associationName}\");\n    }\n";
                    $docassoc[] = $tab . $association;
                }
            }
            $ormmap[] = $tab . $tab . $tab . "'attributes' => array(";
            foreach ($docattr as $attr) {
                $ormmap[] = $attr;
            }
            $ormmap[] = $tab . $tab . $tab . "),";
            $ormmap[] = $tab . $tab . $tab . "'associations' => array(";
            foreach ($docassoc as $assoc) {
                $ormmap[] = $assoc;
            }
            $ormmap[] = $tab . $tab . $tab . ")";
            $ormmap[] = $tab . $tab . ");";

            $ormmapdef = implode("\n", $ormmap);

            $document[] = $ormmapdef;
            $document[] = $tab . "}";

            $map = implode("\n", $document);
            $configLog = "array( " . $log . " ),";
            $configValidators = "array(" . $validators . "\n            ),";
            $configConverters = "array()";

            // generate PHP class
            $var = array();
            $var['class'] = $className;
            $var['model'] = $className;
            $var['module'] = $moduleName ? : $appName;
            $var['properties'] = $properties;
            $var['methods'] = $getterSetter . $methods;
            $var['comment'] = $comment;
            $var['package'] = $appName;
            $var['ormmap'] = $map;
            $var['extends'] = $extends;
            $var['description'] = $description;
            $var['lookup'] = $description;
            $var['configLog'] = $configLog;
            $var['configValidators'] = $configValidators;
            $var['configConverters'] = $configConverters;

            // Create Model & Map

            $template = new MWizardTemplate();
            $template->setVar($var);
            $template->setTemplate('/public/files/templates/map.php');
            $template->applyClass();
            $template->saveResult("{$moduleName}/models/map/{$className}Map.php", $this->baseDir);

            $template = new MWizardTemplate();
            $template->setVar($var);
            $template->setTemplate('/public/files/templates/model.php');
            $template->applyClass();
            $template->saveResult("{$moduleName}/models/{$className}.php", $this->baseDir);

            // Create CRUD
            $fileName = array();
            $fileName[] = array('public/files/templates/formBase.xml', "{$moduleName}/views/{$lowerClassName}/formBase.xml");
            $fileName[] = array('public/files/templates/formFind.xml', "{$moduleName}/views/{$lowerClassName}/formFind.xml");
            $fileName[] = array('public/files/templates/formNew.xml', "{$moduleName}/views/{$lowerClassName}/formNew.xml");
            $fileName[] = array('public/files/templates/formObject.xml', "{$moduleName}/views/{$lowerClassName}/formObject.xml");
            $fileName[] = array('public/files/templates/formUpdate.xml', "{$moduleName}/views/{$lowerClassName}/formUpdate.xml");
            $fileName[] = array('public/files/templates/lookup.xml', "{$moduleName}/views/{$lowerClassName}/lookup.xml");
            $fileName[] = array('public/files/templates/fields.xml', "{$moduleName}/views/{$lowerClassName}/fields.xml");
            $fileName[] = array('public/files/templates/controller.php', "{$moduleName}/controllers/{$className}Controller.php");
            $template = new MWizardTemplate();
            $var = array();
            $var['model'] = $className;
            $var['lookup'] = $description;
            $var['module'] = $moduleName ? : $appName;
            $template->setVar($var);
            
            $template->setFields(eval(stripslashes($ormmapdef)));
            foreach ($fileName as $f) {
                $template->setTemplate($f[0]);
                $template->apply();
                $template->saveResult($f[1], $this->baseDir);
            }

            // define actions
            $upperClass = ucFirst($className);
            $actions[] = $tab . $tab . "'{$className}' => array('{$upperClass}', '{$moduleName}/{$className}/main', '{$moduleName}IconForm', '', A_ACCESS, array()),";
        }
        $actions[] = $tab . "))\n";

        $var['module'] = $moduleName ? : $appName;

        // create Actions

        $var['actions'] = implode("\n", $actions);
        $template = new MWizardTemplate();
        $template->setVar($var);
        $template->setTemplate('/public/files/templates/actions.php');
        $template->applyClass();
        $template->saveResult("{$moduleName}/conf/actions.php", $this->baseDir);


        // create Conf
        $template = new MWizardTemplate();
        $template->setTemplate('/public/files/templates/conf.php');
        $template->applyClass();
        $template->saveResult("{$moduleName}/conf/conf.php", $this->baseDir);


        // create Main
        $template = new MWizardTemplate();
        $template->setVar($var);
        $template->setTemplate('/public/files/templates/main.xml');
        $template->applyClass();
        $template->saveResult("{$moduleName}/views/main/main.xml", $this->baseDir);

        $template->setTemplate('/public/files/templates/MainController.php');
        $template->applyClass();
        $template->saveResult("{$moduleName}/controllers/MainController.php", $this->baseDir);
    }

    public function generateEnumeration($className, $var) {

        // Create Model & Map

        $template = new MWizardTemplate();
        $template->setVar($var);
        $template->setTemplate('/public/files/templates/enummap.php');
        $template->applyClass();
        $template->saveResult("{$var['moduleName']}/models/map/{$className}map.php", $this->baseDir);

        $template = new MWizardTemplate();
        $template->setVar($var);
        $template->setTemplate('/public/files/templates/enummodel.php');
        $template->applyClass();
        $template->saveResult("{$var['moduleName']}/models/{$className}.php", $this->baseDir);
    }

}

?>