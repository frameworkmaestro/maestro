<?php

Manager::import('exemplos\models\*');

class UtilsController extends MController {

    public function mkrono() {
        // Exemplo de uso da classe MKrono
        // Convert the numerical value of a day in its name
        $this->data->NToDay = "Dia correspondente ao número 3 = " . MKrono::NToDay(3) . " [Domingo = 0]";
        // Convert the name of a day in its numerical value. 
        $this->data->dayToN = "Valor numérico de 'Segunda' = " . MKrono::dayToN('Segunda') . " [Domingo = 0]";
        // Convert the numerical value of a month in its name. 
        $this->data->NToMonth = "Mês correspondente ao número 7 = " . MKrono::NToMonth(7);
        // Convert the name of a month in its numerical value. 
        $this->data->monthToN = "Valor numérico de 'Maio' = " . Mkrono::monthToN('Maio');
        // Define if the day of date given is equal to day given. 
        $this->data->isDay1 = "07/09/2011 é uma sexta? " . (MKrono::isDay('07/09/2011', 6) ? 'Sim' : 'Não');
        $this->data->isDay2 = "07/09/2011 é uma quarta? " . (MKrono::isDay(new MDate('07/09/2011'), 'Quarta') ? 'Sim' : 'Não');
        
        $dataIni = '15/06/2010';
        $dataFim = '13/07/2012';
        // Define what's the day difference between two given date. 
        $this->data->daysDiff = "Diferença em dias entre " .$dataIni . " e " . $dataFim . " = " . MKrono::daysDiff($dataIni, $dataFim) . " dias";
        // Define what's the week difference between two given date. 
        $this->data->weeksDiff = "Diferença em semanas entre " .$dataIni . " e " . $dataFim . " = " . MKrono::weeksDiff($dataIni, $dataFim) . " semanas";
        // Define what's the month difference between two given date. 
        $this->data->monthsDiff = "Diferença em meses entre " .$dataIni . " e " . $dataFim . " = " . MKrono::monthsDiff($dataIni, $dataFim)  . " meses";
        // Define what's the year difference between two given date. 
        $this->data->yearsDiff = "Diferença em anos entre " .$dataIni . " e " . $dataFim . " = " . MKrono::yearsDiff($dataIni, $dataFim)  . " anos";
        // Give the difference between two times. 
        $this->data->timeDiff = "Diferença em minutos entre 09:25 e 16:45 = " . MKrono::timesDiff("09:25","16:25","m");
        // Add some minutes or hours to a given time.
        $this->data->timeAdd1 = "Adiciona 324 minutos a 09:25 = " . MKrono::timesAdd("09:25",324, "m");
        $this->data->timeAdd2 = "Adiciona 03:47 a 09:25 = " . MKrono::timesAdd("09:25","03:47", "t");
        // Define how days left to given date. date according to dateFormat parameter passed on inizialization
        $this->data->howTo = "Quantidade de dias de hoje até 20/12/2014 = " . MKrono::howTo('20/12/2014');
        // Get data from date
        $timestamp = '23/07/2012 15:45:00';
        $getDate = MKrono::getDate($timestamp);
        $this->data->getDateWDay = "Dia da semana de " . $timestamp . " usando getDate = " . MKrono::NToDay($getDate['wday']); 
        $this->data->getDateYDay = "Dia do ano de " . $timestamp . " usando getDate = " . $getDate['yday']; 
        // Function to turn seconds into a time
        $this->data->secsToTime = "4573 segundos transformado em horário = " . MKrono::secsToTime(4573);
        // Function that check the validity of a date and/or time
        $this->data->isValid1 = "A data 23/07/2011 é válida? " . (MKrono::isValid('23/07/2011') ? 'Sim' : 'Não');
        $this->data->isValid2 = "A data 29/02/2007 é válida? " . (MKrono::isValid('29/02/2007') ? 'Sim' : 'Não');
        //  Invert a date
        $this->data->invertDate = "Inventendo a data 23/07/2011 = " . MKrono::invertDate('23/07/2011');
        //  Compare two dates
        $dataA = '15/06/2010';
        $dataB = '13/07/2012';
        $dataC = '15/06/2010';
        $this->data->compare1 = $dataA . " = " . $dataB . " ? " . (MKrono::compareDate($dataA, '=', $dataB) ? 'Sim' : 'Não');
        $this->data->compare2 = $dataA . " > " . $dataB . " ? " . (MKrono::compareDate($dataA, '>', $dataB) ? 'Sim' : 'Não');
        $this->data->compare3 = $dataA . " <> " . $dataB . " ? " . (MKrono::compareDate($dataA, '<>', $dataB) ? 'Sim' : 'Não');
        $this->data->compare4 = $dataA . " = " . $dataC . " ? " . (MKrono::compareDate($dataA, '=', $dataC) ? 'Sim' : 'Não');
        // Get Day
        $this->data->getDay = "Dia em " . $dataA . " = " . MKrono::getDay($dataA);
        // Get Month
        $this->data->getMonth = "Mês em " . $dataA . " = " . MKrono::getMonth($dataA);
        // Get Year
        $this->data->getYear = "Ano em " . $dataA . " = " . MKrono::getYear($dataA);
        // Return true if a date is between two others
        $this->data->between1 = "15/03/2011 está entre " . $dataA . " e " . $dataB . " ? " . (MKrono::between($dataA, '15/03/2011', $dataB) ? 'Sim' : 'Não');
        $this->data->between2 = "15/03/2007 está entre " . $dataA . " e " . $dataB . " ? " . (MKrono::between($dataA, '15/03/2007', $dataB) ? 'Sim' : 'Não');
        // Get last day of the month
        $this->data->last1 = "Último dia do mês 7 = " . MKrono::getLastDayOfMonth(7);
        $this->data->last2 = "Último dia do mês 2 em 2004 = " . MKrono::getLastDayOfMonth(2, 2004);
        
        $this->render();
    }
    
    public function formCSVDump() {
        $this->render();
    }
    
    public function CSVDump() {
        $filter->login = $this->data->login;
        $result = Usuario::create()->listByFilter($filter)->asQuery()->getResult();
        $csvdump = new MCSVDump();
        $file = $csvdump->save($result,'exemplo.csv');
        $this->data->url = $file->getURL();
        $this->render("formcsvdump");
    }

}