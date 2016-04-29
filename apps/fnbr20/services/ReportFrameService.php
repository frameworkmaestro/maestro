<?php

Manager::import("fnbr20\models\*");

class ReportFrameService extends MService
{

    public function listFrames($data, $idLanguage = '')
    {
        $frame = new Frame();
        $filter = (object) ['lu' => $data->lu, 'fe' => $data->fe, 'frame' => $data->frame, 'idLanguage' => $idLanguage];
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

    public function listLUs($idFrame, $idLanguage)
    {
        $result = array();
        $lu = new ViewLU();
        $lus = $lu->listByFrame($idFrame, $idLanguage)->asQuery()->chunkResult('idLU', 'name');
        foreach ($lus as $idLU => $name) {
            $node = array();
            $node['id'] = 'l' . $idLU;
            $node['text'] = $name;
            $node['state'] = 'open';
            $result[] = $node;
        }
        return json_encode($result);
    }

    public function decorate($description, $styles)
    {
        $decorated = "";
        $sentence = utf8_decode($description);
        $decorated = preg_replace_callback(
            "/\#([^\s\.\,\;\?\!]*)/i", 
            function ($matches) use ($styles) {
                $m = substr($matches[0], 1);
                $l = strtolower($m);
                $s = $styles[utf8_encode($l)];
                if ($s) {
                    return "<span class='fe_{$l}'>{$m}</span>";
                }
                foreach ($styles as $s) {
                    $p = strpos(utf8_encode($l), $s['fe']);
                    if ($p === 0) {
                        return "<span class='fe_{$s['fe']}'>{$m}</span>";
                    }
                }
                return $m;
            }, 
            $sentence
        );
        return utf8_encode($decorated);
    }

    public function getFEData($idFrame)
    {
        $frameElement = new FrameElement();
        $styles = $frameElement->getStylesByFrame($idFrame);
        $fes = $frameElement->listForReport($idFrame)->asQuery()->getResult();
        $core = [];
        foreach ($fes as $fe) {
            $fe['lower'] = strtolower($fe['name']);
            $fe['description'] = $this->decorate($fe['description'], $styles);
            if ($fe['coreType'] == 'cty_core') {
                $core[] = $fe;
            } else {
                $noncore[] = $fe;
            }
        }
        return [
            'styles' => $styles,
            'core' => $core,
            'noncore' => $noncore
        ];
    }

    public function getRelations($frame)
    {
        $relations = [];
        $directRelations = $frame->listDirectRelations();
        foreach($directRelations as $entry => $row) {
            $relations[$entry] = '';
            $i = 0;
            foreach($row as $r) {
                $relations[$entry] .= ($i++ > 0 ? ', ' : '') . $r['name'];
            }
        }
        $inverseRelations = $frame->listInverseRelations(); 
        foreach($inverseRelations as $entry => $row) {
            $entry = $entry . '_inv';
            $relations[$entry] = '';
            $i = 0;
            foreach($row as $r) {
                $relations[$entry] .= ($i++ > 0 ? ', ' : '') . $r['name'];
            }
        }
        ksort($relations);
        mdump($relations);
        return $relations;
    }

}
