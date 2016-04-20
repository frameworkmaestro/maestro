<?php

class GuiaIndice extends MControl{
    
    public function setItem($item) {
        $action = Manager::getAction($item);
        //$this->addControl(new MText(['text' => $action[0]]));
        foreach($action[ACTION_ACTIONS] as $a) {
            $this->addControl(new MLink(['text' => $a[0],'action' => '>'.$a[1]]));
        }
    }

    public function generate() {
        return $this->getPainter()->mvcontainer($this);
    }
    
}
