<?php

class MLookupComponente extends MLookupFieldValue {

    protected $object;
    protected $idHidden;

    public function onAfterCreate() {
        parent::onAfterCreate();
        $id = $this->getId();
        if (!$this->idHidden) {
            $this->idHidden = 'id' . ucfirst($id);
        }
        $this->setFilter($id);
    }

    public function getFieldValue() {
        return '';
    }

    public function getObjectId() {
        return '';
    }

    public function setValue($value = NULL) {
        if (is_object($value)) {
            $this->object = $value;
            $this->property->value = $this->getFieldValue();
        }
    }

    public function generateInner() {
        $hidden = new MHiddenField($this->idHidden, $this->getObjectId());
        parent::generateInner();
        $this->inner = array($hidden, $this->inner);
    }

}

?>