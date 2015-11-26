<?php

class repExemploCSV extends MJavaJasperReport {

    public function load() {
        $report = 'repExemploCSV.jrxml';
        $logo = Manager::getPublicPath('exemplos','', 'images/logo.png');
        $parametros['logo'] = $logo;//str_replace("/", "\\", $logo);
        $parametros['instituicao'] = Manager::getConf('instituicao');
        $parametros['param1'] = $this->data->param1;
        $parametros['param2'] = $this->data->param2;
        $parametros['param3'] = $this->data->param3;
        $parametros['param4'] = $this->data->param4;
        $url = $this->executeCSV($this->data->result, $report, $parametros);
        $this->page->window($url);
    }

}

?>
