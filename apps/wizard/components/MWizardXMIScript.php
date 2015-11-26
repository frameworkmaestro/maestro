<?php

class MWizardXMIScript {

    public $fileXMI;
    public $baseDir;
    private $nodes;
    private $className;
    private $appName;
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

    public function setAppName($name) {
        $this->appName = $name;
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

        $this->parse($doc);
        $this->handleAssociation();
        $this->handleAssociationClass();
        $this->handleClassPK();
        $this->handleClassModule();
        $this->handleClassComment();
        $this->handleClassGeneralization();

        $elements = $this->xpath->query("//ownedMember[@name='{$this->package}']/ownedMember[@xmi:type='uml:Class'] | " .
                " //ownedMember[@name='{$this->package}']/ownedMember[@xmi:type='uml:AssociationClass'] | " .
                " //ownedMember[@name='{$this->package}']/ownedMember[@xmi:type='uml:Enumeration'] ");

        if ($elements->length > 0) {
            $this->handleClass($elements);
            //$this->handleAssociativeClass();
        }
        else
        	throw new Exception("Não foi possível encontrar o Package {$this->package} no arquivo XMI.", 1);
        	
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
                            ($domAttribute->value == "uml:Enumeration") ||
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

    private function handleAssociationClass() {
        foreach ($this->nodes['uml:AssociationClass'] as $idA => $assoc) {
            $i = 0;
            $association = $associationExtra = array();
            $a = $assoc->firstChild;
            while ($a) {
                if ($a->nodeType == XML_ELEMENT_NODE) {
                    if ($a->nodeName == 'memberEnd') {
                        $idref = $a->getAttributeNode('xmi:idref')->value;
                        $property = $this->nodes['uml:Property'][$idref];
                        $association[$i] = $property;
                        $i++;
                    }
                }
                $a = $a->nextSibling;
            }
            if ((count($association) == 0) ||
                    !($association[0] instanceof DomElement) || !($association[1] instanceof DomElement)) {
                $this->errors[] = 'Error at Association ' . $idA;
            } else {
                $class0 = $association[0]->getAttributeNode('type')->value;
                $class1 = $association[1]->getAttributeNode('type')->value;
                $this->nodes['Associations'][$class0][$class1] = $association;
                $this->nodes['Associations'][$class1][$class0] = $association;
                $this->nodes['AssociativeClass'][$class0][$idA] = array(strtolower($assoc->getAttributeNode('name')->value), $idA);
                $this->nodes['AssociativeClass'][$class1][$idA] = array(strtolower($assoc->getAttributeNode('name')->value), $idA);
                $this->nodes['AssociativeClassAttribute'][$idA][$class0] = $class0;
                $this->nodes['AssociativeClassAttribute'][$idA][$class1] = $class1;
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
                                    $this->nodes['PK'][$id] = array($n->getAttributeNode('name')->value, $col->getAttributeNode('name')->value, $n->getAttributeNode('type'));
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
        $classNodes = $this->nodes['uml:Enumeration'];
        foreach ($classNodes as $id => $node) {
            $n = $node->firstChild->nextSibling;
            while ($n) {
                if ($n->nodeType == XML_ELEMENT_NODE) {
                    if ($n->nodeName == 'generalization') {
                        $this->nodes['classGeneralization'][$id] = $n->getAttributeNode('general')->value;
                        //$this->nodes['classEspecialization'][$n->getAttributeNode('general')->value][] = $id;
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
            $package = $node->parentNode->getAttributeNode('name')->value;
            $moduleName = strtolower(str_replace('_Classes', '', $package));
            $this->nodes['classModule'][$id] = $moduleName;
        }
        $classNodes = $this->nodes['uml:Enumeration'];
        foreach ($classNodes as $id => $node) {
            $package = $node->parentNode->getAttributeNode('name')->value;
            $moduleName = strtolower(str_replace('_Classes', '', $package));
            $this->nodes['classModule'][$id] = $moduleName;
        }
    }

    function handleClass($elements) {
        $tab = '    ';
        $classNodes = $elements;
        $dbName = $this->databaseName;
        $moduleName = $this->moduleName;
        $document = array();

        $document[] = "[globals]";
        $document[] = "database = \"{$dbName}\"";
        $document[] = "app = \"{$this->appName}\"";
        $document[] = "module = \"{$this->moduleName}\"";
        $document[] = '';

        foreach ($classNodes as $node) {
            $properties = $methods = '';
            $id = $node->getAttributeNode('xmi:id')->value;
            //$this->moduleName = $moduleName = $this->nodes['classModule'][$id];
            $classNameXMI = $node->getAttributeNode('name')->value;
            $this->className = $className = strtolower($classNameXMI);
            
            if ($className == 'menumdatabase') {
                continue;
            }
            
            mdump('handleClass = ' . $classNameXMI);

            $docassoc = $docattr = $attributes = array();
            $document[] = '[' . $classNameXMI . ']';
            if ($t = $this->getChild($node->firstChild->nextSibling, 'ormDetail')) {
                $tableId = $t->getAttributeNode('tableModel')->value;
                $table = $this->nodes['dbTable'][$tableId];
                if ($table->nodeType == XML_ELEMENT_NODE) {
                    $tableName = $table->getAttributeNode('name')->value;
                    $document[] = "table = \"{$tableName}\"";
                }
            }

            $extends = '';
            if ($generalization = $this->nodes['classGeneralization'][$id]) {
                $moduleSuperClass = $this->nodes['classModule'][$generalization];
                if ($node->getAttributeNode('xmi:type')->value != 'uml:Enumeration') {
                    $superClass = $this->nodes['uml:Class'][$generalization];
                    $extends = "\\" . $moduleSuperClass . "\models\\" . $superClass->getAttributeNode('name')->value;
                    $document[] = "extends = \"{$extends}\"";
                    $reference = $this->getChild($node->firstChild->nextSibling, 'ormDetail');
                    if ($reference) {
                        $key = $this->nodes['PK'][$generalization];
                        $columnType = $this->getType($key[2]);
                        $docattr[] = "attributes['{$key[0]}'] = \"{$key[1]},{$columnType},not null,reference\"";
                    }
                } else {
                    $superClass = $this->nodes['uml:Enumeration'][$generalization];
                    if ($superClass->getAttributeNode('name')->value != 'MEnumDatabase') {
                        $extends = "\\" . $moduleSuperClass . "\models\\" . $superClass->getAttributeNode('name')->value;
                    } else {
                        $extends = "\\" . $superClass->getAttributeNode('name')->value;
                    }
                    $document[] = "extends = \"{$extends}\"";
                }
            }

            $comment = $this->nodes['classComment'][$id];

            $noGenerator = $this->hasChild($node->firstChild->nextSibling, 'appliedStereotype', "Class_ORM No Generator_id");
            $idIdentity = $this->hasChild($node->firstChild->nextSibling, 'appliedStereotype', "Class_ORM ID Identity_id");
            $getterSetter = '';
            $pk = '';
            if ($node->getAttributeNode('xmi:type')->value == 'uml:Enumeration') {
                $document[] = "type = \"enumeration\"";
            } else {
                $document[] = "log = \"\"";
                $document[] = "description = \"\"";
            }
            $n = $node->firstChild->nextSibling;
            while ($n) {
                if ($n->nodeType == XML_ELEMENT_NODE) {
                    if ($n->nodeName == 'ownedAttribute') {
                        if ($n->getAttributeNode('association')->value != '') { // e uma associação, não um atributo
                            $n = $n->nextSibling;
                            continue;
                        }

                        $at = $n->getAttributeNode('name')->value;
                        $attributes[$at] = $at;
                        $attribute = "attributes['{$at}'] = \"";

                        if ($cmt = $this->getChild($n, 'ownedComment')) {
                            $c = $this->getChild($cmt, 'body');
                            $attrComment = str_replace("\n", "\n * ", $c->nodeValue);
                        }

                        if ($c = $this->getChild($n->firstChild->nextSibling, 'ormDetail')) {
                            $colId = $c->getAttributeNode('columnModel')->value;
                            $col = $this->nodes['dbColumn'][$colId];
                            if ($col->nodeType == XML_ELEMENT_NODE) {
                                $colName = $col->getAttributeNode('name')->value;
                                $attribute .= "{$colName}";
                            }
                        }

                        $columnType = $this->getType($n->getAttributeNode('type'));
                        $attribute .= ",{$columnType}";

                        $isPK = false;
                        if ($c = $this->getChild($n->firstChild->nextSibling, 'appliedStereotype')) {
                            if ($c->getAttributeNode('xmi:value')->value == 'Attribute_PK_id') {
                                $attribute .= ",not null,primary";
                                $isPK = true;
                                $pk = $at;
                                if (!$noGenerator) {
                                    if ($idIdentity) {
                                        $attribute .= ",identity";
                                    } else {
                                        $attribute .= ",seq_{$tableName}";
                                    }
                                }
                            }
                        }

                        $docattr[] = $attribute . "\"";
                    } else if ($n->nodeName == 'ownedLiteral') {
                        $at = $n->getAttributeNode('name')->value;
                        mdump($at);
                        $attributes[$at] = $at;
                        $c = $n->firstChild->nextSibling;
                        if ($c->nodeType == XML_ELEMENT_NODE) {
                            $value = $c->getAttributeNode('value')->value;
                            if ($at == 'model') {
                                $attribute = "attributes['{$at}'] = \"\\{$moduleName}\\models\\{$value}\"";
                            } elseif ($at == 'table') {
                                $attribute = "attributes['{$at}'] = \"{$value}\"";
                            } else {
                                $attribute = "constants['{$at}'] = \"{$value}\"";
                            }
                        }
                        if ($enumDefault == '') {
                            $enumDefault = 'default = ' . "\"{$at}\"";
                            $docattr[] = $enumDefault;
                        }
                        $docattr[] = $attribute;
                    }
                }
                $n = $n->nextSibling;
            }

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
                        $docassoc[] = $this->createAssociationNode($association[1], $association[0], $attribute, $params);

                        if (($attribute != '') && (!$attributes[$attribute[0]])) {
                            $docattr[] = "attributes['{$attribute[0]}'] = \"{$attribute[0]},{$attribute[2]},,foreign\"";
                            $attributes[$attribute[0]] = $attribute[0];
                        }
                    }
                    if (($class1 == $id) && ($name0 != '')) {
                        $docassoc[] = $this->createAssociationNode($association[0], $association[1], $attribute, $params);

                        if (($attribute != '') && (!$attributes[$attribute[0]])) {
                            $docattr[] = "attributes['{$attribute[0]}'] = \"{$attribute[0]},{$attribute[2]},,foreign\"";
                            $attributes[$attribute[0]] = $attribute[0];
                        }
                    }
                }
            }

            if (count($this->nodes['AssociativeClass'][$id])) {
                foreach ($this->nodes['AssociativeClass'][$id] as $idA => $association) {
                    $toClass = $association[0];
                    $module = $this->nodes['classModule'][$id];
                    $docattr[] = "associations['{$toClass}s'] = \"{$module}\models\\{$toClass},oneToMany,{$pk}:{$pk}\"";
                }
            }

            if (count($this->nodes['AssociativeClassAttribute'][$id])) {
                foreach ($this->nodes['AssociativeClassAttribute'][$id] as $idA => $associatedClass) {
                    $atName = $this->nodes['PK'][$associatedClass][0];
                    $atCol = $this->nodes['PK'][$associatedClass][0];
                    $atType = $this->nodes['PK'][$associatedClass][0];
                    $docattr[] = "attributes['{$atName}'] = \"{$atCol},integer,,foreign\"";
                }
            }

            if (count($especializations = $this->nodes['classEspecialization'][$id])) {
                foreach ($especializations as $especialization) {
                    $moduleSubClass = $this->nodes['classModule'][$especialization];
                    $subClass = $this->nodes['uml:Class'][$especialization];
                    $subClassName = lcfirst($subClass->getAttributeNode('name')->value);
                    $subClassNameFull = "\\" . $moduleSubClass . "\models\\" . $subClassName;
                    $key = $this->nodes['PK'][$id];
                    $docassoc[] = "associations['{$subClassName}'] = \"{$subClassNameFull},oneToOne,{$key[0]}:{$key[1]}\"";
                }
            }

            foreach ($docattr as $attr) {
                $document[] = $attr;
            }
            foreach ($docassoc as $assoc) {
                $document[] = $assoc;
            }
            $document[] = '';
        }

        $map = implode("\n", $document);
        $filename = $this->baseDir . '/' . $this->moduleName . '.txt';


        file_put_contents($filename, $map);
    }

    /*
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
     */

    private function createAssociationNode($association, $myself, &$attribute, &$params) {
        $tab = '    ';
        $target = trim($association->getAttributeNode('name')->value);

        $at = "associations['{$target}'] = \"";

        $autoAssociation = false;
        $to = $this->nodes['uml:Class'][$association->getAttributeNode('type')->value];
        if ($to->nodeType == XML_ELEMENT_NODE) {
            $module = $this->nodes['classModule'][$to->getAttributeNode('xmi:id')->value];
            $toClass = strtolower($to->getAttributeNode('name')->value);
            $from = $this->nodes['uml:Class'][$myself->getAttributeNode('type')->value];
            $fromClass = strtolower($from->getAttributeNode('name')->value);
            $autoAssociation = ($toClass == $fromClass);
        }
        $at .= "{$module}\models\\{$toClass}";

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

        $at .= ",{$cardinality}";

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
            $at .= ",{$tableName}\"";

            $keyName1 = $myself->getAttributeNode('name')->value;
            $keyName2 = $association->getAttributeNode('name')->value;
        } else {
            if ($createFK) {
                $at .= ",{$fkName}:{$pkName}\"";
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
                $at .= ",{$pkName}:{$fkName}\"";
            }
        }
        return $at;
    }

    private function getType($node) {
        $value = strtolower($node->value);
        if (strpos($value, '_id') !== false) {
            $columnType = str_replace('_id', '', $value);
            if ($columnType == 'char') {
                $columnType = 'string';
            } elseif ($columnType == 'int') {
                $columnType = 'integer';
            }
        } else {
            $enum = $this->nodes['uml:Enumeration'][$node->value];
            if ($enum) {
                $columnType = $enum->getAttributeNode('name')->value;
            }
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