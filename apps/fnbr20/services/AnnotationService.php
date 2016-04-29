<?php

Manager::import("fnbr20\models\*");

class AnnotationService extends MService {

    public function getColor() {
        $color = new Color();
        $colors = $color->listAll()->asQuery()->getResult();
        $result = new \StdClass();
        foreach ($colors as $c) {
            $node = new \StdClass();
            $node->rgbFg = $c['rgbFg'];
            $node->rgbBg = $c['rgbBg'];
            $idColor = $c['idColor'];
            $result->$idColor = $node;
        }
        return json_encode($result);
    }

    public function getLayerType() {
        $lt = new LayerType();
        $lts = $lt->listAll()->asQuery()->getResult();
        $result = new \StdClass();
        foreach ($lts as $row) {
            $node = new \StdClass();
            $node->entry = $row['entry'];
            $node->name = $row['name'];
            $idLT = $row['idLayerType'];
            $result->$idLT = $node;
        }
        return json_encode($result);
    }

    public function getInstantiationType() {
        $type = new Type();
        $instances = $type->getInstantiationType()->asQuery()->getResult();
        $array = array();
        $obj = new \StdClass();
        foreach ($instances as $instance) {
            if ($instance['instantiationType'] != 'APos') {
                $obj->$instance['idInstantiationType'] = $instance['instantiationType'];
                $node = new \StdClass();
                if ($instance['instantiationType'] == 'Normal') {
                    $node->idInstantiationType = 0;
                    $node->instantiationType = '-';
                } else {
                    $node->idInstantiationType = $instance['idInstantiationType'];
                    $node->instantiationType = $instance['instantiationType'];
                }
                $array[] = $node;
            }
        }
        $result = [
            'array' => json_encode($array),
            'obj' => json_encode($obj)
        ];
        return $result;
    }

    private function constraintLU() {
        $idLU = NULL;
        $userLevel = Base::getCurrentUserLevel();
        if (($userLevel == 'BEGINNER') || ($userLevel == 'JUNIOR')) {
            $user = Base::getCurrentUser();
            $lus = $user->getConfigData('fnbr20ConstraintsLU');
            if (is_array($lus) && count($lus)) {
                $idLU = $lus;
            } else {
                $idLU = -1;
            }
        }
        return $idLU;
    }

    public function listFrames($lu = '', $idLanguage = '') {
        $idLU = $this->constraintLU();
        if ($idLU == -1) {
            return json_encode([[]]);
        }
        $frame = new ViewFrame();
        $filter = (object) ['lu' => $lu, 'idLanguage' => $idLanguage, 'idLU' => $idLU];
        $frames = $frame->listByFilter($filter)->asQuery()->chunkResult('idFrame', 'name');
        $result = array();
        foreach ($frames as $idFrame => $name) {
            $node = array();
            $node['id'] = 'f' . $idFrame;
            $node['text'] = $name;
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function listLUs($idFrame, $idLanguage) {
        $idLU = $this->constraintLU();
        if ($idLU == -1) {
            return json_encode([[]]);
        }
        $lu = new ViewLU();
        $lus = $lu->listByFrame($idFrame, $idLanguage, $idLU)->asQuery()->chunkResult('idLU', 'name');
        $result = array();
        foreach ($lus as $idLU => $name) {
            $node = array();
            $node['id'] = 'l' . $idLU;
            $node['text'] = $name;
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function listSubCorpus($idLU) {
        $sc = new ViewSubCorpusLU();
        $scs = $sc->listByLU($idLU)->asQuery()->getResult();
        foreach ($scs as $sc) {
            $node = array();
            $node['id'] = 's' . $sc['idSubCorpus'];
            $node['text'] = $sc['name'] . ' [' . $sc['quant'] . ']';
            $node['state'] = 'open';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function getSubCorpusTitle($idSubCorpus, $idLanguage, $isCxn) {
        $sc = $isCxn ? new ViewSubCorpusCxn() : new ViewSubCorpusLU();
        $title = $sc->getTitle($idSubCorpus, $idLanguage);
        return $title;
    }

    public function getSubCorpusStatus($idSubCorpus, $isCxn) {
        $sc = $isCxn ? new ViewSubCorpusCxn() : new ViewSubCorpusLU();
        $status = new \StdClass;
        $total = 0;
        $totalUnann = 0;
        $stats = $sc->getStats($idSubCorpus);
        foreach ($stats as $st) {
            $entry = $st['entry'];
            $status->stat->$entry = (object) ['name' => $st['name'], 'quant' => $st['quant']];
            $total += $st['quant'];
            if ($entry == 'ast_unann') {
                ++$totalUnann;
            }
        }
        $status->stat->total = (object) ['name' => _M('Total'), 'quant' => $total];
        if ($totalUnann == 0) {
            $status->status->code = 1;
            $status->status->msg = _M('Complete');
        } else {
            $status->status->code = 0;
            $status->status->msg = _M('Incomplete');
        }
        return $status;
    }

    public function getDocumentTitle($idDocument, $idLanguage) {
        $doc = new Document();
        $filter = (object) ['idDocument' => $idDocument];
        $result = $doc->listByFilter($filter)->asQuery()->getResult();
        return 'Document:' . $result['name'];
    }

    public function decorateSentence($sentence, $labels) {
        $decorated = "";
        $ni = "";
        $i = 0;
        $sentence = utf8_decode($sentence);
        foreach ($labels as $label) {
            $style = 'background-color:#' . $label['rgbBg'] . ';color:#' . $label['rgbFg'] . ';';
            if ($label['startChar'] >= 0) {
                $decorated .= substr($sentence, $i, $label['startChar'] - $i);
                $decorated .= "<span style='{$style}'>" . substr($sentence, $label['startChar'], $label['endChar'] - $label['startChar'] + 1) . "</span>";
                $i = $label['endChar'] + 1;
            } else { // null instantiation
                $ni .= "<span style='{$style}'>" . $label['instantiationType'] . "</span> " . $decorated;
            }
        }
        $decorated = utf8_encode($ni . $decorated . substr($sentence, $i));
        return $decorated;
    }

    public function listAnnotationSet($idSubCorpus, $sortable = NULL) {
        $as = new ViewAnnotationSet();
        $sentences = $as->listBySubCorpus($idSubCorpus, $sortable)->asQuery()->getResult();
        $annotation = $as->listFECEBySubCorpus($idSubCorpus);
        $result = array();
        foreach ($sentences as $sentence) {
            $node = array();
            $node['idAnnotationSet'] = $sentence['idAnnotationSet'];
            $node['idSentence'] = $sentence['idSentence'];
            if ($annotation[$sentence['idSentence']]) {
                $node['text'] = $this->decorateSentence($sentence['text'], $annotation[$sentence['idSentence']]);
            } else {
                $node['text'] = $sentence['text'];
            }
            $node['status'] = $sentence['annotationStatus'];
            $node['rgbBg'] = $sentence['rgbBg'];
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function getLayers($params, $idLanguage) {
        $idSentence = $params->idSentence;
        $idAnnotationSet = $params->idAnnotationSet;

        $layers = array(
            "words" => NULL,
            "frozenColumns" => NULL,
            "columns" => NULL,
            "labels" => NULL,
            "layers" => NULL,
            "labelTypes" => NULL,
            "nis" => NULL,
        );

        $as = $idAnnotationSet ? new AnnotationSet($idAnnotationSet) : new AnnotationSet();

        // get words/chars
        $wordsChars = $as->getWordsChars($idSentence);
        $words = $wordsChars->words;
        $chars = $wordsChars->chars;

        $result = [];
        foreach ($words as $i => $word) {
            $fieldData = $i;
            $result[$fieldData]->word = $word['word'];
            $result[$fieldData]->startChar = $word['startChar'];
            $result[$fieldData]->endChar = $word['endChar'];
        }
        $layers['words'] = json_encode($result);

        $result = [];
        foreach ($chars as $i => $char) {
            $fieldData = 'wf' . $i;
            $result[$fieldData]->order = $char['offset'];
            $result[$fieldData]->char = $char['char'];
            $result[$fieldData]->word = $char['order'];
        }
        $layers['chars'] = json_encode($result);

        // annotationSet Status
        $asStatus = $as->getFullAnnotationStatus();

        // get hiddenColumns/frozenColumns/Columns using $words
        $frozenColumns[] = array(
            "field" => "layer",
            "width" => '60',
            "title" => "[{$idSentence}] " . "<span class='fa fa-square' style='width:16px;color:#" . $asStatus->rgbBg . "'></span><span>" . $asStatus->annotationStatus . "</span>",
            "formatter" => "annotation.cellLayerFormatter",
            "styler" => "annotation.cellStyler"
        );
        $columns[] = array("field" => "idAnnotationSet", "hidden" => 'true', "formatter" => "", "styler" => "");
        $columns[] = array("field" => "idLayerType", "hidden" => 'true', "formatter" => "", "styler" => "");
        $columns[] = array("field" => "idLayer", "hidden" => 'true', "formatter" => "", "styler" => "");
        $columns[] = array(
            "hidden" => 'false',
            "field" => "ni",
            "width" => "90",
            "resizable" => "true",
            "title" => "NI",
            "formatter" => "annotation.cellNIFormatter",
            "styler" => ""
        );

        foreach ($chars as $i => $char) {
            $columns[] = array(
                "hidden" => 'false',
                "field" => 'wf' . $i,
                "width" => 13,
                "resizable" => "false",
                "title" => $char['char'],
                "formatter" => "annotation.cellFormatter",
                "styler" => "annotation.cellStyler"
            );
        }
        $layers['columns'] = $columns;
        $layers['frozenColumns'] = $frozenColumns;

        // get Layers
        $result = array();
        $asLayers = $as->getLayers($idSentence);
        foreach ($asLayers as $row) {
            $result[$row['idLayer']] = [
                'idAnnotationSet' => $row['idAnnotationSet'],
                'nameLayer' => $row['name'],
                'currentLabel' => '0',
                'currentLabelPos' => 0
            ];
        }
        // CE-FE is a "artificial" layer; it needs to be inserts manually
        $queryLabelType = $as->getLabelTypesCEFE($idSentence);
        $rowsCEFE = $queryLabelType->getResult();
        foreach ($rowsCEFE as $row) {
            $result[$row['idLayer']] = [
                'idAnnotationSet' => $row['idAnnotationSet'],
                'nameLayer' => $row['idLayer'],
                'currentLabel' => '0',
                'currentLabelPos' => 0
            ];
        }
        $layers['layers'] = json_encode($result);

        // get AnnotationSets
        $result = array();
        $annotationSets = $as->getAnnotationSets($idSentence);
        foreach ($annotationSets as $row) {
            $result[$row['idAnnotationSet']] = [
                'idAnnotationSet' => $row['idAnnotationSet'],
                'name' => $row['name'],
                'show' => true
            ];
        }
        $layers['annotationSets'] = json_encode($result);

        // get Labels
        $result = array();
        $asLabels = $as->getLabels($idSentence);
        foreach ($asLabels as $row) {
            $result[$row['idLayer']][$row['idLabel']] = ['idLabelType' => $row['idLabelType']];
        }
        $layers['labels'] = json_encode($result);

        // get LabelTypes
        $result = array();
        // GL-GF
        $queryLabelType = $as->getLabelTypesGLGF($idSentence)->asQuery();
        $rows = $queryLabelType->getResult();
        foreach ($rows as $row) {
            $result[$row['idLayer']][$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => $row['coreType']
            ];
        }
        // GL
        $queryLabelType = $as->getLabelTypesGL($idSentence)->asQuery();
        $rows = $queryLabelType->getResult();
        foreach ($rows as $row) {
            $result[$row['idLayer']][$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => $row['coreType']
            ];
        }
        // FE
        $queryLabelType = $as->getLabelTypesFE($idSentence);
        $rows = $queryLabelType->getResult();
        //mdump($rows);
        foreach ($rows as $row) {
            $result[$row['idLayer']][$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => $row['coreType']
            ];
        }
        // CE
        $queryLabelType = $as->getLabelTypesCE($idSentence);
        $rows = $queryLabelType->getResult();
        foreach ($rows as $row) {
            $result[$row['idLayer']][$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => $row['coreType']
            ];
        }

        // CE-FE - $rowsCEFE is obtained via query for layer above
        foreach ($rowsCEFE as $row) {
            $result[$row['idLayer']][$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => $row['coreType']
            ];
        }

        $layers['labelTypes'] = json_encode($result);

        // get NIs
        //$niFields = array();
        $result = array();
        $queryNI = $as->getNI($idSentence, $idLanguage);
        $rows = $queryNI->getResult();
        foreach ($rows as $row) {
            $result[$row['idLayer']][$row['idLabelType']] = [
                'fe' => $row['feName'],
                'idInstantiationType' => $row['idInstantiationType'],
                'label' => $row['instantiationType'],
                'idColor' => $row['idColor']
            ];
        }
        $layers['nis'] = (count($result) > 0) ? json_encode($result) : "{}";
        return $layers;
    }

    public function getLayersData($params, $idLanguage) {
        $idSentence = $params->idSentence;
        $idAnnotationSet = $params->idAnnotationSet;

        $as = new AnnotationSet($idAnnotationSet);
        $idLU = $as->getSubCorpus()->getIdLU();
        $idCxn = $as->getSubCorpus()->getIdCxn();
        $isCxn = ($idLU == NULL) && ($idCxn != NULL);
        
        $result = array();
        $queryLayersData = $as->getLayersData($idSentence);
        $unorderedRows = $queryLayersData->getResult();

        // get the annotationsets
        $aSet = array();
        foreach ($unorderedRows as $row) {
            $aSet[$row['idAnnotationSet']][] = $row;
        }
        // reorder rows to put Target on top of each annotatioset
        $rows = array();
        $idHeaderLayer = -1;
        foreach ($aSet as $asRows) {
            $hasTarget = false;
            foreach ($asRows as $row) {
                if ($row['layerTypeEntry'] == 'lty_target') {
                    $row['idLayerType'] = 0;
                    $rows[] = $row;
                    $hasTarget = true;
                }
            }
            if ($hasTarget) {
                foreach ($asRows as $row) {
                    if ($row['layerTypeEntry'] != 'lty_target') {
                        $rows[] = $row;
                    }
                }
            } else {
                $headerLayer = $asRows[0];
                $headerLayer['layer'] = 'x';
                $headerLayer['startChar'] = -1;
                $headerLayer['idLayerType'] = 0;
                $headerLayer['idLayer'] = $idHeaderLayer--;
                $rows[] = $headerLayer;
                foreach ($asRows as $row) {
                    $rows[] = $row;
                }
            }
        }

        // CE-FE
        $ltCEFE = new LayerType();
        $ltCEFE->getByEntry('lty_cefe');
        $queryLabelType = $as->getLayerNameCnxFrame($idSentence);
        $cefe = $queryLabelType->chunkResultMany('idLayer',['idFrame','name'],'A');
        
        $level = Manager::getSession()->fnbr20Level;
        if ($level == 'BEGINNER') {
            $layersToShow = Manager::getConf('fnbr20.beginnerLayers');
        } else {
            $layersToShow = Manager::getSession()->fnbr20Layers;
            if ($layersToShow == '') {
                $user = Manager::getLogin()->getUser();
                $layersToShow = Manager::getSession()->fnbr20Layers = $user->getConfigData('fnbr20Layers');
            }
        }

        $wordsChars = $as->getWordsChars($idSentence);
        $chars = $wordsChars->chars;
        $line = [];
        $idLayerRef = -1;
        $lastLayerTypeEntry = '';
        // each row is a Label - the loop aggregates labels in Layers
        foreach ($rows as $row) {
            $idLT = $row['idLayerType'];
            if ($idLT != 0) {
                if (!in_array($idLT, $layersToShow)) {
                    //  mdump('*'.$idLT);
                    continue;
                }
            }
            $idLayer = $row['idLayer'];
            if ($idLayer != $idLayerRef) {
                // if lastLayer=CE, try to add the layers for CE-FE
                if ($lastLayerTypeEntry == 'lty_ce') {
                    foreach($cefe as $idLayerCEFE => $frame) {
                        $line[$idLayerCEFE] = new \StdClass();
                        $line[$idLayerCEFE]->idAnnotationSet = $row['idAnnotationSet'];
                        $line[$idLayerCEFE]->idLayerType = "{$ltCEFE->getId()}";
                        $line[$idLayerCEFE]->layerTypeEntry = $idLayerCEFE;
                        $line[$idLayerCEFE]->idLayer = $idLayerCEFE;
                        $line[$idLayerCEFE]->layer = $frame[1] . '.FE';
                        $line[$idLayerCEFE]->ni = '';
                        $line[$idLayerCEFE]->show = true;
                        $cefeData = $as->getCEFEData($idSentence, $idLayerCEFE)->getResult();
                        foreach($cefeData as $labelCEFE) {
                            if ($labelCEFE['startChar'] > -1) {
                                $posChar = $labelCEFE['startChar'];
                                while ($posChar <= $labelCEFE['endChar']) {
                                    $field = 'wf' . $posChar;
                                    $line[$idLayerCEFE]->$field = $labelCEFE['idLabelType'];
                                    $posChar += 1;
                                }
                            }
                        }
                    }
                }
                $line[$idLayer] = new \StdClass();
                $line[$idLayer]->idAnnotationSet = $row['idAnnotationSet'];
                $line[$idLayer]->idLayerType = $row['idLayerType'];
                $line[$idLayer]->layerTypeEntry = $row['layerTypeEntry'];
                $line[$idLayer]->idLayer = $idLayer;
                if ($row['idLayerType'] == 0) {
                    $line[$idLayer]->layer = 'AS_' . $row['idAnnotationSet'];
                } else {
                    $line[$idLayer]->layer = $row['layer'];
                }
                $line[$idLayer]->ni = '';
                $line[$idLayer]->show = true;
                $idLayerRef = $idLayer;
                $lastLayerTypeEntry = $row['layerTypeEntry'];
            }
            if ($row['startChar'] > -1) {
                $posChar = $row['startChar'];
                $i = 0;
                while ($posChar <= $row['endChar']) {
                    $field = 'wf' . $posChar;
                    if ($row['layer'] == 'Target') {
                        $line[$idLayer]->$field = $chars[$posChar]['char'];
                    } else {
                        $line[$idLayer]->$field = $row['idLabelType'];
                    }
                    $posChar += 1;
                }
            }
        }

// last, create data
        $data = array();
        foreach ($line as $idLine => $layer) {
            $data[] = $layer;
        }
        return json_encode($data);
        //return $data;
    }

    public function putLayers($layers) {
        $annotationSet = new AnnotationSet();
        $transaction = $annotationSet->beginTransaction();
        $idAS = [];
        $hasFE = $annotationSet->putLayers($layers);
        foreach ($layers as $layer) {
            $idAnnotationSet = $layer->idAnnotationSet;
            $idAS[$idAnnotationSet] = $idAnnotationSet;
        }
        foreach ($idAS as $idAnnotationSet) {
            if ($hasFE[$idAnnotationSet]) {
                $annotationSet->getById($idAnnotationSet);
                $annotationSet->setIdAnnotationStatus(Base::getAnnotationStatus());
                $annotationSet->save();
            }
        }
        $transaction->commit();
    }

    public function addFELayer($idAnnotationSet) {
        $annotationSet = new AnnotationSet($idAnnotationSet);
        $annotationSet->addFELayer();
        $this->render();
    }
    
    public function getFELabels($idAnnotationSet, $idSentence) {
        $annotationSet = new AnnotationSet($idAnnotationSet);
        $queryLabelType = $annotationSet->getLabelTypesFE($idSentence, true);
        $rows = $queryLabelType->getResult();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['idLayer']][$row['idLabelType']] = [
                'label' => $row['labelType'],
                'idColor' => $row['idColor'],
                'coreType' => $row['coreType']
            ];
        }
        return $result;
    }

    public function delFELayer($idAnnotationSet) {
        $annotationSet = new AnnotationSet($idAnnotationSet);
        $annotationSet->delFELayer();
        $this->render();
    }

    public function validation($as, $validation, $feedback = '') {
        $annotationSet = new AnnotationSet();
        foreach ($as as $idAnnotationSet => $o) {
            $annotationSet->getById($idAnnotationSet);
            $annotationSet->setIdAnnotationStatus(Base::getAnnotationStatus(true, $validation));
            $annotationSet->save();
            if ($validation == '0') { // ast_disapp 
                $this->notifySupervised($annotationSet, $feedback);
            }
        }
    }

    public function notifySupervised($annotationSet, $feedback = '') {
        $idLU = $annotationSet->getIdLU();
        $user = Base::getCurrentUser();
        $userSupervised = $user->getUserSupervisedByIdLU($idLU);
        if ($userSupervised) {
            $emailService = MApp::getService('fnbr20', '', 'email');
            $email = $userSupervised->getPerson()->getEmail();
            $to[$email] = $email;
            $subject = 'FNBr - AnnotationSet Disapproved';
            $body = "<p>From supervisor: " . $user->getLogin() . ' - ' . $user->getPerson()->getName() . "</p>";
            $subCorpus = $annotationSet->getSubCorpus();
            $body .= "<p>SubCorpus [" . $subCorpus->getTitle() . '] - Sentence ['. $annotationSet->getIdSentence() . ']  disapproved. Please, correct it.</p>';
            $body .= "<p>Message: " . $feedback . "</p>";
            $emailService->sendSystemEmail($to, $subject, $body);
        } else {
            throw new \Exception("No supervised user.");
        }
    }

    public function notifySupervisor($as) {
        $body = '';
        $annotationSet = new AnnotationSet();
        foreach ($as as $idAnnotationSet => $o) {
            $annotationSet->getById($idAnnotationSet);
            $status = $this->getSubCorpusStatus($annotationSet->getIdSubCorpus());
            if ($status->status->code == 1) {
                $subCorpus = $annotationSet->getSubCorpus();
                $body .= "<p>SubCorpus [" . $subCorpus->getTitle() . '] completed.</p>';
            }
        }
        if ($body != '') {
            $emailService = MApp::getService('fnbr20', '', 'email');
            $user = Base::getCurrentUser();
            $userLevel = $user->getUserLevel();
            if ($userLevel == 'BEGINNER') {
                $idSupervisor = $user->getConfigData('fnbr20JuniorUser');
            } else if ($userLevel == 'JUNIOR') {
                $idSupervisor = $user->getConfigData('fnbr20SeniorUser');
            } else if ($userLevel == 'SENIOR') {
                $idSupervisor = $user->getConfigData('fnbr20MasterUser');
            }
            $supervisor = new User($idSupervisor);
            $email = $supervisor->getPerson()->getEmail();
            $to[$email] = $email;
            $subject = 'FNBr - SubCorpus Completed';
            $body = "<p>From annotator: " . $user->getLogin() . ' - ' . $user->getPerson()->getName() . "</p>" . $body;
            $emailService->sendSystemEmail($to, $subject, $body);
        } else {
            throw new \Exception("No completed Set");
        }
    }

    public function listCxn($cxn = '', $idLanguage = '') {
        $construction = new Construction();
        $filter = (object) ['cxn' => $cxn, 'idLanguage' => $idLanguage];
        $constructions = $construction->listByFilter($filter)->asQuery()->chunkResult('idConstruction', 'name');
        $result = array();
        foreach ($constructions as $idCxn => $name) {
            $node = array();
            $node['id'] = 'c' . $idCxn;
            $node['text'] = $name;
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function listSubCorpusCxn($idCxn) {
        $sc = new SubCorpus();
        $scs = $sc->listByCxn($idCxn)->asQuery()->getResult();
        foreach ($scs as $sc) {
            $node = array();
            $node['id'] = 's' . $sc['idSubCorpus'];
            $node['text'] = $sc['name'] . ' [' . $sc['quant'] . ']';
            $node['state'] = 'open';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function headerMenu($wordform) {
        $wf = new WordForm();
        $lus = $wf->listLUByWordForm($wordform);
        return json_encode($lus);
    }

    public function addManualSubcorpus($data) {
        $sc = new SubCorpus();
        if ($data->idLU != '') {
            $sc->addManualSubcorpusLU($data);
        } else {
            $sc->addManualSubcorpusCxn($data);
        }
    }

    public function cxnGridData() {
        $cxn = new Construction();
        $criteria = $cxn->listAll();
        $data = $cxn->gridDataAsJSON($criteria);
        return $data;
    }

    public function listCorpus($corpusName = '', $idLanguage = '') {
        $corpus = new Corpus();
        $filter = (object) ['corpus' => $corpusName, 'idLanguage' => $idLanguage];
        $corpora = $corpus->listByFilter($filter)->asQuery()->chunkResult('idCorpus', 'name');
        $result = array();
        foreach ($corpora as $idCorpus => $name) {
            $node = array();
            $node['id'] = 'c' . $idCorpus;
            $node['text'] = $name;
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function listCorpusDocument($idCorpus) {
        $doc = new Document();
        $docs = $doc->listByCorpus($idCorpus)->asQuery()->getResult();
        foreach ($docs as $doc) {
            if ($doc['idDocument']) {
                $node = array();
                $node['id'] = 'd' . $doc['idDocument'];
                $node['text'] = $doc['name'] . ' [' . $doc['quant'] . ']';
                $node['state'] = 'open';
                $result[] = $node;
            }
        }
        return json_encode($result);
    }
    
    public function changeStatusAS($arrayAS, $newStatus) {
        $as = new AnnotationSet();
        foreach($arrayAS as $idAnnotationStatus) {
            $as->getById($idAnnotationStatus);
            $as->setIdAnnotationStatus($newStatus);
            $as->save();
        }
    }

}
