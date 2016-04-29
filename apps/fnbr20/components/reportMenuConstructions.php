<?php
Manager::import("fnbr\models\*");

class ReportMenuConstructions extends MBaseGroup {
    
    public function onCreate() {
        parent::onCreate();
        $data = Manager::getData();
        $db = $data->db;

        $construction = new Construction();
        $initials = $construction->listInitialsForReport()->asQuery()->chunkResult(0,0);        
        
        $i = 0;
        $p = new MContentPane('p');
        $p->addControl(new MLabel('Construções [Constructions]','black',false));
        $p->addControl(new MRawText('<br>'));
        foreach($initials as $char) {
            $p->addControl(new MLink($char,'',"report/listConstructions?db=" . $db . '#'.$char,$char,'listConstructions'));
            $p->addControl(new Mlabel('&nbsp;&nbsp'));
            if(++$i > 12) {
                $i = 0;
                $p->addControl(new MRawText('<br>'));
            }
        }
        
        $this->addControl($p);
        $this->addControl(new MSpacer());
        $this->setFieldset(false);
        
    }
    
    
}

/*
class ReportMenuConstructions extends MAccordion {

    public function onCreate() {
        parent::onCreate();
        $this->setId('reportMenuConstructions');

        $construction = new Construction();
        $constructionList = $construction->listForReport()->asQuery()->chunkResult();
        $baseGroup = new MBaseGroup("menuConstructions", 'Constructions');
        $baseGroup->setFieldSet(false);
        $j = 1;
        $char = '';
        $constructionTree = new MTree("constructionFrame");
        foreach ($constructionList as $i => $construction) {
            if ($char != substr($construction, 0, 1)) {
                $char = substr($construction, 0, 1);
                $k = $i+10000;
                $array[] = array($k, $char, NULL, 'root');
            }
            $construction = str_replace('\'',"\\'", $construction);
            $array[] = array($i, $construction, NULL , $k);
        }
        $action = "!" . MUI::setHref('centerPane','report/construction/#0#');
        $constructionTree->setAction($action);
        $constructionTree->setItemsFromArray($array);
        $baseGroup->addControl($constructionTree);
        $this->addControl($baseGroup);
    }

}
 * 
 */

?>