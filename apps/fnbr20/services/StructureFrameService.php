<?php

Manager::import("fnbr20\models\*");

class StructureFrameService extends MService
{

    public function listFrames($data, $idLanguage = '')
    {
        $frame = new ViewFrame();
        $filter = (object) ['idDomain' => $data->idDomain, 'lu' => $data->lu, 'fe' => $data->fe, 'frame' => $data->frame, 'idLanguage' => $idLanguage];
        $frames = $frame->listByFilter($filter)->asQuery()->getResult(\FETCH_ASSOC);
        $result = array();
        foreach ($frames as $row) {
            $node = array();
            $node['id'] = 'f' . $row['idFrame'];
            $node['text'] = $row['name'];
            $node['state'] = 'closed';
            $node['entry'] = $row['entry'];
            $result[] = $node;
        }
        return $result;
    }

    public function listFEsLUs($idFrame, $idLanguage)
    {
        $result = array();
        $icon = [
            "cty_core" => "fa fa-circle",
            "cty_peripheral" => "fa fa-dot-circle-o",
            "cty_extra-thematic" => "fa fa-circle-o",
            "cty_core-unexpressed" => "fa fa-circle-o"
        ];
        $frame = new Frame($idFrame);
        $fes = $frame->listFE()->asQuery()->getResult();
        foreach ($fes as $fe) {
            $node = array();
            $node['id'] = 'e' . $fe['idFrameElement'];
            $style = 'background-color:#' . $fe['rgbBg'] . ';color:#' . $fe['rgbFg'] . ';';
            $node['text'] = "<span style='{$style}'>" . $fe['name'] . "</span>";
            $node['state'] = 'closed';
            $node['entry'] = $fe['entry'];
            $node['iconCls'] = 'icon-blank fa-icon ' . $icon[$fe['coreType']];
            $result[] = $node;
        }
        $lu = new ViewLU();
        $lus = $lu->listByFrame($idFrame, $idLanguage)->asQuery()->chunkResult('idLU', 'name');
        foreach ($lus as $idLU => $name) {
            $node = array();
            $node['id'] = 'l' . $idLU;
            $node['text'] = $name;
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function listSubCorpus($idLU)
    {
        $sc = new ViewSubCorpusLU();
        $scs = $sc->listByLU($idLU)->asQuery()->getResult();
        foreach ($scs as $sc) {
            $node = array();
            $node['id'] = 's' . $sc['idSubCorpus'];
            $node['text'] = $sc['name'] . ' [' . $sc['quant'] . ']';
            $node['state'] = 'open';
            $result[] = $node;
        }
        $this->data->result = $result;
        return json_encode($result);
    }

    public function listConstraintsFE($idFrameElement, $idLanguage)
    {
        $service = MApp::getService('fnbr20', '', 'structureconstraints');
        $result = $service->listConstraintsFE($idFrameElement);
        return $result;
    }

    public function getSubCorpusTitle($idSubCorpus, $idLanguage, $isCxn)
    {
        $sc = $isCxn ? new ViewSubCorpusCxn() : new ViewSubCorpusLU();
        $title = $sc->getTitle($idSubCorpus, $idLanguage);
        return $title;
    }

    public function getDocumentTitle($idDocument, $idLanguage)
    {
        $doc = new Document();
        $filter = (object) ['idDocument' => $idDocument];
        $result = $doc->listByFilter($filter)->asQuery()->getResult();
        return 'Document:' . $result[1];
    }

    public function decorateSentence($sentence, $labels)
    {
        $decorated = "";
        $ni = "";
        $i = 0;
        $sentence = utf8_decode($sentence);
        foreach ($labels as $label) {
            $style = 'background-color:#' . $label[3] . ';color:#' . $label[2] . ';';
            if ($label[0] >= 0) {
                $decorated .= substr($sentence, $i, $label[0] - $i);
                $decorated .= "<span style='{$style}'>" . substr($sentence, $label[0], $label[1] - $label[0] + 1) . "</span>";
                $i = $label[1] + 1;
            } else { // null instantiation
                $ni .= "<span style='{$style}'>" . $label[4] . "</span> " . $decorated;
            }
        }
        $decorated = utf8_encode($ni . $decorated . substr($sentence, $i));
        return $decorated;
    }

    public function listAnnotationSet($idSubCorpus)
    {
        $as = new ViewAnnotationSet();
        $sentences = $as->listBySubCorpus($idSubCorpus)->asQuery()->getResult();
        $annotation = $as->listFECEBySubCorpus($idSubCorpus);
        $result = array();
        foreach ($sentences as $sentence) {
            $node = array();
            $node['idAnnotationSet'] = $sentence[0];
            $node['idSentence'] = $sentence[1];
            if ($annotation[$sentence[1]]) {
                $node['text'] = $this->decorateSentence($sentence[2], $annotation[$sentence[1]]);
            } else {
                $node['text'] = $sentence[2];
            }
            $node['status'] = $sentence[3];
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function getLayers($params, $idLanguage)
    {
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

        $as = new AnnotationSet($idAnnotationSet);

        // get words/chars
        $wordsChars = $as->getWordsChars($idSentence);
        $words = $wordsChars->words;
        $chars = $wordsChars->chars;

        $result = array();
        foreach ($words as $i => $word) {
//            $field = 'wf' . $word[0];
            $fieldData = $i; //$field . '_data';
            $result[$fieldData]->word = $word[1];
            $result[$fieldData]->startChar = $word[2];
            $result[$fieldData]->endChar = $word[3];
        }
        $layers['words'] = json_encode($result);

        $result = array();
        foreach ($chars as $i => $char) {
            $fieldData = 'wf' . $i; //$field . '_data';
            $result[$fieldData]->order = $char[0];
            $result[$fieldData]->char = $char[1];
            $result[$fieldData]->word = $char[2];
        }
        $layers['chars'] = json_encode($result);

        // get hiddenColumns/frozenColumns/Columns using $words
        $frozenColumns[] = array(
            "field" => "layer",
            "width" => '60',
            "title" => "S_" . $idSentence,
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
            $width = 15;
            $columns[] = array(
                "hidden" => 'false',
                "field" => 'wf' . $i,
                "width" => 13,
                "resizable" => "false",
                "title" => $char[1],
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
            $result[$row[0]] = ['idAnnotationSet' => $row[2], 'nameLayer' => $row[1], 'currentLabel' => '0', 'currentLabelPos' => 0];
        }
        $layers['layers'] = json_encode($result);

        // get AnnotationSets
        $result = array();
        $annotationSets = $as->getAnnotationSets($idSentence);
        foreach ($annotationSets as $row) {
            $result[$row[0]] = ['idAnnotationSet' => $row[0], 'name' => $row[1], 'show' => true];
        }
        $layers['annotationSets'] = json_encode($result);

        // get Labels
        $result = array();
        $asLabels = $as->getLabels($idSentence);
        foreach ($asLabels as $row) {
            $result[$row[0]][$row[1]] = ['idLabelType' => $row[2]];
        }
        $layers['labels'] = json_encode($result);

        // get LabelTypes
        $result = array();
        // GL-GF
        $queryLabelType = $as->getLabelTypesGLGF($idSentence)->asQuery();
        $rows = $queryLabelType->getResult();
        list($idLayer, $idLabelType, $labelType, $idColor, $coreType) = $queryLabelType->getColumnNumbers('idLayer,idLabelType,labelType,idColor,coreType');
        foreach ($rows as $row) {
            $result[$row[$idLayer]][$row[$idLabelType]] = array('label' => $row[$labelType], 'idColor' => $row[$idColor], 'coreType' => $row[$coreType]);
        }
        // GL
        $queryLabelType = $as->getLabelTypesGL($idSentence)->asQuery();
        $rows = $queryLabelType->getResult();
        list($idLayer, $idLabelType, $labelType, $idColor, $coreType) = $queryLabelType->getColumnNumbers('idLayer,idLabelType,labelType,idColor,coreType');
        foreach ($rows as $row) {
            $result[$row[$idLayer]][$row[$idLabelType]] = array('label' => $row[$labelType], 'idColor' => $row[$idColor], 'coreType' => $row[$coreType]);
        }
        // FE
        $queryLabelType = $as->getLabelTypesFE($idSentence);
        $rows = $queryLabelType->getResult();
        list($idLayer, $idLabelType, $labelType, $idColor, $coreType) = $queryLabelType->getColumnNumbers('idLayer,idLabelType,labelType,idColor,coreType');
        foreach ($rows as $row) {
            $result[$row[$idLayer]][$row[$idLabelType]] = array('label' => $row[$labelType], 'idColor' => $row[$idColor], 'coreType' => $row[$coreType]);
        }
        // CE
        $queryLabelType = $as->getLabelTypesCE($idSentence);
        $rows = $queryLabelType->getResult();
        list($idLayer, $idLabelType, $labelType, $idColor, $coreType) = $queryLabelType->getColumnNumbers('idLayer,idLabelType,labelType,idColor,coreType');
        foreach ($rows as $row) {
            $result[$row[$idLayer]][$row[$idLabelType]] = array('label' => $row[$labelType], 'idColor' => $row[$idColor], 'coreType' => $row[$coreType]);
        }
        $layers['labelTypes'] = json_encode($result);

        // get NIs
        //$niFields = array();
        $result = array();
        $queryNI = $as->getNI($idSentence, $idLanguage);
        $rows = $queryNI->getResult();
        list($idLayer, $idLabel, $feName, $idInstantiationType, $instantiationType, $idColor, $idLabelType) = $queryNI->getColumnNumbers('idLayer,idLabel,feName,idInstantiationType,instantiationType,idColor,idLabelType');
        foreach ($rows as $row) {
            $result[$row[$idLayer]][$row[$idLabelType]] = array(
                'fe' => $row[$feName],
                'idInstantiationType' => $row[$idInstantiationType],
                'label' => $row[$instantiationType],
                //'idSentenceWord' => $row[$idSentenceWord],
                'idColor' => $row[$idColor]
            );
            //$niFields[$row[$idLayer]] = 'wf' . $row[$idSentenceWord];
        }
        $layers['nis'] = (count($result) > 0) ? json_encode($result) : "{}";
        //$layers['niFields'] = json_encode($niFields);

        return $layers;
    }

    public function getLayersData($params, $idLanguage)
    {
        $idSentence = $params->idSentence;
        $idAnnotationSet = $params->idAnnotationSet;

        $as = new AnnotationSet($idAnnotationSet);
        $result = array();
        $queryLayersData = $as->getLayersData($idSentence);
        $unorderedRows = $queryLayersData->getResult();
        list($idAnnotationSet, $idLayerType, $idLayer, $nameLayer, $startChar, $endChar, $idLabelType, $idLabel) = $queryLayersData->getColumnNumbers('idAnnotationSet,idLayerType,idLayer,layer,startChar,endChar,idLabelType,idLabel');

        // get the annotationsets
        $aSet = array();
        foreach ($unorderedRows as $row) {
            $aSet[$row[$idAnnotationSet]][] = $row;
        }
        // reorder rows to put Target on top of each annotatioset
        $rows = array();
        $idHeaderLayer = -1;
        foreach ($aSet as $asRows) {
            $hasTarget = false;
            foreach ($asRows as $row) {
                if ($row[$nameLayer] == 'Target') {
                    $row[$idLayerType] = 0;
                    $rows[] = $row;
                    $hasTarget = true;
                }
            }
            if ($hasTarget) {
                foreach ($asRows as $row) {
                    if ($row[$nameLayer] != 'Target') {
                        $rows[] = $row;
                    }
                }
            } else {
                $headerLayer = $asRows[0];
                $headerLayer[$nameLayer] = 'x';
                $headerLayer[$startChar] = -1;
                $headerLayer[$idLayerType] = 0;
                $headerLayer[$idLayer] = $idHeaderLayer--;
                $rows[] = $headerLayer;
                foreach ($asRows as $row) {
                    $rows[] = $row;
                }
            }
        }

        $layersToShow = Manager::getSession()->fnbr20Layers;
        if ($layersToShow == '') {
            $user = Manager::getLogin()->getUser();
            $layersToShow = Manager::getSession()->fnbr20Layers = $user->getConfigData('fnbr20Layers');
        }
        $wordsChars = $as->getWordsChars($idSentence);
        $chars = $wordsChars->chars;

        $line = [];
        $idLayerRef = -1;
        foreach ($rows as $row) {
            $idLT = $row[$idLayerType];
            if ($idLT != 0) {
                if (!in_array($idLT, $layersToShow)) {
                    //  mdump('*'.$idLayerType);
                    continue;
                }
            }
            if ($row[$idLayer] != $idLayerRef) {
                $line[$row[$idLayer]] = new \StdClass();
                $line[$row[$idLayer]]->idAnnotationSet = $row[$idAnnotationSet];
                $line[$row[$idLayer]]->idLayerType = $row[$idLayerType];
                $line[$row[$idLayer]]->idLayer = $row[$idLayer];
                $line[$row[$idLayer]]->layer = ($row[$idLayerType] == 0) ? 'AS_' . $row[$idAnnotationSet] : $row[$nameLayer];
                $line[$row[$idLayer]]->ni = '';
                $line[$row[$idLayer]]->show = true;
                $idLayerRef = $row[$idLayer];
            }
            if ($row[$startChar] > -1) {
                $posChar = $row[$startChar];
                $i = 0;
                while ($posChar <= $row[$endChar]) {
                    $field = 'wf' . $posChar;
                    if ($row[$nameLayer] == 'Target') {
                        $line[$row[$idLayer]]->$field = $chars[$posChar][1];
                    } else {
                        $line[$row[$idLayer]]->$field = $row[$idLabelType];
                    }
                    $posChar += 1;
                }
            }
        }
        // last, create data
        $data = array();
        foreach ($line as $layer) {
            $data[] = $layer;
        }
        return json_encode($data);
        //return $data;
    }

    public function putLayers($layers)
    {
        $annotationSet = new AnnotationSet();
        $annotationSet->putLayers($layers);
    }

    public function addFELayer($idAnnotationSet)
    {
        $annotationSet = new AnnotationSet($idAnnotationSet);
        $annotationSet->addFELayer();
        $this->render();
    }

    public function delFELayer($idAnnotationSet)
    {
        $annotationSet = new AnnotationSet($idAnnotationSet);
        $annotationSet->delFELayer();
        $this->render();
    }

    public function listCnx($cnx = '', $idLanguage = '')
    {
        $construction = new Construction();
        $filter = (object) ['cnx' => $cnx, 'idLanguage' => $idLanguage];
        $constructions = $construction->listByFilter($filter)->asQuery()->chunkResult('idConstruction', 'name');
        $result = array();
        foreach ($constructions as $idCnx => $name) {
            $node = array();
            $node['id'] = 'c' . $idCnx;
            $node['text'] = $name;
            $node['state'] = 'closed';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function listSubCorpusCnx($idCnx)
    {
        $sc = new SubCorpus();
        $scs = $sc->listByCnx($idCnx)->asQuery()->getResult();
        foreach ($scs as $sc) {
            $node = array();
            $node['id'] = 's' . $sc[0];
            $node['text'] = $sc[1] . ' [' . $sc[2] . ']';
            $node['state'] = 'open';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function headerMenu($wordform)
    {
        $wf = new WordForm();
        $lus = $wf->listLUByWordForm($wordform);
        return json_encode($lus);
    }

    public function addManualSubcorpus($data)
    {
        $sc = new SubCorpus();
        if ($data->idLU != '') {
            $sc->addManualSubcorpusLU($data);
        } else {
            $sc->addManualSubcorpusCnx($data);
        }
    }

    public function cnxGridData()
    {
        $cnx = new Construction();
        $criteria = $cnx->listAll();
        $data = $cnx->gridDataAsJSON($criteria);
        return $data;
    }

    public function listCorpus($corpus = '', $idLanguage = '')
    {
        $corpus = new Corpus();
        $filter = (object) ['corpus' => $cnx, 'idLanguage' => $idLanguage];
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

    public function listCorpusDocument($idCorpus)
    {
        $doc = new Document();
        $docs = $doc->listByCorpus($idCorpus)->asQuery()->getResult();
        foreach ($docs as $doc) {
            if ($doc[0]) {
                $node = array();
                $node['id'] = 'd' . $doc[0];
                $node['text'] = $doc[1] . ' [' . $doc[2] . ']';
                $node['state'] = 'open';
                $result[] = $node;
            }
        }
        return json_encode($result);
    }

    public function deleteFrame($idFrame)
    {
        mdump('deleteFrame ' . $idFrame);
        $frame = new Frame($idFrame);
        $transaction = $frame->beginTransaction();
        try {
            $frameElement = new FrameElement();
            $filter = (object) ['idFrame' => $idFrame];
            $fes = $frameElement->listByFilter($filter)->asQuery()->getResult();
            foreach ($fes as $fe) {
                $frameElement->getById($fe['idFrameElement']);
                $frameElement->delete();
            }
            $frame->delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function addConstraintsFE($data)
    {
        try {
            $transaction = Manager::getDatabase(Manager::getConf('fnbr20.db'))->beginTransaction();
            if ($data->idFrame != '') {
                $constraint = Base::createEntity('CN', 'con');
                $cf = new \fnbr20\models\FrameElement($data->idFrameElement);
                $frame = new \fnbr20\models\Frame($data->idFrame);
                Base::createEntityRelation($constraint->getIdEntity(), 'rel_constraint_frame', $cf->getIdEntity(), $frame->getIdEntity());
            }
            if ($data->idSemanticType != '') {
                $constraint = Base::createEntity('CN', 'con');
                $cf = new \fnbr20\models\FrameElement($data->idFrameElement);
                $st = new \fnbr20\models\SemanticType($data->idSemanticType);
                Base::createEntityRelation($constraint->getIdEntity(), 'rel_constraint_semtype', $cf->getIdEntity(), $st->getIdEntity());
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }


}
