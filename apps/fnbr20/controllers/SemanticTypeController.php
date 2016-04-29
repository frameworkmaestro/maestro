<?php
/**
 * $_comment
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage $_package
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

Manager::import("fnbr20\models\*");

class SemanticTypeController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function lookupData($rowsOnly = false, $idDomain = 0){
        $model = new SemanticType();
        $filter = (object) ['idDomain' => $idDomain];
        $criteria = $model->listForLookup($filter);
        $this->renderJSON($model->gridDataAsJSON($criteria, $rowsOnly));
    }
}