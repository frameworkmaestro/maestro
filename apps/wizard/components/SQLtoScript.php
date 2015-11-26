<?php

/**
 * Created by PhpStorm.
 * User: felipe
 * Date: 30/01/2015
 * Time: 10:38
 */
class SQLtoScript
{

    private $script;
    private $db;
    private $app;
    private $model;

    public function __construct($db,$script,$app,$model) {
        $this->db = $db;
        $this->script = Manager::getAbsolutePath() . "/core/var/wizard/" . $script;; //"/home/fmatos/public_html/temp/library.sql";
        $this->app = $app;
        $this->model = $model;
    }

    public function run(){
        $template = new SQLTemplate($this->app,$this->model);

        $text = file_get_contents($this->script);

        preg_match_all("/(CREATE[ ]*TABLE[^\.]*\.`([\d\w_-]*)`[^;]*)/", $text, $matches);
        $associations = array();
        $script = array();
        $nn = array();
        foreach ($matches[0] as $index=>$stcreate) {
            $tableName = $matches[2][$index];
            $pkName = $this->getPrimaryKey($stcreate);
            if(is_array($pkName)){
                $nn[] = $this->getAssociations($stcreate);
                continue;
            }
            $fkNames = $this->getForeignKey($stcreate);
            $associations[$tableName] = $this->getAssociations($stcreate);
            $lines = explode(PHP_EOL, $stcreate);

            $script[$tableName]->pk = $pkName;
            $script[$tableName]->script = $template->table($tableName);
            foreach ($lines as $line) {
                preg_match_all("/^`[A-Za-z0-9_-]*`[^,]*/", trim($line), $stcolumns);
                if($stcolumns[0][0]){
                    preg_match("/`([\\d\\w]*)` ([\\d\\w\\(\\)]*) (UNSIGNED )?((NOT )?NULL)/",$stcolumns[0][0],$stcolumn);
                    $name = $stcolumn[1];
                    $type = $stcolumn[2];
                    $null = $stcolumn[4];
                    $pk = ($pkName == $name);
                    $fk = (array_search($name,$fkNames) !== false);
                    $attribute = $template->column($name,$type,$null,$pk,$fk);
                    $script[$tableName]->script .= PHP_EOL . $attribute;
                    //mdump($attribute);
                }
            }
        }
        foreach($associations as $tableName=>$arAssociation){
            if(is_array($arAssociation)){
                foreach($arAssociation as $ass){
                    $a1 = $template->association($ass->table,'oneToOne',$ass->fkName,$ass->reference);
                    $a2 = $template->association($tableName,'oneToMany',$ass->reference,$ass->fkName);
                    $script[$tableName]->script .= PHP_EOL . $a1;
                    $script[$ass->table]->script .= PHP_EOL . $a2;
                }
            }
        }
        foreach($nn as $n){
            if(is_array($arAssociation)){
                $a1 = $template->association($n[1]->table,'manyToMany',"{$n[0]->table}_{$n[1]->table}");
                $a2 = $template->association($n[0]->table,'manyToMany',"{$n[0]->table}_{$n[1]->table}");
                $script[$n[0]->table]->script .= PHP_EOL . $a1;
                $script[$n[1]->table]->script .= PHP_EOL . $a2;
            }
        }
        $total = $template->globals($this->db,$this->app,$this->model) . PHP_EOL . PHP_EOL;
        foreach($script as $part){
            $total .= $part->script . PHP_EOL . PHP_EOL;
        }
        $fileResult = ($this->model ?: $this->app) . '.txt';
        file_put_contents(Manager::getAbsolutePath() . "/core/var/wizard/{$fileResult}",$total);
    }

    public function getAssociations($stCreate){
        //preg_match_all("/FOREIGN KEY \(`([A-Za-z0-9_-]*)`\)[ \n]*REFERENCES[^.]*\.`([\d\w]*)` \(`([\d\w]*)`\)/", trim($stCreate), $groups);
        preg_match_all("/[.]*FOREIGN KEY \(`([A-Za-z0-9_-]*)`( )?\)[\s]*REFERENCES[^.]*\.`([\d\w_-]*)` \(`([\d\w]*)`( )?\)/", trim($stCreate), $groups);
        $associations = array();
        foreach($groups[1] as $index=>$fkName){
            $associations[$index]->fkName = $fkName;
        }
        foreach($groups[3] as $index=>$table){
            $associations[$index]->table = $table;
        }
        foreach($groups[4] as $index=>$reference){
            $associations[$index]->reference = $reference;
        }
        mdump($associations);
        return $associations;
    }

    public function getPrimaryKey($stCreate){
        preg_match_all("/PRIMARY KEY \(`([A-Za-z0-9_-]*)`(, `([A-Za-z0-9_-]*)`)?\)/", trim($stCreate), $result);
        if($result[3][0]){
            return array($result[1][0],$result[3][0]);
        }
        return $result[1][0];
    }

    public function getForeignKey($stCreate){
        preg_match_all("/[.]*FOREIGN KEY \(`([A-Za-z0-9_-]*)`/", trim($stCreate), $result);
        return $result[1];
    }


    //mdump($matches)
} 