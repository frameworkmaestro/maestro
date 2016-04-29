<?php

class ListElements extends MTContainer {

    public function onCreate() {
        parent::onCreate();
        $this->setId('listElements');
        $data = Manager::getData();
        if (is_array($data->elements)) {
            foreach($data->elements as $element) {
                $name = new MLabel($element->name . '[' . $element->abbreviation . ']');
                $name->color = $element->color[0];
                $name->backgroundColor = $element->color[1];
                $line = new MHContainer('', array(
                   $name,
                   new MLabel('&nbsp'),
                   new MLabel($element->definition)
                ));
                $this->addControl($line);
            }
            
        }
    }    

}

?>
