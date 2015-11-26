<?php

Manager::import("exemplos\models\*");

class SOAPService extends MService {

    /**
     * Doc para o método SOAPController::method1
     * 
     * @param string $param
     * @return string 
     */
    public function method1($param){ 
        return 'Method1 em SOAPService param = ' . $param;
    }
    
    /**
     * Doc para método SOAPController::method2. Parâmetros: integer e string.
     * 
     * @param integer $integer
     * @param string $string
     * @return string
     */
    public function method2($integer, $string){
        return 'Method2 em SOAPService: integer[' . $integer . '] e string[' . $string .']';
    }

}