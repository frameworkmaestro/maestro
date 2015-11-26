<?php

class MWizardORM {

    public $fileXMI;
    public $baseDir;
    private $nodes;
    private $className;
    private $moduleName;
    private $databaseName;
    private $package;
    public $generatedMaps;
    public $xpath;
    public $errors;

    public function setBaseDir($dir) {
        $this->baseDir = $dir;
    }

    public function setFile($fileXMI) {
        $this->fileXMI = $fileXMI;
    }

    public function setModuleName($name) {
        $this->moduleName = $name;
    }

    public function setDatabaseName($name) {
        $this->databaseName = $name;
    }

    public function setPackage($name) {
        $this->package = $name;
    }

    public function getGeneratedMaps() {
        sort($this->generatedMaps);
        return $this->generatedMaps;
    }

    public function generate() {
        $this->errors = array();
        $this->generatedMaps = array();
        $doc = new domDocument();
        $doc->load($this->fileXMI);

        $this->xpath = new DOMXpath($doc);

// example 1: for everything with an id
//$elements = $xpath->query("//*[@id]");
// example 2: for node data in a selected id
//$elements = $xpath->query("/html/body/div[@id='yourTagIdHere']");
// example 3: same as above with wildcard


        $this->parse($doc);
        $this->handleAssociation();
        $this->handleClassPK();
        $this->handleClassModule();
        $this->handleClassComment();
        $this->handleClassGeneralization();

        $elements = $this->xpath->query("//ownedMember[@name='{$this->package}']/ownedMember[@xmi:type='uml:Class']");
        if ($elements->length > 0) {
            $this->handleClass($elements);
            $this->handleAssociativeClass();
        }
    }

    private function parse($domNode) {
        $childDomNode = $domNode->firstChild;
        while ($childDomNode) {
            if ($childDomNode->nodeType == XML_ELEMENT_NODE) {
                $this->parseNode($childDomNode);
            }
            $childDomNode = $childDomNode->nextSibling;
        }
    }

    private function parseNode($node) {
        if ($node->hasAttributes()) {
            $array = $node->attributes;
            $ok = false;
            foreach ($array AS $domAttribute) {
                if ($domAttribute->name == "type") {
                    if (($domAttribute->value == "uml:Association") ||
                            ($domAttribute->value == "uml:Class") ||
                            ($domAttribute->value == "uml:Property") ||
                            ($domAttribute->value == "dbTable") ||
                            ($domAttribute->value == "dbColumn") ||
                            ($domAttribute->value == "dbForeignKey") ||
                            ($domAttribute->value == "dbForeignKeyConstraint") ||
                            ($domAttribute->value == "uml:AssociationClass")) {
                        $ok = true;
                        $type = trim($domAttribute->value);
                    }
                }
                if ($domAttribute->name == "id") {
                    $id = trim($domAttribute->value);
                }
                if ($domAttribute->name == "foreignKey") {
                    $fk = trim($domAttribute->value);
                }
                if ($domAttribute->name == "relationshipEndModel") {
                    $rel = trim($domAttribute->value);
                }
                if ($type == "uml:AssociationClass") {
                    $type = "uml:Class";
                }
            }
            if ($ok) {
                $this->nodes[$type][$id] = $node;

                if ($type == "dbForeignKeyConstraint") {
                    $this->nodes[$type][$fk] = $node;
                }
                if ($type == "dbForeignKey") {
                    $this->nodes[$type][$rel] = $node;
                }
            }
        }
        if ($node->hasChildNodes()) {
            $this->parse($node);
        }
    }

    private function handleAssociation() {
        foreach ($this->nodes['uml:Association'] as $idA => $assoc) {
            $i = 0;
            $association = array();
            $a = $assoc->firstChild;
            while ($a) {
                if ($a->nodeType == XML_ELEMENT_NODE) {
                    if ($a->nodeName == 'memberEnd') {
                        $idref = $a->getAttributeNode('xmi:idref')->value;
                        $property = $this->nodes['uml:Property'][$idref];
                        $association[$i++] = $property;
                    }
                }
                $a = $a->nextSibling;
            }
            if ((count($association) == 0) ||
                    !($association[0] instanceof DomElement) || !($association[1] instanceof DomElement)) {
                $this->errors[] = 'Error at Association ' . $idA;
            } else {
                $class0 = $association[0]->getAttributeNode('type')->value;
                $this->nodes['Associations'][$class0][$idA] = $association;
                $class1 = $association[1]->getAttributeNode('type')->value;
                $this->nodes['Associations'][$class1][$idA] = $association;
            }
        }
    }

    private function handleClassPK() {
        $classNodes = $this->nodes['uml:Class'];
        foreach ($classNodes as $id => $node) {
            $n = $node->firstChild->nextSibling;
            while ($n) {
                if ($n->nodeType == XML_ELEMENT_NODE) {
                    if ($n->nodeName == 'ownedAttribute') {
                        if ($c = $this->getChild($n->firstChild->nextSibling, 'ormDetail')) {
                            $colId = $c->getAttributeNode('columnModel')->value;
                            $col = $this->nodes['dbColumn'][$colId];
                            if ($col->nodeType == XML_ELEMENT_NODE) {
                                if ($col->getAttributeNode('primaryKey')->value == 'true') {
                                    $this->nodes['classPK'][$colId] = $n->getAttributeNode('name')->value;
                                    $this->nodes['classPK'][$colId . '_type'] = $n->getAttributeNode('type');
                                    $this->nodes['PK'][$id] = array($n->getAttributeNode('name')->value, $col->getAttributeNode('name')->value);
                                }
                            }
                        }
                    }
                }
                $n = $n->nextSibling;
            }
        }
    }

    private function handleClassGeneralization() {
        $classNodes = $this->nodes['uml:Class'];
        foreach ($classNodes as $id => $node) {
            $n = $node->firstChild->nextSibling;
            while ($n) {
                if ($n->nodeType == XML_ELEMENT_NODE) {
                    if ($n->nodeName == 'generalization') {
                        $this->nodes['classGeneralization'][$id] = $n->getAttributeNode('general')->value;
                        $this->nodes['classEspecialization'][$n->getAttributeNode('general')->value][] = $id;
                    }
                }
                $n = $n->nextSibling;
            }
        }
    }

    private function handleClassComment() {
        $classNodes = $this->nodes['uml:Class'];
        foreach ($classNodes as $id => $node) {
            $n = $node->firstChild->nextSibling;
            while ($n) {
                if ($n->nodeType == XML_ELEMENT_NODE) {
                    if ($n->nodeName == 'ownedComment') {
                        if ($c = $this->getChild($n, 'body')) {
                            $this->nodes['classComment'][$id] = str_replace("\n", "\n * ", $c->nodeValue);
                        }
                    }
                }
                $n = $n->nextSibling;
            }
        }
    }

    private function handleClassModule() {
        $classNodes = $this->nodes['uml:Class'];
        foreach ($classNodes as $id => $node) {
//            $this->nodes['classModule'][$id] = $this->moduleName;
            $package = $node->parentNode->getAttributeNode('name')->value;
            $moduleName = strtolower(str_replace('_Classes', '', $package));
            $this->nodes['classModule'][$id] = $moduleName;
        }
    }

    function handleClass($elements) {
//        $classNodes = $this->nodes['uml:Class'];
        $tab = '    ';
        $classNodes = $elements;
        $dbName = $this->databaseName;
        foreach ($classNodes as $node) {
            $properties = $methods = '';
            $id = $node->getAttributeNode('xmi:id')->value;
            $this->moduleName = $moduleName = $this->nodes['classModule'][$id];
            $this->className = $className = strtolower($node->getAttributeNode('name')->value);
            //mdump('handleClass = ' . $className);


            $document = $docassoc = $docattr = $attributes = array();
            $document[] = '';
            $document[] = $tab . 'public static function ORMMap() {';
            $document[] = '';
            $document[] = $tab . $tab . 'return array(';
            $document[] = $tab . $tab . $tab . "'class' => \get_called_class(),";
            $document[] = $tab . $tab . $tab . "'database' => '{$dbName}',";
            if ($t = $this->getChild($node->firstChild->nextSibling, 'ormDetail')) {
                $tableId = $t->getAttributeNode('tableModel')->value;
                $table = $this->nodes['dbTable'][$tableId];
                if ($table->nodeType == XML_ELEMENT_NODE) {
                    $tableName = $table->getAttributeNode('name')->value;
                    $document[] = $tab . $tab . $tab . "'table' => '{$tableName}',";
                }
            }

            $extends = '';
            if ($generalization = $this->nodes['classGeneralization'][$id]) {
                $moduleSuperClass = $this->nodes['classModule'][$generalization];
                $superClass = $this->nodes['uml:Class'][$generalization];
                $extends = "\\" . $moduleSuperClass . "\models\\" . $superClass->getAttributeNode('name')->value;
                $document[] = $tab . $tab . $tab . "'extends' => '{$extends}',";
                $reference = $this->getChild($node->firstChild->nextSibling, 'ormDetail');
                if ($reference) {
                    // //mdump('reference='.$reference->getAttributeNode('inheritanceStrategy')->value);
                    ////mdump($this->nodes['PK'][$generalization]);
                    $key = $this->nodes['PK'][$generalization];
                    $docattr[] = $tab . $tab . $tab . $tab . "'{$key[0]}' => array('column' => '{$key[1]}', 'key' => 'reference'),";
                }
            }

            $comment = $this->nodes['classComment'][$id];

            $noGenerator = $this->hasChild($node->firstChild->nextSibling, 'appliedStereotype', "Class_ORM No Generator_id");
            $idIdentity = $this->hasChild($node->firstChild->nextSibling, 'appliedStereotype', "Class_ORM ID Identity_id");
            $getterSetter = '';

            $n = $node->firstChild->nextSibling;
            while ($n) {
                if ($n->nodeType == XML_ELEMENT_NODE) {
                    if ($n->nodeName == 'ownedAttribute') {
                        $at = $n->getAttributeNode('name')->value;
                        $attributes[$at] = $at;
                        $attribute = $tab . $tab . $tab . "'{$at}' => array(";

                        if ($cmt = $this->getChild($n, 'ownedComment')) {
                            $c = $this->getChild($cmt, 'body');
                            $attrComment = str_replace("\n", "\n * ", $c->nodeValue);
                        }

                        if ($c = $this->getChild($n->firstChild->nextSibling, 'ormDetail')) {
                            $colId = $c->getAttributeNode('columnModel')->value;
                            $col = $this->nodes['dbColumn'][$colId];
                            if ($col->nodeType == XML_ELEMENT_NODE) {
                                $colName = $col->getAttributeNode('name')->value;
                                $attribute .= "'column' => '{$colName}'";
                            }
                        }
                        $isPK = false;
                        if ($c = $this->getChild($n->firstChild->nextSibling, 'appliedStereotype')) {
                            if ($c->getAttributeNode('xmi:value')->value == 'Attribute_PK_id') {
                                $attribute .= ",'key' => 'primary'";
                                $isPK = true;

                                if (!$noGenerator) {
                                    if ($idIdentity) {
                                        $attribute .= ",'idgenerator' => 'identity'";
                                    } else {
                                        $attribute .= ",'idgenerator' => 'seq_{$tableName}'";
                                    }
                                }
                            }
                            //if ($c->getAttributeNode('xmi:value')->value == 'Attribute_UpperCase_id') {
                            //    $attribute .= ",'converter' => 'uppercase'";
                            //}
                        }

                        $attrType = str_replace("_id", "", $n->getAttributeNode('type')->value);

                        $properties .= "\n    /**\n     * {$attrComment}\n     * @var {$attrType} \n     */";
                        $properties .= "\n    protected " . "\$" . $at . ";";
                        $getterSetter .= "\n    public function get" . ucfirst($at) . "() {\n        return \$this->{$at};\n    }\n";

                        $setterBody = '';
                        $lowerAttrType = strtolower($attrType);
                        if ($lowerAttrType == 'currency') {
                           $setterBody = "if (!(\$value instanceof \\MCurrency)) {\n            \$value = new \\MCurrency((float) \$value);\n        }\n        ";
                        } elseif ($lowerAttrType == 'date') {
                           $setterBody = "if (!(\$value instanceof \\MDate)) {\n            \$value = new \\MDate(\$value);\n        }\n        ";
                        } elseif ($lowerAttrType == 'timestamp') {
                           $setterBody = "if (!(\$value instanceof \\MTimeStamp)) {\n            \$value = new \\MTimeStamp(\$value);\n        }\n        ";
                        } elseif ($lowerAttrType == 'boolean') {
                           $setterBody = "\$value = ((\$value != '0') && (\$value != 0) && (\$value != '')) ? '1' : '0';\n        ";
                        }

                        if ($isPK) {
                            $setterBody .= "\$this->{$at} = (\$value ? : NULL);";
                        } else {
                            $setterBody .= "\$this->{$at} = \$value;";
                        }

                        $getterSetter .= "\n    public function set" . ucfirst($at) . "(\$value) {\n        {$setterBody}\n    }\n";

                        $columnType = $this->getType($n->getAttributeNode('type'));
                        $attribute .= ",'type' => '{$columnType}'),";
                        $docattr[] = $tab . $attribute;
                    }
                }
                $n = $n->nextSibling;
            }

            $properties .= "\n\n    /**\n     * Associations\n     */";

            if (count($this->nodes['Associations'][$id])) {
                foreach ($this->nodes['Associations'][$id] as $idA => $association) {
                    $i = 0;
                    $j = 0;
                    $class0 = $association[0]->getAttributeNode('type')->value;
                    $name0 = trim($association[0]->getAttributeNode('name')->value);
                    $class1 = $association[1]->getAttributeNode('type')->value;
                    $name1 = trim($association[1]->getAttributeNode('name')->value);
                    $attribute = '';

                    if (($class0 == $id) && ($name1 != '')) {
                        $docassoc[] = $tab . $tab . $this->createAssociationNode($association[1], $association[0], $attribute, $params);

                        if (($attribute != '') && (!$attributes[$attribute[0]])) {
                            $properties .= "\n    protected " . "\$" . $attribute[0] . ";";
                            $getterSetter .= "\n    public function get" . ucfirst($attribute[0]) . "() {\n        return \$this->{$attribute[0]};\n    }\n";
                            $getterSetter .= "\n    public function set" . ucfirst($attribute[0]) . "(\$value) {\n        \$this->{$attribute[0]} = \$value;\n    }\n";
                            $docattr[] = $tab . $tab . $tab . $tab . "'{$attribute[0]}' => array('column' => '{$attribute[0]}', 'key' => 'foreign', 'type' => '{$attribute[2]}'),";
                            $attributes[$attribute[0]] = $attribute[0];
                        }

                        $target = $name1;
                        $properties .= "\n    protected " . "\$" . $target . ";";
                        $type = $params['cardinality'] == 'oneToOne' ? $params['toClass'] : 'Association';
                        $methods .= "    /**\n     *\n     * @return {$type}\n     */\n    public function get" . ucfirst($target) . "() {\n        if (is_null(\$this->{$target})){\n            \$this->retrieveAssociation(\"{$target}\");\n        }\n        return  \$this->{$target};\n    }\n";
                        $methods .= "    /**\n     *\n     * @param {$type} \$value\n     */\n    public function set" . ucfirst($target) . "(\$value) {\n        \$this->{$target} = \$value;\n    }\n";
                        $methods .= "    /**\n     *\n     * @return {$type}\n     */\n    public function getAssociation" . ucfirst($target) . "() {\n        \$this->retrieveAssociation(\"{$target}\");\n    }\n";
                    }
                    if (($class1 == $id) && ($name0 != '')) {
                        $docassoc[] = $tab . $tab . $this->createAssociationNode($association[0], $association[1], $attribute, $params);

                        if (($attribute != '') && (!$attributes[$attribute[0]])) {
                            $properties .= "\n    protected " . "\$" . $attribute[0] . ";";
                            $getterSetter .= "\n    public function get" . ucfirst($attribute[0]) . "() {\n        return \$this->{$attribute[0]};\n    }\n";
                            $getterSetter .= "\n    public function set" . ucfirst($attribute[0]) . "(\$value) {\n        \$this->{$attribute[0]} = \$value;\n    }\n";
                            $docattr[] = $tab . $tab . $tab . $tab . "'{$attribute[0]}' => array('column' => '{$attribute[0]}', 'key' => 'foreign', 'type' => '{$attribute[2]}'),";
                            $attributes[$attribute[0]] = $attribute[0];
                        }

                        $target = $name0;
                        $properties .= "\n    protected " . "\$" . $target . ";";
                        $type = $params['cardinality'] == 'oneToOne' ? $params['toClass'] : 'Association';
                        $methods .= "    /**\n     *\n     * @return {$type}\n     */\n    public function get" . ucfirst($target) . "() {\n        if (is_null(\$this->{$target})){\n            \$this->retrieveAssociation(\"{$target}\");\n        }\n        return  \$this->{$target};\n    }\n";
                        $methods .= "    /**\n     *\n     * @param {$type} \$value\n     */\n    public function set" . ucfirst($target) . "(\$value) {\n        \$this->{$target} = \$value;\n    }\n";
                        $methods .= "    /**\n     *\n     * @return {$type}\n     */\n    public function getAssociation" . ucfirst($target) . "() {\n        \$this->retrieveAssociation(\"{$target}\");\n    }\n";
                    }
                }
            }

            if (count($especializations = $this->nodes['classEspecialization'][$id])) {
                foreach ($especializations as $especialization) {
                    $moduleSubClass = $this->nodes['classModule'][$especialization];
                    $subClass = $this->nodes['uml:Class'][$especialization];
                    $subClassName = lcfirst($subClass->getAttributeNode('name')->value);
                    $subClassNameFull = "\\" . $moduleSubClass . "\models\\" . $subClassName;
                    $key = $this->nodes['PK'][$id];
                    $docassoc[] = $tab . $tab . $tab . $tab . "'{$subClassName}' => array('toClass' => '{$subClassNameFull}' , 'cardinality' => 'oneToOne' , 'keys' => '{$key[0]}:{$key[1]}' ),";
                }
            }

            $document[] = $tab . $tab . $tab . "'attributes' => array(";
            foreach ($docattr as $attr) {
                $document[] = $attr;
            }
            $document[] = $tab . $tab . $tab . "),";
            $document[] = $tab . $tab . $tab . "'associations' => array(";
            foreach ($docassoc as $assoc) {
                $document[] = $assoc;
            }
            $document[] = $tab . $tab . $tab . ")";
            $document[] = $tab . $tab . ");";
            $document[] = $tab . "}";

            /*
              $map = "/{$moduleName}/classes/map/{$className}.php";
              $this->generatedMaps[] = $map;
              file_put_contents($this->baseDir . $map, implode("\n", $document));
             */
            $map = implode("\n", $document);

            // generate PHP class
            $var['class'] = $className;
            $var['module'] = $moduleName;
            $var['properties'] = $properties;
            $var['methods'] = $getterSetter . $methods;
            $var['comment'] = $comment;
            $var['package'] = $this->package;
            $var['ormmap'] = $map;
            $var['extends'] = $extends;

            $fileName = array('/public/files/templates/map.php', "public/files/base/{$moduleName}/map/{$className}map.php");
            $template = new MWizardTemplate();
            $template->setVar($var);
            $template->setTemplate($fileName[0]);
            $template->applyClass();
            $template->saveResult($fileName[1]);

            $fileName = array('/public/files/templates/model.php', "public/files/base/{$moduleName}/{$className}.php");
            $template = new MWizardTemplate();
            $template->setVar($var);
            $template->setTemplate($fileName[0]);
            $template->applyClass();
            $template->saveResult($fileName[1]);
        }
    }

    private function handleAssociativeClass() {
        $classNodes = $this->nodes['AssociativeClass'];
        $dbName = $this->databaseName;
        if (count($classNodes)) {
            foreach ($classNodes as $className => $associations) {
                $a = $associations[0];
                $id = $a->getAttributeNode('xmi:id')->value;
                $relNode = $this->nodes['dbForeignKey'][$id];
                $tableId = $relNode->getAttributeNode('to')->value;
                $tableNode = $this->nodes['dbTable'][$tableId];
                $tableName = $tableNode->getAttributeNode('name')->value;
            }
        }
    }

    private function createAssociationNode($association, $myself, &$attribute, &$params) {
        $tab = '    ';
        $target = trim($association->getAttributeNode('name')->value);

        $at = $tab . $tab . "'{$target}' => array(";

        $autoAssociation = false;
        $to = $this->nodes['uml:Class'][$association->getAttributeNode('type')->value];
        if ($to->nodeType == XML_ELEMENT_NODE) {
            $module = $this->nodes['classModule'][$to->getAttributeNode('xmi:id')->value];
            $toClass = strtolower($to->getAttributeNode('name')->value);
            $at .= "'toClass' => " . "'\\{$module}\models\\{$toClass}' ";
            $from = $this->nodes['uml:Class'][$myself->getAttributeNode('type')->value];
            $fromClass = strtolower($from->getAttributeNode('name')->value);
            $autoAssociation = ($toClass == $fromClass);
        }
        $params['toClass'] = "\\{$module}\models\\{$toClass}";

        $c0 = $c1 = '';
        $lower = $this->getChild($association, 'lowerValue');
        $upper = $this->getChild($association, 'upperValue');
        if ($upper->nodeType == XML_ELEMENT_NODE) {
            $c0 = $upper->getAttributeNode('value')->value;
        } elseif ($lower->nodeType == XML_ELEMENT_NODE) {
            $c0 = $lower->getAttributeNode('value')->value;
        }

        $lower = $this->getChild($myself, 'lowerValue');
        $upper = $this->getChild($myself, 'upperValue');
        if ($upper->nodeType == XML_ELEMENT_NODE) {
            $c1 = $upper->getAttributeNode('value')->value;
        } elseif ($lower->nodeType == XML_ELEMENT_NODE) {
            $c1 = $lower->getAttributeNode('value')->value;
        }

        if (($c0 == '*') && ($c1 == '*')) {
            $cardinality = 'manyToMany';
        } elseif (($c0 == '*')) {
            $cardinality = 'oneToMany';
        } else {
            $cardinality = 'oneToOne';
        }
        $params['cardinality'] = $cardinality;

        $at .= ", 'cardinality' => '{$cardinality}' ";

        $deleteAutomatic = $saveAutomatic = $retrieveAutomatic = false;

        $node = $this->getChild($association->firstChild->nextSibling, 'appliedStereotype');
        while ($node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $deleteAutomatic |= strpos(strtolower($node->getAttribute('xmi:value')), 'deleteautomatic') !== false;
                $saveAutomatic |= strpos(strtolower($node->getAttribute('xmi:value')), 'saveautomatic') !== false;
                $retrieveAutomatic |= strpos(strtolower($node->getAttribute('xmi:value')), 'retrieveautomatic') !== false;
            }
            $node = $node->nextSibling;
        }

        if ($retrieveAutomatic) {
            $at .= ", 'retrieveAutomatic' => true ";
        }

        if ($saveAutomatic) {
            $at .= ", 'saveAutomatic' => true ";
        }

        if ($deleteAutomatic) {
            $at .= ", 'deleteAutomatic' => true ";
        }

        $createFK = $isPK = false;

        $fk = $this->getChild($association->firstChild->nextSibling, 'ormDetail');
        if ($fk->nodeType == XML_ELEMENT_NODE) {
            $fkm = $fk->getAttributeNode('foreignKeyModel')->value;
            if ($fkm != '') {
                $fknode = $this->nodes['dbForeignKeyConstraint'][$fkm];
                if ($fknode->nodeType == XML_ELEMENT_NODE) {
                    $refCol = $fknode->getAttributeNode('refColumn')->value;
                    $col = $this->nodes['dbColumn'][$refCol];
                    if ($col->nodeType == XML_ELEMENT_NODE) {
                        $pkName = $this->nodes['classPK'][$refCol];
                        $refType = $this->getType($this->nodes['classPK'][$refCol . '_type']);
                        $createFK = true;
                    }
                }
            }
        }


        if ($pkName == '') {
            $fk = $this->getChild($myself->firstChild->nextSibling, 'ormDetail');
            if ($fk->nodeType == XML_ELEMENT_NODE) {
                $fkm = $fk->getAttributeNode('foreignKeyModel')->value;
                if ($fkm != '') {
                    $fknode = $this->nodes['dbForeignKeyConstraint'][$fkm];
                    if ($fknode->nodeType == XML_ELEMENT_NODE) {
                        $refCol = $fknode->getAttributeNode('refColumn')->value;
                        $col = $this->nodes['dbColumn'][$refCol];
                        if ($col->nodeType == XML_ELEMENT_NODE) {
                            $refType = $this->getType($this->nodes['classPK'][$refCol . '_type']);
                            $pkName = $this->nodes['classPK'][$refCol];
                        }
                    }
                }
            }
        }

        if ($fknode) {
            if ($fknode->nodeType == XML_ELEMENT_NODE) {
                $fkCol = $fknode->parentNode->parentNode;
                $fkName = $fkCol->getAttributeNode('name')->value;
                $isPK = ($fkCol->getAttributeNode('primaryKey')->value == 'true');
            }
        }

        if ($cardinality == 'manyToMany') {
            $a = $association;
            $id = $a->getAttributeNode('xmi:id')->value;
            $relNode = $this->nodes['dbForeignKey'][$id];
            if (!$relNode) {
                $id = $myself->getAttributeNode('xmi:id')->value;
                $relNode = $this->nodes['dbForeignKey'][$id];
            }
            $tableId = $relNode->getAttributeNode('to')->value;
            $tableNode = $this->nodes['dbTable'][$tableId];
            $tableName = $tableNode->getAttributeNode('name')->value;
            $at .= ",'associative' => '{$tableName}' ";

            $keyName1 = $myself->getAttributeNode('name')->value;
            $keyName2 = $association->getAttributeNode('name')->value;
        } else {
            /*
            if ($autoAssociation) {
                $at .= ", 'keys' => '{$fkName}:{$pkName}' ";
                if ($fkName == '') {
                    $this->errors[] = $fromClass . ' - ' . $toClass . ': Chaves nulas';
                }
                $attribute = array($fkName, $fkName, $refType);
            } else*/
            if ($createFK) {
                $at .= ", 'keys' => '{$fkName}:{$pkName}' ";
                $attribute = array($fkName, $pkName, $refType);
                if ($fkName == '') {
                    $this->errors[] = $fromClass . ' - ' . $toClass . ': Chave FK nula';
                }
                if ($pkName == '') {
                    $this->errors[] = $fromClass . ' - ' . $toClass . ': Chaves PK nula';
                }
            } else {
                if ($fkName == '') {
                    $this->errors[] = $fromClass . ' - ' . $toClass . ': Chave FK nula';
                }
                if ($pkName == '') {
                    $this->errors[] = $fromClass . ' - ' . $toClass . ': Chaves PK nula';
                }
                $at .= ", 'keys' => '{$pkName}:{$fkName}' ";
            }
        }

        $tagged = $this->getChild($association->firstChild->nextSibling, 'taggedValue');
        if ($tagged) {
            if ($tagged->getAttributeNode('tag')->value == 'orderAttribute') {
                /*
                  $t = $document->createElement("orderAttribute");
                  $t->appendChild(new DOMText($tagged->getAttributeNode('value')->value));
                  $e->appendChild($t);
                 */
            }
        }
        $at .= "),";
        return $at;
    }

    private function getType($node) {
        $columnType = str_replace('_id', '', strtolower($node->value));
        if ($columnType == 'char') {
            $columnType = 'string';
        } elseif ($columnType == 'int') {
            $columnType = 'integer';
        }
        return $columnType;
    }

    private function getChild($node, $nodeName) {
        try {
            if (!$node)
                throw new Exception;
            if ($node->hasChildNodes()) {
                $n = $node->firstChild;
                while ($n) {
                    if ($n->nodeType == XML_ELEMENT_NODE) {
                        if ($n->nodeName == $nodeName) {
                            return $n;
                        }
                    }
                    $n = $n->nextSibling;
                }
            }
        } catch (Exception $e) {
            var_dump($e->getTraceAsString());
        }
        return NULL;
    }

    private function hasChild($node, $nodeName, $value = NULL) {
        $ok = $found = false;
        try {
            if (!$node)
                throw new Exception;
            if ($node->hasChildNodes()) {
                $n = $node->firstChild;
                while ($n && !$found) {
                    if ($n->nodeType == XML_ELEMENT_NODE) {
                        if ($n->nodeName == $nodeName) {
                            $found = $ok = true;
                            if ($value) {
                                $found = $ok = $n->getAttributeNode('xmi:value')->value == $value;
                            }
                        }
                    }
                    $n = $n->nextSibling;
                }
            }
        } catch (Exception $e) {
            var_dump($e->getTraceAsString());
        }
        return $ok;
    }

}

?>