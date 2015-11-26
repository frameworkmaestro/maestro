<?php

class repEzPDFFontes extends MEzPDFReport {

    private $afmFonts;

    public function load() {
        // array com os nomes das fontes e arquivos correspondentes
        // fontes na pasta public/fonts/afm
        $this->afmFonts = array(
            'arial.afm' => 'Arial',
            'vera.afm' => 'BitStream Vera Sans',
            'veramono.afm' => 'BitStream Vera Sans Mono',
            'verase.afm' => 'BitStream Vera Serif',
            'Courier.afm' => 'Courier',
            'Helvetica.afm' => 'Helvetica',
            'monofont.afm' => 'MonoFont',
            'Symbol.afm' => 'Symbol',
            'tahoma.afm' => 'Tahoma',
            'Times.afm' => 'Times',
            'verdana.afm' => 'Verdana',
            'ZapfDingbats.afm' => 'ZapfDingbats'
        );

        // texto base
        $texto = 'The quick brown fox jumps over the lazy dog. 0123456789 - áéíóúâêôçãõ';
        foreach ($this->afmFonts as $f => $n) {
            if ($n != 'Symbol') {
                // seleciona a fonte
                $this->setFont($f);
                $this->text("Font: $n  - File: $f");
                for ($size = 6; $size < 13; $size+=2) {
                    // adiciona o texto no documento - avança automaticamente para proxima linha
                    $this->text('Size ' . $size . ': ' . $texto, $size);
                }
                $this->text('');
            }
        }
        // executa o report obtendo a url do arquivo gerado
        $url = $this->execute();
        $this->page->window($url);
    }

}

?>
