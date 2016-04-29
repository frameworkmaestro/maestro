<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class FrameController extends MController
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
        $domain = new Domain();
        $this->data->domain = $domain->gridDataAsJson($domain->listForSelection(),true);
        mdump($this->data->domain);
        $this->render();
    }

    public function frameTree()
    {
        $structure = MApp::getService('fnbr20', '', 'structureframe');
        if ($this->data->id == '') {
            $children = $structure->listFrames($this->data, $this->idLanguage);
            $data = (object) [
                        'id' => 'root',
                        'state' => 'open',
                        'text' => 'Frames',
                        'children' => $children
            ];
            $json = json_encode([$data]);
        } elseif ($this->data->id{0} == 'f') {
            $json = $structure->listFEsLUs(substr($this->data->id, 1), $this->idLanguage);
        } elseif ($this->data->id{0} == 'l') {
            $json = $structure->listSubCorpus(substr($this->data->id, 1));
        } elseif ($this->data->id{0} == 'e') {
            $json = $structure->listConstraintsFE(substr($this->data->id, 1));
        }
        $this->renderJson($json);
    }

    public function formNewFrame()
    {
        $this->data->title = _M('New Frame');
        $this->render();
    }

    public function formUpdateFrame()
    {
        $model = new Frame($this->data->id);
        $this->data->object = $model->getData();
        $this->data->title = 'Frame: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }

    public function formDeleteFrame()
    {
        $ok = ">fnbr20/structure/frame/deleteFrame/" . $this->data->id;
        $this->renderPrompt('confirmation', 'Atenção: O Frame e todos os FrameElements serão removidos! Continua?', $ok);
    }

    public function formFrameSemanticType()
    {
        $model = new Frame($this->data->id);
        $this->data->object = $model->getData();
        $this->data->idEntity = $model->getIdEntity();
        $this->data->form = "formFrameSemanticType";
        $this->data->close = "!$('#formFrameSemanticType_dialog').dialog('close');";
        $this->data->title = 'Frame: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }

    public function formFrameDomain()
    {
        $model = new Frame($this->data->id);
        $this->data->object = $model->getData();
        $this->data->idFrame = $model->getIdFrame();
        $this->data->form = "formFrameDomain";
        $this->data->close = "!$('#formFrameDomain_dialog').dialog('close');";
        $this->data->title = 'Frame: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }

    public function formNewFrameRelations()
    {
        $this->data->save = "@fnbr20/structure/frame/newFrameRelatios|formNewFrameRelations";
        $this->data->close = "!$('#formNewFrameRelations_dialog').dialog('close');";
        $this->data->title = _M('New Frame Relations');
        $this->render();
    }

    public function formNewFrameElement()
    {
        $this->data->idFrame = $this->data->id;
        $model = new Frame($this->data->idFrame);
        $this->data->frame = $model->getEntry() . '  [' . $model->getName() . ']';
        $this->data->save = "@fnbr20/structure/frame/newFrameElement|formNewFrameElement";
        $this->data->close = "!$('#formNewFrameElement_dialog').dialog('close');";
        $this->data->title = _M('New FrameElement');
        $this->render();
    }

    public function formUpdateFrameElement()
    {
        $model = new FrameElement($this->data->id);
        $this->data->object = $model->getData();
        $this->data->save = "@fnbr20/structure/frame/updateFrameElement|formUpdateFrameElement";
        $this->data->close = "!$('#formUpdateFrameElement_dialog').dialog('close');";
        $this->data->title = 'FrameElement: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }

    public function formDeleteFrameElement()
    {
        $ok = "^fnbr20/structure/frame/deleteFrameElement/" . $this->data->id;
        $this->renderPrompt('confirmation', 'Warning: FrameElement will be removed! Continue?', $ok);
    }

    /*
    public function formFESemanticType()
    {
        $model = new FrameElement($this->data->id);
        $this->data->object = $model->getData();
        $this->data->idEntity = $model->getIdEntity();
        $this->data->form = "formFESemanticType";
        //$this->data->save = "@fnbr20/structure/frame/feSemanticType|formFESemanticType";
        $this->data->close = "!$('#formFESemanticType_dialog').dialog('close');";
        $this->data->title = 'FrameElement: ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $this->render();
    }
    */
    public function formAddConstraintFE()
    {
        $this->data->idFrameElement = $this->data->id;
        $model = new FrameElement($this->data->idFrameElement);
        $frame = $model->getFrame();
        $this->data->fe = 'FE: ' . $frame->getName() . '.' . $model->getName();
        $this->data->save = "@fnbr20/structure/frame/addConstraintFE|formAddConstraintFE";
        $this->data->close = "!$('#formAddConstraintFE_dialog').dialog('close');";
        $this->data->title = _M('Add Constraint');
        $this->render();
    }

    public function formDeleteConstraintFE()
    {
        $structure = MApp::getService('fnbr20', '', 'structureconstraints');
        $hasChild = $structure->constraintHasChild($this->data->id);
        if (!$hasChild) {
            $ok = "^fnbr20/structure/frame/deleteConstraintFE/" . $this->data->id;
            $this->renderPrompt('confirmation', 'Warning: Constraint will be deleted! Continue?', $ok);
        } else {
            $this->renderPrompt('error', "This constraint has children; it can't be deleted!");
        }
    }

    public function addConstraintFE() {
        mdump($this->data);
        try {
            $structure = MApp::getService('fnbr20', '', 'structureframe');
            $structure->addConstraintsFE($this->data);
            $this->renderPrompt('information', 'Constraint added.');
        } catch (\Exception $e) {
            $this->renderPrompt('error', "Add Constraint failed.");
        }
    }

    public function deleteConstraintFE() {
        try {
            $model = Constraint::create($this->data->id);
            $model->delete();
            $this->renderPrompt('information', 'Constraint deleted.', "!structure.reloadFrameParent();");
        } catch (\Exception $e) {
            $this->renderPrompt('error', "Delete Constraint failed.","!structure.reloadFrame();");
        }
    }

    public function formNewLU()
    {
        $this->data->idFrame = $this->data->id;
        $model = new Frame($this->data->idFrame);
        $this->data->frame = 'Frame:  ' . $model->getEntry() . '  [' . $model->getName() . ']';
        $model = new Lemma();
        $this->data->query = Manager::getAppURL('fnbr20', 'structure/frame/gridSearchLemmaData');
        $this->data->save = "@fnbr20/structure/frame/newLU|formNewLU";
        $this->data->close = "!$('#formNewLU_dialog').dialog('close');structure.reloadFrame()";
        $this->data->title = _M('New LU');
        $this->render();
    }

    public function formUpdateLU()
    {
        $model = new LU($this->data->id);
        $this->data->object = $model->getData();
        $this->data->idFrame = $this->data->object->idFrame; // for lookupFE
        $this->data->save = "@fnbr20/structure/frame/updateLU|formUpdateLU";
        $this->data->close = "!$('#formUpdateLU_dialog').dialog('close');";
        $this->data->title = 'LU:  ' . $model->getFullName();
        $this->render();
    }

    public function formDeleteLU()
    {
        $ok = "^fnbr20/structure/frame/deleteLU/" . $this->data->id;
        $this->renderPrompt('confirmation', 'Warning: LU will be removed! Continue?', $ok);
    }

    public function formDeleteSubCorpus()
    {
        $ok = "^fnbr20/structure/frame/deleteSubCorpus/" . $this->data->id;
        $this->renderPrompt('confirmation', 'Warning: SubCorpus will be removed! Continue?', $ok);
    }

    public function formLUSemanticType()
    {
        $model = new LU($this->data->id);
        $this->data->object = $model->getData();
        $this->data->idEntity = $model->getIdEntity();
        $this->data->form = "formLUSemanticType";
        $this->data->close = "!$('#formLUSemanticType_dialog').dialog('close');";
        $this->data->title = 'LU: ' . $model->getName();
        $this->render();
    }

    public function gridSearchLemmaData()
    {
        $model = new Lemma();
        $lemma = str_replace('+', ' ', $this->data->lemma);
        $criteria = $model->listForSearch($lemma);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formNewLemma()
    {
        if ($this->data->lemma == '') {
            $this->renderPrompt('error', 'No lemma informed.');
        } elseif ($this->data->idLanguage == '') {
            $this->renderPrompt('error', 'No language informed.');
        } else {
            $this->data->save = "@fnbr20/structure/frame/newLemma|formNewLemma";
            $this->data->close = "!$('#formNewLemma_dialog').dialog('close')";
            $dataService = MApp::getService('fnbr20', '', 'data');
            $this->data->pos = $dataService->getPOS();
            $this->data->language = $dataService->getLanguage()[$this->data->idLanguage];
            $this->render();
        }
    }

    public function formNewLexeme()
    {
        $this->data->lexeme = $this->data->id;
        if ($this->data->lexeme == '') {
            $this->renderPrompt('error', 'No lexeme informed.');
        } elseif ($this->data->lemma->idLanguage == '') {
            $this->renderPrompt('error', 'No language informed.');
        } else {
            $this->data->save = "@fnbr20/structure/frame/newLexeme|formNewLexeme";
            $this->data->close = "!$('#formNewLexeme_dialog').dialog('close')";
            $dataService = MApp::getService('fnbr20', '', 'data');
            $this->data->language = $dataService->getLanguage()[$this->data->lemma->idLanguage];
            $this->data->pos = $dataService->getPOS();
            $this->render();
        }
    }

    public function createTemplate()
    {
        try {
            $this->data->idFrame = $this->data->id;
            $model = new Template();
            $model->createFromFrame($this->data->idFrame);
            $this->renderPrompt('information', 'Template [' . $model->getName() . '] was created.');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function newFrame()
    {
        try {
            $model = new Frame();
            $this->data->frame->entry = 'frm_' . strtolower(str_replace('frm_','',$this->data->frame->entry));
            $model->setData($this->data->frame);
            $inheritsFromBase = ($this->data->inheritsFromBase == 'on');
            $relations = $model->createNew($this->data->frame, $inheritsFromBase);
            /*
            if ((count($relations['direct'])) || (count($relations['inverse']))) {
                $this->renderPrompt('information', 'Frame created.', "structure.editRelations('{$this->data->frame->entry}');");
            } else {
                //$this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->frame->entry}');");
                $this->renderPrompt('information', 'OK',"structure.editEntry('{$this->data->frame->entry}','formNewFrame')");
                $this->renderPrompt('information', 'OK');
            }
             * */
            $this->renderResponse('ok', 'Frame created.');
        } catch (\Exception $e) {
            $this->renderResponse('error', $e->getMessage());
        }
    }

    public function newFrameRelations()
    {
        try {
            $model = new Frame();
            $this->data->frame->entry = 'frm_' . strtolower(str_replace('frm_','',$this->data->frame->entry));
            $model->setData($this->data->frame);
            $inheritsFromBase = ($this->data->inheritsFromBase == 'on');
            $relations = $model->createNew($this->data->frame, $inheritsFromBase);
            if ((count($relations['direct'])) || (count($relations['inverse']))) {
                $this->renderPrompt('information', 'Frame created.', "structure.editRelations('{$this->data->frame->entry}');");
            } else {
                //$this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->frame->entry}');");
                $this->renderPrompt('information', 'OK',"structure.editEntry('{$this->data->frame->entry}','formNewFrame')");
                $this->renderPrompt('information', 'OK');
            }
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }


    public function updateFrame()
    {
        try {
            $model = new Frame($this->data->frame->idFrame);
            $model->updateEntry($this->data->frame->entry);
            //$this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->frame->entry}');");
            $this->renderResponse('ok', 'Frame updated.');
            //$this->renderPrompt('information', 'OK', "$('#formUpdateFrame_dialog').dialog('refresh', 'fnbr20/entry/formUpdate/{$this->data->frame->entry}');$('#formUpdateFrame_dialog').dialog('doLayout')");
        } catch (\Exception $e) {
            $this->renderResponse('error', $e->getMessage());
//            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function deleteFrame() {
        try {
            $structure = MApp::getService('fnbr20', '', 'structureframe');
            $structure->deleteFrame($this->data->id);
            $this->renderResponse('information', 'OK', "!structure.reloadFrame();");
        } catch (\Exception $e) {
            $this->renderResponse('error', $e->getMessage());
        }

    }

    public function newFrameElement()
    {
        try {
            $model = new FrameElement();
            $this->data->frameelement->entry = 'fe_' . $this->data->frameelement->entry;
            $model->setData($this->data->frameelement);
            $model->save($this->data->frameelement);
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->frameelement->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function updateFrameElement()
    {
        try {
            $model = new FrameElement($this->data->frameelement->idFrameElement);
            $model->updateEntry($this->data->frameelement->entry);
            $model->setData($this->data->frameelement);
            $model->save($this->data->frameelement);
            $this->renderPrompt('information', 'OK', "structure.editEntry('{$this->data->frameelement->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function deleteFrameElement() {
        try {
            $model = new FrameElement($this->data->id);
            $model->safeDelete();
            $this->renderPrompt('information', 'FrameElement removed.', "!structure.reloadFrame();");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function newLU()
    {
        try {
            $lu = new LU();
            $this->data->lu->idLemma = $this->data->gridSearchLemma->data->checked[0];
            $this->data->lu->active = '1';
            $this->data->lu->name = $this->data->lemma;
            $lu->save($this->data->lu);
            $frame = Frame::create($this->data->lu->idFrame);
            Base::createEntityRelation($lu->getIdEntity(), 'rel_evokes', $frame->getIdEntity());
            $this->renderPrompt('information', 'OK', "!$('#formNewLU_dialog').dialog('close'); structure.reloadFrame()");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function updateLU()
    {
        try {
            $model = new LU($this->data->lu->idLU);
            $model->setData($this->data->lu);
            $model->save();
            $this->renderPrompt('information', 'OK');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function deleteLU() {
        try {
            $model = new LU($this->data->id);
            $model->delete();
            $this->renderPrompt('information', 'LU removed.', "!structure.reloadFrame();");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function deleteSubCorpus() {
        try {
            $model = new SubCorpus($this->data->id);
            $model->delete();
            $this->renderPrompt('information', 'SubCorpus removed.', "!structure.reloadFrame();");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function newLemma()
    {
        try {
            $model = new Lemma();
            $model->save($this->data);
            $this->renderPrompt('information', 'OK', "!$('#formNewLemma_dialog').dialog('close'); jQuery('#gridSearchLemma').datagrid('filter','lemma');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function newLexeme()
    {
        try {
            if ($this->data->lexeme->idPOS == '') {
                throw new \Exception('No POS informed.');
            } else {
                $model = new Lexeme();
                $model->save($this->data->lexeme);
                $this->renderPrompt('information', 'OK', "!$('#formNewLexeme_dialog').dialog('close'); $('#gridLexema{$this->data->lexeme->name}').datagrid('reload');");
            }
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formImportWS()
    {
        $this->data->languages = Base::languages();
        $this->data->idLU = $this->data->id;
        $this->data->tags = array('N' => 'Não', 'S' => 'Sim');
        $this->data->message = _M("Importação do arquivo do WordSketch<br>com nome do documento informado em cada linha.<br>Os documentos já devem estar cadastrados no sistema.");
        $this->data->save = "@fnbr20/structure/frame/importWS|formImportWS";
        $this->data->close = "!$('#formImportWS_dialog').dialog('close')";
        $this->render();
    }

    public function importWS()
    {
        try {
            $files = \Maestro\Utils\Mutil::parseFiles('uploadFile');
            $model = new Corpus($this->data->idCorpus);
            if ($this->data->tags == 'N') {
                $result = $model->uploadSentences($this->data, $files[0]);
            } else {
                $result = $model->uploadSentencesPenn($this->data, $files[0]);
            }
            $this->renderPrompt('information', 'OK');
        } catch (EMException $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

}
