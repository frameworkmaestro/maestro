<?php

class repEzPDFLinhas extends MEzPDFReport {

    public function load() {
        // escreve 100 linhas no documento
        for ($i = 0; $i < 100; $i++) {
            $r = $i / 100;
            $g = $i / 200;
            $b = (100 - $i) / 100;
            // altera a cor da fonte   
            $this->setColor($r, $g, $b);
            // adiciona o texto no documento - avanÃ§a automaticamente para proxima linha
            $this->text((($i == 0) ? 'Fonte: ' . $font . ' - ' : '') . 'Linha = ' . $i . '[ y = ' . $this->getY() . ' ] ');
        }
        // executa o report obtendo a url do arquivo gerado
        $url = $this->execute();
        $this->page->window($url);
    }

}

?>
