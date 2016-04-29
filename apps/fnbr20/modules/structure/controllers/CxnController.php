<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class CxnController extends MController
{

    private $idLanguage;

    public function init()
    {
        parent::init();
        $this->idLanguage = Manager::getSession()->idLanguage;
    }

    public function main()
    {
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE) ? 'true' : 'false';
        $this->render();
    }

    public function cxnTree()
    {
        $structure = MApp::getService('fnbr20', '', 'structurecxn');
        if ($this->data->id == '') {
            $children = $structure->listCxns($this->data, $this->idLanguage);
            $data = (object) [
                        'id' => 'root',
                        'state' => 'open',
                        'text' => 'Constructions',
                        'children' => $children
            ];
            $json = json_encode([$data]);
        } elseif ($this->data->id{0} == 'c') {
            $json = $structure->listCEs(substr($this->data->id, 1), $this->idLanguage);
        } elseif ($this->data->id{0} == 'e') {
            $json = $structure->listConstraintsCE(substr($this->data->id, 1), $this->idLanguage);
        } elseif ($this->data->id{0} == 'x') {
            $json = $structure->listConstraintsCN(substr($this->data->id, 1), $this->idLanguage);
        }
        $this->renderJson($json);
    }

    public function formNewCxn()
    {
        $this->data->title = _M('New Construction');
        $this->render();
    }

    public function formUpdateCxn()
    {
        $model = new Construction($this->data->id);
        $this->data->object = $model->getData();
        $this->data->title = 'Construction: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }

    public function formDeleteCxn()
    {
        $ok = ">fnbr20/structure/cxn/deleteCxn/" . $this->data->id;
        $this->renderPrompt('confirmation', 'Atenção: A Cxn e todos os CxnElements serão removidos! Continua?', $ok);
    }
    
    public function formNewCxnElement()
    {
        $this->data->idConstruction = $this->data->id;
        $model = new Construction($this->data->idConstruction);
        $this->data->cxn = $model->getEntry() . '  [' . $model->getName() . ']';
        $this->data->save = "@fnbr20/structure/cxn/newCxnElement|formNewCxnElement";
        $this->data->close = "!$('#formNewCxnElement_dialog').dialog('close');";
        $this->data->title = _M('New CxnElement');
        $this->render();
    }

    public function formUpdateCxnElement()
    {
        $model = new ConstructionElement($this->data->id);
        $this->data->object = $model->getData();
        $this->data->save = "@fnbr20/structure/cxn/updateCxnElement|formUpdateCxnElement";
        $this->data->close = "!$('#formUpdateCxnElement_dialog').dialog('close');";
        $this->data->title = 'CxnElement: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }

    public function newCxn()
    {
        try {
            $model = new Construction();
            $this->data->cxn->entry = 'cxn_' . strtolower(str_replace('cxn_','',$this->data->cxn->entry));
            $model->setData($this->data->cxn);
            $model->createNew($this->data->cxn);
            $this->renderResponse('ok', 'Construction created.');
        } catch (\Exception $e) {
            $this->renderResponse('error', $e->getMessage());
        }
    }

    public function updateCxn()
    {
        try {
            $model = new Construction($this->data->cxn->idConstruction);
            $model->updateEntry($this->data->cxn->entry);
            $this->renderResponse('information', 'OK');
//            $this->renderPrompt('information', 'Cxn updated.', "structure.editEntry('{$this->data->cxn->entry}');");            
        } catch (\Exception $e) {
            $this->renderResponse('error', $e->getMessage());
        }
    }

    public function deleteCxn() {
        try {
            $structure = MApp::getService('fnbr20', '', 'structurecxn');
            $structure->deleteCxn($this->data->id);
            $this->renderPrompt('information', 'Cxn deleted.');
        } catch (\Exception $e) {
            $this->renderPrompt('error', "Não é possível remover esta construção.");
        }
        
    }
    
    public function newCxnElement()
    {
        try {
            $model = new ConstructionElement();
            $this->data->cxnelement->entry = 'ce_' . strtolower(str_replace('ce_', '', $this->data->cxnelement->entry));
            $model->setData($this->data->cxnelement);
            $model->save($this->data->cxnelement);
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->cxnelement->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function updateCxnElement()
    {
        try {
            $model = new ConstructionElement($this->data->cxnelement->idConstructionElement);
            $model->updateEntry($this->data->cxnelement->entry);
            $model->setData($this->data->cxnelement);
            $model->save($this->data->cxnelement);
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->cxnelement->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formImportTxt()
    {
        $model = new Construction($this->data->id);
        $this->data->cxn = $model->getEntry() . '  [' . $model->getName() . ']';
        $this->data->languages = Base::languages();
        $this->data->message = _M("Importação de arquivo de texto simples (uma sentença por linha).");
        $this->data->save = "@fnbr20/structure/cxn/importTxt|formImportTxt";
        $this->data->close = "!$('#formImportTxt_dialog').dialog('close')";
        $this->render();
    }

    public function importTxt()
    {
        try {
            $files = \Maestro\Utils\Mutil::parseFiles('uploadFile');
            $model = new Corpus();
            $result = $model->uploadCxnSimpleText($this->data, $files[0]);
            $this->renderPrompt('information', 'OK');
        } catch (EMException $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formAddConstraintCE()
    {
        $this->data->idConstructionElement = $this->data->id;
        $model = new ConstructionElement($this->data->idConstructionElement);
        $cxn = $model->getConstruction();
        $this->data->ce = 'CE: ' . $cxn->getName() . '.' . $model->getName();
        $this->data->siblingsCE = $model->listSiblingsCE()->chunkResult('idConstructionElement', 'name');
        $this->data->save = "@fnbr20/structure/cxn/addConstraintCE|formAddConstraintCE";
        $this->data->close = "!$('#formAddConstraintCE_dialog').dialog('close');";
        $this->data->title = _M('Add Constraint');
        $this->render();
    }

    public function formAddConstraintCN()
    {
        $this->data->idConstraint = $this->data->id;
        $model = new Constraint($this->data->idConstraint);
        $constraintData = $model->getConstraintData();
        mdump($constraintData);
        $this->data->showCxnCE = $this->data->showCE = false;
        if ($constraintData->constrainedByType == 'CX') {
            $this->data->showCxnCE = true;
            $ce = new ViewConstructionElement();
            $this->data->cxnCE =  $ce->listCEByConstructionEntity($constraintData->idConstrainedBy)->chunkResult('idConstructionElement', 'name');;
        }
        if ($constraintData->constrainedByType == 'CE') {
            $this->data->showCE = true;
        }
        $this->data->save = "@fnbr20/structure/cxn/addConstraintCN|formAddConstraintCN";
        $this->data->close = "!$('#formAddConstraintCN_dialog').dialog('close');";
        $this->data->title = _M('Add Constraint');
        $this->render();
    }

    public function formDeleteConstraint()
    {
        $structure = MApp::getService('fnbr20', '', 'structureconstraints');
        $hasChild = $structure->constraintHasChild($this->data->id);
        if (!$hasChild) {
            $ok = "^fnbr20/structure/cxn/deleteConstraint/" . $this->data->id;
            $this->renderPrompt('confirmation', 'Warning: Constraint will be deleted! Continue?', $ok);
        } else {
            $this->renderPrompt('error', "This constraint has children; it can't be deleted!");
        }
    }

    public function addConstraintCE() {
        mdump($this->data);
        try {
            $structure = MApp::getService('fnbr20', '', 'structurecxn');
            $structure->addConstraintsCE($this->data);
            $this->renderPrompt('information', 'Constraint added.');
        } catch (\Exception $e) {
            $this->renderPrompt('error', "Add Constraint failed.");
        }
    }

    public function addConstraintCN() {
        mdump($this->data);
        try {
            $structure = MApp::getService('fnbr20', '', 'structurecxn');
            $structure->addConstraintsCN($this->data);
            $this->renderPrompt('information', 'Constraint added.');
        } catch (\Exception $e) {
            $this->renderPrompt('error', "Add Constraint failed.");
        }
    }

    public function deleteConstraint() {
        try {
            $model = Constraint::create($this->data->id);
            $model->delete();
            $this->renderPrompt('information', 'Constraint deleted.', "!structure.reloadCxnParent();");
        } catch (\Exception $e) {
            $this->renderPrompt('error', "Delete Constraint failed.","!structure.reloadCxn();");
        }
    }

    public function graphCxn() {
        $cxn = Construction::create($this->data->id);
        $this->data->cxnName = $cxn->getName();
        $grapher = MApp::getService('fnbr20', '', 'grapher');
        $this->data->relationData = $grapher->getRelationData();
        $this->render();
    }

}
