<?php

class repExemploExcel extends MForm{

    public function load() {
        $exporter = new MExporter('xls');
        $planilhas['Planilha 1'] = $this->data->planilha1;
        $planilhas['Planilha 2'] = $this->data->planilha2;
        $url = $exporter->execute($planilhas);
        $this->page->window($url);
    }

}

?>
