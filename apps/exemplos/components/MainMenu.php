<?php

class MainMenu extends MControl {

    public function onCreate() {
        parent::onCreate();
        $this->setClassName('maccordion');
        $this->setId('exemplosMainMenu');
        $this->width = "100%";

        $actions = Manager::getActions('exemplos');
        $goTo = Manager::getAppURL();
        foreach ($actions as $i => $group) {
            $baseGroup = new MDiv(array("id"=>"menu{$i}", "title"=>$group[0]));
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