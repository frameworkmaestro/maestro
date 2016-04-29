<?php
Manager::import("fnbr\models\*");

class ReportMenuFrames extends MBaseGroup {
    
    public function onCreate() {
        parent::onCreate();
        $data = Manager::getData();
        $db = $data->db;

        $frame = new Frame();
        if ($db == 'fnbrasil') {
            $initials = $frame->listInitialsForReport()->asQuery()->chunkResult(0,0);        
        } else {
            $initials = $frame->listInitialsForReportCopa($data->lang)->asQuery()->chunkResult(0,0);        
        }    
        
        $labelIndex = array(''=>'Índice de Frames','pt'=>'Índice de Frames','en'=>'Frame Index','es'=>'Índice de Frames');
        
        $i = 0;
        $p = new MContentPane('p');
        $p->addControl(new MLabel($labelIndex[$data->lang],'black',false));
        $p->addControl(new MRawText('<br>'));
        foreach($initials as $char) {
            $p->addControl(new MLink($char,'','report/listFrames?db=' . $db . '#'.$char,$char,'listFrames'));
            $p->addControl(new Mlabel('&nbsp;&nbsp'));
            if(++$i > 12) {
                $i = 0;
                $p->addControl(new MRawText('<br>'));
            }
        }
        
        $this->addControl($p);
        $this->addControl(new MSpacer());
        $this->setFieldset(false);
        //$this->addControl($iframe);
        
    }
    
    
}

/*
class ReportMenuFrames extends MAccordion {

    public function onCreate() {
        parent::onCreate();
        $this->setId('reportMenu');

        $frame = new Frame();
        $frameList = $frame->listForReport()->asQuery()->chunkResult();
        $baseGroup = new MBaseGroup("menuFrames", 'Frames');
        $baseGroup->setFieldSet(false);
        $j = 1;
        $char = '';
        $frameTree = new MTree("treeFrame");
        foreach ($frameList as $i => $frame) {
            if ($char != substr($frame, 0, 1)) {
                $char = substr($frame, 0, 1);
                $k = $i+10000;
                mdump($k);
                $array[] = array($k, $char, NULL, 'root');
            }
            $frame = str_replace('\'',"\\'", $frame);
            $array[] = array($frame, $frame, NULL , $k);
        }
        $href = Manager::getConf('farina.url') . "/frame/#0#.xml?banner=";
        $action = "!document.getElementsByName('frameContent')[0].src = \"{$href}\";";
        $action = "!console.log(parent.frameContent.location);";
        $action = "!parent.frameContent.location = \"{$href}\";";
        //$action = "!" . MUI::setHref('centerPane','report/frame/#0#');
        $frameTree->setAction($action);
        $frameTree->setItemsFromArray($array);
        $baseGroup->addControl($frameTree);
        $this->addControl($baseGroup);
    }

}
 * 
 */

?>