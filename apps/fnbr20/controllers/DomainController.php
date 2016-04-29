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

class DomainController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function lookupData($rowsOnly){
        $model = new Domain();
        $criteria = $model->listAll();
        $this->renderJSON($model->gridDataAsJSON($criteria, $rowsOnly));
    }

    public function saveFrameDomain() {
        try {
            $structure = MApp::getService('fnbr20', '', 'structuredomain');
            $structure->saveFrameDomain($this->data->idFrame, $this->data->toSave);
            $this->renderPrompt('information', "Ok","$('#{$this->data->idGrid}').datagrid('reload');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }


}