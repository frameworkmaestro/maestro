<?php

class MainMenu2 extends MAccordion {

    public function onCreate() {
        parent::onCreate();
        $this->setId('exemplosMainMenu');
        $baseGroup = new MBaseGroup("menu{$i}", "aaaa");
        $this->addControl($baseGroup);


    }

}

?>