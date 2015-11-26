<?php

class FilesController extends MController {

    public function inline() {
        $file = \Manager::getFrameworkPath('var/files/' . $this->data->id);
        $stream = file_get_contents($file);
        $this->renderBinary($stream);
    }

    public function save() {
        $file = \Manager::getFrameworkPath('var/files/' . $this->data->id);
        $this->renderDownload($file);
    }

}