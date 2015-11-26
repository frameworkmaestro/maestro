<?php

/**
 * Created by PhpStorm.
 * User: felipe
 * Date: 30/01/2015
 * Time: 10:40
 */
class SQLTemplate
{

    private $app;
    private $model;

    public function __construct($app,$model=false){
        $this->app = $app;
        $this->model = $model;
    }

    public function globals($db,$app,$module){
        return "[globals]" . PHP_EOL . "database = \"{$db}\"" . PHP_EOL . "app = \"{$app}\""  . PHP_EOL . "module = \"{$module}\"";
    }

    public function column($name,$primitive,$notnull=false,$pk=false,$fk=false) {
        $type = $this->evalType($primitive);
        $primary = $pk ? "primary,identity" : "";
        $foreign = $fk ? "foreign" : "";
        $null = $notnull ? "not null" : "";
        return "attributes['{$name}'] = \"{$name},{$type},{$null},{$primary},{$foreign}\"";
    }

    public function table($name){
        return "[$name]" . PHP_EOL . "table = \"$name\"";
    }

    public function association($name,$cardinality,$from,$to=false){
        $path = $this->model ? "{$this->app}\\{$this->model}\\models\\{$name}" : "{$this->app}\\models\\{$name}";
        $relation = $to ? "{$from}:{$to}" : $from;
        if($cardinality == 'manyToMany' || $cardinality == 'oneToMany'){
            $name .= 's';
        }
		$name = strtolower($name);
        return "associations['{$name}'] = \"{$path},{$cardinality},{$relation}\"";
    }

    public function evalType($type){
        if(preg_match("/VARCHAR/i",$type)){
            return "string";
        }else if(preg_match("/int/i",$type)){
            return "integer";
        }else if(preg_match("/date/i",$type)){
            return "date";
        }else if(preg_match("/timestamp/i",$type)){
            return "timestamp";
        }else if(preg_match("/DATETIME/i",$type)){
            return "timestamp";
        }
        else{
            return "string";
        }
    }

}