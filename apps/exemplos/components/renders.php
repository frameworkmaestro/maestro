<?php
/**
 * Classe com métodos usados para customizar a renderização das colunas de Grids.
 * Ex:  <mdatagridcolumn field="dataNascimento" title="DataNascimento" render="{{ array('renders','colorData')}}"  width="20%"/>
 */
class renders {

    static function colorData($value) {
        $control = new MLabel($value);
        $date = Manager::date($value);
        $color = $date->compare('>', '01/10/2011') ? 'blue' : 'red';
        $control->addStyle('color', $color);
        return $control;
    }

}

?>