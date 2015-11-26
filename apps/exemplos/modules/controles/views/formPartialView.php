<?php

class formPartialView extends MForm {

    function __construct() {
        parent::__construct('Form Partial View',"main/controls");
    }

    function createFields() {
        $this->setFieldsFromXML('formPartialView1.xml');
        $this->addFieldsFromXML('formPartialView2.xml');
        $this->insertFieldsFromXML('formPartialView3.xml');
        $this->addFieldsFromXML('formPartialView4.xml');
        $this->setData($this->data->object);
    }
}

?>
