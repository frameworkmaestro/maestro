<?php

class TypesController extends MController {

    public function currency() {
        $value = 123456.78;
        $this->data->value = 'Valor = ' . $value;
        $currency = Manager::currency($value);
        $this->data->currency = 'Currency = ' . $currency;
        $formated = $currency->format();
        $this->data->formated = 'Formatado = ' . $formated;
        $currency = Manager::currency($formated);
        $value = $currency->getValue();
        $this->data->getValue = 'Get Value = ' . $value;
        $currency->setValue(87654321.09);
        $this->data->setValue = 'Após setValue = ' . $currency;
        $currency->setValue(-654.32);
        $this->data->setValueNeg = 'Após setValue negativo = ' . $currency;
        $currency = Manager::currency('-R$ 123.345,67');
        $value = $currency->getValue();
        $this->data->valueNeg = 'Valor negativo = ' . $value;
        $valor1 = Manager::currency(10.9008900015);
        $valor2 = Manager::currency(10.9008900017);
        mdump($valor1->getValue() == $valor2->getValue() ? 'Valores iguais' : 'Valores diferentes');
        $valor1 = Manager::currency(10.9018900015);
        $valor2 = Manager::currency(10.9068900017);
        mdump($valor1->getValue() == $valor2->getValue() ? 'Valores iguais' : 'Valores diferentes');
        $this->render();
    }

    public function dates() {
        // Create a MDate object
        $date = Manager::date('01/07/2011');
        // Show formatted
        $this->data->baseDate = "Data base para o exemplo: " . $date->format();
        // Clonning
        $newDate = $date->copy();
        $this->data->cloneDate = "Clone da data base: " . $newDate->format();
        // Adding interval
        $date->add('P2D');
        $this->data->addDate = "Adicionando 2 dias: " . $date->format();
        // Subtracting interval
        $date->sub('P5D');
        $this->data->subDate = "Subtraindo 5 dias: " . $date->format();
        // Difference between dates
        $diff = $date->diff('23/07/2011');
        $this->data->diffDate1 = "Diferença entre " . $date->format() . ' e 23/07/2011 = ' . $diff . ' dias';
        $diff = $date->diff('13/10/2011','%m meses e %d dias');
        $this->data->diffDate2 = "Diferença entre " . $date->format() . ' e 13/10/2011 = ' . $diff;
        // Comparing dates
        $date = Manager::date('01/07/2011');
        $compare = $date->compare('>','15/06/2011');
        $this->data->compDate1 = $date . ' > 15/06/2011 ? ' . ($compare ? 'SIM': 'NÃO');
        $compare = $date->compare('=','01/07/2011');
        $this->data->compDate2 =  $date . ' = 01/07/2011 ? ' . ($compare ? 'SIM': 'NÃO');
        $compare = $date->compare('<','01/07/2011');
        $this->data->compDate3 = $date . ' < 01/07/2011 ? ' . ($compare ? 'SIM': 'NÃO');
        // generic get
        $date = Manager::date('01/07/2011');
        $this->data->getDay = "getDay = " . $date->getDay();
        $this->data->getMonth = "getMonth = " . $date->getMonth();
        $this->data->getYear = "getYear = " . $date->getYear();
        $this->data->getDayNick = "getDayNick = " . $date->getDayNick();
        $this->data->getMonthNick = "getMonthNick = " . $date->getMonthNick();
        $this->data->getYear = "getYear = " . $date->getYear('y');
        $this->data->getDayName = "getDayName = " . $date->getDayName();
        $this->data->getMonthName = "getMonthName = " . $date->getMonthName();
        $this->data->getFullName = "getFullName = " . $date->getFullName();
        $this->data->getFullNameTrue = "getFullName(true) = " . $date->getFullName(true);
        // Invert date
        $this->data->invertDate = "Data invertida = " . $date->invert();
        
        // Using DatePeriod
        $period = $date->getPeriod('15/07/2011','P1D','23/07/2011');
        $this->data->periodoTitulo1 = "Períodos de 1 dia entre 15/07/2011 e 23/07/2011:";
        foreach($period as $dt){
            $this->data->periodo1 .= \Manager::date($dt) . ' - ';
        }
        $period = $date->getPeriod('01/01/2011','P14D','23/07/2011');
        $this->data->periodoTitulo2 = "Períodos de 14 dias entre 01/01/2011 e 23/07/2011:";
        foreach($period as $dt){
            $this->data->periodo2 .= \Manager::date($dt) . ' - ';
        }

        $this->render();
    }


}