<?php

class MainMenu extends MContainerControl {

    public function onCreate() {
        parent::onCreate();
        $this->setClassName('maccordion');
        $this->setId('guiaMainMenu');

        $actions = Manager::getActions('guia');
        $goTo = Manager::getBaseURL(false, true);
        mdump('---'. $goTo);
        foreach ($actions as $i => $group) {
            $baseGroup = new MContainerControl("mbasegroup", array("id"=>"menu{$i}", "caption"=>$group[0]));
            $baseGroup->fieldSet = false;

            $tree = new MControl("mtree", array("id"=>"tree{$i}"));
            $tree->onSelect = "manager.doGet('{$goTo}' + '/' + node.action);";
            $groupActions = $group[5];
            $array = array();
            foreach($groupActions as $j => $action){
                $array[] = array($j, $action[0], $action[1], 'root');
            }
            $tree->arrayItems = $array;
            $baseGroup->addControl($tree);
            $this->addControl($baseGroup);
        }
    }

}

?>