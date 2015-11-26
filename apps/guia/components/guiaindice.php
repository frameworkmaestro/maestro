<?php

class GuiaIndice extends MVContainer {
    
    public function setItem($item) {
        $action = Manager::getAction($item);
        $this->addControl(new MTextHeader('','1',$action[0]));
        foreach($action[ACTION_ACTIONS] as $a) {
            $this->addControl(new MLink('',$a[0],'>'.$a[1]));
        }
    }
    
}

?>