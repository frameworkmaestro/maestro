<?php

use exemplos\models as models;

Manager::import('exemplos\models\*');

class RelatoriosController extends MController
{

    public function formExemploCSV()
    {
        $this->render();
    }

    public function repExemploCSV()
    {
        $usuario = new models\Usuario();
        $this->data->result = $usuario->listExemploCSV()->asQuery()->getResult();
        if ($this->data->result)
        {
            $this->renderWindow();
        }
        else
        {
            $this->renderPrompt('error', 'Nenhum registro encontrado.');
        }
    }

    public function formEzPDF()
    {
        $this->render();
    }

    public function repEzPDFFontes()
    {
        $this->renderWindow();
    }

    public function repEzPDFLinhas()
    {
        $this->renderWindow();
    }

    public function formExcel()
    {
        $this->data->options = array('Opção A' => 'Opção A', 'Opção B' => 'Opção B', 'Opção C' => 'Opção C', 'Opção D' => 'Opção D', 'Opção E' => 'Opção E');
        $this->data->planilha1 = array(
            array('12', '13', 'Opção A', '14'),
            array('22', '23', 'Opção B', '24'),
            array('32', '43', 'Opção C', '54'),
            array('62', '73', 'Opção A', '84'),
            array('32', '43', 'Opção C', '54'),
        );
        $this->data->planilha2 = array(
            array('22', '23', 'Opção B', '24'),
            array('12', '13', 'Opção A', '14')
        );
        $this->render();
    }

    public function repExemploExcel()
    {

        $this->renderWindow();
    }

}