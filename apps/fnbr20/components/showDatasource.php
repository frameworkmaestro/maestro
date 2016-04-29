<?php

class showDatasource extends MLabel {

	public function onCreate(){
		parent::onCreate();
		$this->setText('Banco de Dados: ' . Manager::getConf('farina.db') . '    ');
		$this->fontSize = '15px';
		$this->color = 'blue';
	}

}


?>