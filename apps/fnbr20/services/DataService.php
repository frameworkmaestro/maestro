<?php

use Maestro\Types\MFile;

Manager::import("fnbr20\models\*");

class DataService extends MService
{

    public function getLanguage()
    {
        $language = new Language();
        return $language->listForCombo()->asQuery()->chunkResult('idLanguage', 'language');
    }

    public function getPOS()
    {
        $pos = new POS();
        return $pos->listForCombo()->asQuery()->chunkResult('idPOS', 'name');
    }

    public function exportFramesToJSON($idFrames)
    {
        $frameModel = new Frame();
        $frames = $frameModel->listForExport($idFrames)->asQuery()->getResult();
        $feModel = new FrameElement();
        $entry = new Entry();
        foreach ($frames as $i => $frame) {
            $entity = new Entity($frame['idEntity']);
            $frames[$i]['entity'] = [
                'idEntity' => $entity->getId(),
                'alias' => $entity->getAlias(),
                'type' => $entity->getType(),
                'idOld' => $entity->getIdOld()
            ];
            $frames[$i]['fes'] = [];
            $fes = $feModel->listForExport($frame['idFrame'])->asQuery()->getResult();
            foreach ($fes as $j => $fe) {
                $frames[$i]['fes'][$j] = $fe;
                $entityFe = new Entity($fe['idEntity']);
                $frames[$i]['fes'][$j]['entity'] = [
                    'idEntity' => $entityFe->getId(),
                    'alias' => $entityFe->getAlias(),
                    'type' => $entityFe->getType(),
                    'idOld' => $entityFe->getIdOld()
                ];
                $coreset = $feModel->listCoreSet($fe['idFrameElement'])->asQuery()->getResult();
                $frames[$i]['fes'][$j]['coreset'] = $coreset;
                $excludes = $feModel->listExcludes($fe['idFrameElement'])->asQuery()->getResult();
                $frames[$i]['fes'][$j]['excludes'] = $exclude;
                $requires = $feModel->listRequires($fe['idFrameElement'])->asQuery()->getResult();
                $frames[$i]['fes'][$j]['requires'] = $requires;
                $color = new Color($fe['idColor']);
                $frames[$i]['fes'][$j]['color'] = [
                    'name' => $color->getName(),
                    'rgbFg' => $color->getRgbFg(),
                    'rgbBg' => $color->getRgbBg(),
                ];
                $entries = $entry->listForExport($fe['entry'])->asQuery()->getResult();
                foreach ($entries as $n => $e) {
                    $frames[$i]['fes'][$j]['entries'][] = $e;
                }
            }
            $entries = $entry->listForExport($frame['entry'])->asQuery()->getResult();
            foreach ($entries as $j => $e) {
                $frames[$i]['entries'][] = $e;
            }
        }
        $result = json_encode($frames);
        return $result;
    }

    public function importFramesFromJSON($json)
    {
        $frames = json_decode($json);
        $frame = new Frame();
        $fe = new FrameElement();
        $entity = new Entity();
        $entry = new Entry();
        $transaction = $frame->beginTransaction();
        try {
            foreach ($frames as $frameData) {
                // create entries
                $entries = $frameData->entries;
                foreach ($entries as $entryData) {
                    $entry->createFromData($entryData);
                }
                // create entity
                $entity->createFromData($frameData->entity);
                // craete frame
                $frameData->idEntity = $entity->getId();
                $frame->createFromData($frameData);
                // create frameElements
                $fes = $frameData->fes;
                foreach ($fes as $feData) {
                    // create fe entries
                    $entries = $feData->entries;
                    foreach ($entries as $entryData) {
                        $entry->createFromData($entryData);
                    }
                    // create fe entity
                    $entity->createFromData($feData->entity);
                    // craete frame
                    $feData->idEntity = $entity->getId();
                    $feData->idFrame = $frame->getId();
                    $fe->createFromData($feData);
                    $feData->idFrameElement = $fe->getId();
                }
                // create frameElements relations (fes must be created before)
                foreach ($fes as $feData) {
                    $fe->getById($feData->idFrameElement);
                    $fe->createRelationsFromData($feData);
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function parseDocWf($file)
    {
        $getOutput = function ($diff) {
            $output = '';
            foreach ($diff as $w) {
                if (!is_numeric($w)) {
                    $output .= $w . ' X ' . $w . "\n";
                }
            }
            return $output;
        };
        $words = [];
        $rows = file($file->getTmpName());
        foreach ($rows as $row) {
            //mdump($row);
            // excludes punctuation
            $row = strtolower(str_replace([',', '.', ';', '!', '?', ':', '"', '(', ')', '[', ']', '<', '>', '{', '}'], ' ', utf8_decode($row)));
            $row = str_replace("\t", " ", $row);
            $row = str_replace("\n", " ", $row);
            $row = utf8_encode(trim($row));

            if ($row == '') {
                continue;
            }
            $wordsTemp = explode(' ', $row);
            foreach ($wordsTemp as $word) {
                $word = str_replace("'","''", $word);
                $words[$word] = $word;
            }
        }
        $wf = new WordForm();
        $output = "";
        $i = 0;
        foreach ($words as $word) {
            if (trim($word) != '') {
                $lookFor[$word] = $word;
                if ($i++ == 200) {
                    $found = $wf->lookFor($lookFor);
                    $output .= $getOutput(array_diff($lookFor, $found));
                    $lookFor = [];
                    $i = 0;
                }
            }
        }
        if (count($lookFor)) {
            $found = $wf->lookFor($lookFor);
            $output .= $getOutput(array_diff($lookFor, $found));
        }
        $fileName = str_replace(' ', '_', $file->getName()) . '_docwf.txt';
        $mfile = MFile::file("\xEF\xBB\xBF" . $output, false, $fileName);
        return $mfile;
    }

    private function getFSTree($structure, $idEntity)
    {
        $tree = [];
        foreach ($structure as $node) {
            if ($node['idEntity'] == $idEntity) {
                $tree = [
                    'id' => $node['idEntity'],
                    'nick' => $node['nick'],
                    'typeSystem' => $node['typeSystem'],
                    'children' => []
                ];
            }
        }
        foreach ($structure as $node) {
            if ($node['source'] == $idEntity) {
                $tree['children'][$node['idEntity']] = $this->getFSTree($structure, $node['idEntity']);
            }
        }
        return $tree;
    }

    private function getFSTreeText($node, &$text, $ident = '') {
        $text .= $ident . $node['typeSystem'] . '_' . $node['nick'] . "\n";
        foreach($node['children'] as $child) {
            $this->getFSTreeText($child, $text, $ident . '    ');
        }
    }

    public function exportCxnToFS()
    {
        $fs = '';
        $construction = new Construction();
        $cxns = $construction->listAll()->asQuery()->getResult();
        foreach ($cxns as $cxn) {
            $idConstruction = $cxn['idConstruction'];
            $construction->getById($idConstruction);
            $structure = $construction->getStructure();
            $tree = $this->getFSTree($structure, $cxn['idEntity']);
            $this->getFSTreeText($tree, $fs);
            $fs .= "\n";
        }
        return $fs;
    }

}
