<?php

/* Copyright [2011, 2012, 2013] da Universidade Federal de Juiz de Fora
 * Este arquivo é parte do programa Framework Maestro.
 * O Framework Maestro é um software livre; você pode redistribuí-lo e/ou 
 * modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada 
 * pela Fundação do Software Livre (FSF); na versão 2 da Licença.
 * Este programa é distribuído na esperança que possa ser  útil, 
 * mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL 
 * em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título
 * "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software
 * Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

namespace Maestro\Utils;

use Maestro;

/**
 * Brief Class Description.
 * Complete Class Description.
 */
class MUtil
{

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $value1 (tipo) desc
     * @param $value2 (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function NVL($value1, $value2)
    {
        return ($value1 != NULL) ? $value1 : $value2;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $value1 (tipo) desc
     * @param $value2 (tipo) desc
     * @param $value3 (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function ifNull($value1, $value2, $value3)
    {
        return ($value1 == NULL) ? $value2 : $value3;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param &$value1 (tipo) desc
     * @param $value2 (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function setIfNull(&$value1, $value2)
    {
        if ($value1 == NULL)
            $value1 = $value2;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param &$value1 (tipo) desc
     * @param $value2 (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function setIfNotNull(&$value1, $value2)
    {
        if ($value2 != NULL)
            $value1 = $value2;
    }

    /**
     * @todo TRANSLATION
     * Retorna o valor booleano da variÃ¡vel
     * FunÃ§Ã£o utilizada para testar se uma variÃ¡vel tem um valor booleano, conforme definiÃ§Ã£o: serÃ¡ verdadeiro de
     *      for 1, t ou true... caso contrÃ¡rio serÃ¡ falso.
     *
     * @param $value (misc) valor a ser testado
     *
     * @returns (bool) value
     *
     */
    public static function getBooleanValue($value)
    {
        $trues = array('t', '1', 'true', 'True');

        if (is_bool($value)) {
            return $value;
        }

        return in_array($value, $trues);
    }

    /**
     * Retorna o valor float da variável, com base no locale atual (definido via setlocale)
     * @param $value (string) valor a ser convertido
     * @returns (float) value
     *
     */
    public static function getFloatValue($value)
    {
        $l = localeConv();
        $sign = (strpos($value, $l['negative_sign']? : '-') !== false) ? -1 : 1;
        $value = strtr($value, $l['positive_sign'] . $l['negative_sign'] . '()', '    ');
        $value = str_replace(' ', '', $value);
        $value = str_replace($l['currency_symbol']? : '$', '', $value);
        $value = str_replace($l['mon_thousands_sep']? : ',', '', $value);
        $value = str_replace($l['mon_decimal_point']? : '.', '.', $value);
        return (float) ($value * $sign);
    }

    /**
     * @todo TRANSLATION
     * Retorna o valor da variÃ¡vel sem os caracteres considerados vazios
     * FunÃ§Ã£o utilizada para remover os caracteres considerados vazios
     *
     * @param $value (misc) valor a ser substituido
     *
     * @returns (string) value
     *
     */
    public function removeSpaceChars($value)
    {
        $blanks = array("\r" => '', "\t" => '', "\n" => '', '&nbsp;' => '', ' ' => '');

        return strtr($value, $blanks);
    }

    /**
     * Retira os caracteres especiais.
     * @param <type> $string
     */
    public static function RemoveSpecialChars($string)
    {
        $specialCharacters = ['#','$','%','&','@','.','?','+','=','§','-','\\','/','!','"',"'"];
        $string = str_replace($specialCharacters, '', $string);

        $arrayStringsSpecialChars = array("À", "Á", "Â", "Ã", "Ä", "Å", "?", "á", "â", "ã", "ä", "å", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "ò", "ó", "ô", "õ", "ö", "ø", "È", "É", "Ê", "Ë", "è", "é", "ê", "ë", "Ç", "ç", "Ì", "Í", "Î", "Ï", "ì", "í", "î", "ï", "Ù", "Ú", "Û", "Ü", "ù", "ú", "û", "ü", "ÿ", "Ñ", "ñ");
        $arrayStringsNormalChars =  array("A", "A", "A", "A", "A", "A", "a", "a", "a", "a", "a", "a", "O", "O", "O", "O", "O", "O", "o", "o", "o", "o", "o", "o", "E", "E", "E", "E", "e", "e", "e", "e", "C", "c", "I", "I", "I", "I", "i", "i", "i", "i", "U", "U", "U", "U", "u", "u", "u", "u", "y", "N", "n");
        $string = str_replace($arrayStringsSpecialChars, $arrayStringsNormalChars, $string);

        return $string;
    }

    public static function listFiles($dir, $type = 'd', $extension = '')
    {
        $result = '';
        if (is_dir($dir)) {
            $thisdir = dir($dir);
            while ($entry = $thisdir->read()) {
                if (($entry != '.') && ($entry != '..') && (substr($entry, 0, 1) != '.')) {
                    if ($type == 'a') {
                        $result[$entry] = $entry;
                        next;
                    }
                    $isFile = is_file("$dir/$entry");
                    $isDir = is_dir("$dir/$entry");

                    if (($type == 'f') && ($isFile)) {
                        $fileExtension = substr(strrchr($entry, "."), 1);
                        if ($extension == '') {
                            $result[$entry] = $entry;
                        } elseif ($fileExtension == $extension) {
                            $result[$entry] = $entry;
                        }
                        next;
                    }

                    if (($type == "d") && ($isDir)) {
                        $result[$entry] = $entry;
                        next;
                    }
                }
            }
        }
        return $result;
    }

    public static function dirToArray($dir, &$array, $extension = 'php')
    {
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    self::dirToArray($dir . DIRECTORY_SEPARATOR . $value, $array, $extension);
                } else {
                    $fileName = $dir . DIRECTORY_SEPARATOR . $value;
                    $fileExtension = substr(strrchr($fileName, "."), 1);
                    if (($extension == '') || (($extension != '') && ($fileExtension == $extension))) {
                        $array[] = $fileName;
                    }
                }
            }
        }
    }

    /**
     * @todo TRANSLATION
     * Copia diretorio
     * Esta funcao copia o conteudo de um diretorio para outro
     *
     * @param $sourceDir (string) Diretorio de origem
     * @param $destinDir (string) Diretorio de destino
     *
     * @returns (string) value
     */
    public function copyDirectory($sourceDir, $destinDir)
    {
        if (file_exists($sourceDir) && file_exists($destinDir)) {
            $open_dir = opendir($sourceDir);

            while (false !== ( $file = readdir($open_dir) )) {
                if ($file != "." && $file != "..") {
                    $aux = explode('.', $file);

                    if ($aux[0] != "") {
                        if (file_exists($destinDir . "/" . $file) &&
                                filetype($destinDir . "/" . $file) != "dir") {
                            unlink($destinDir . "/" . $file);
                        }
                        if (filetype($sourceDir . "/" . $file) == "dir") {
                            if (!file_exists($destinDir . "/" . $file)) {
                                mkdir($destinDir . "/" . $file . "/");
                                self::copyDirectory($sourceDir . "/" . $file, $destinDir . "/" . $file);
                            }
                        } else {
                            copy($sourceDir . "/" . $file, $destinDir . "/" . $file);
                        }
                    }
                }
            }
        }
    }

    /**
     * @todo TRANSLATION
     * Remove diretorio
     * Esta funcao remove recursivamente o diretorio e todo o conteudo existente dentro dele
     *
     * @param $directory (string) Diretorio a ser removido
     * @param $empty (string)
     *
     * @returns (string) value
     */
    public function removeDirectory($directory, $empty = FALSE)
    {
        if (substr($directory, -1) == '/') {
            $directory = substr($directory, 0, -1);
        }

        if (!file_exists($directory) || !is_dir($directory)) {
            return FALSE;
        } elseif (is_readable($directory)) {
            $handle = opendir($directory);

            while (FALSE !== ( $item = readdir($handle) )) {
                if ($item != '.' && $item != '..') {
                    $path = $directory . '/' . $item;

                    if (is_dir($path)) {
                        self::removeDirectory($path);
                    } else {
                        unlink($path);
                    }
                }
            }

            closedir($handle);

            if ($empty == FALSE) {
                if (!rmdir($directory)) {
                    return FALSE;
                }
            }
        }

        return TRUE;
    }

    /**
     * @todo TRANSLATION
     * Retorna o diretÃ³rio temporario
     * Esta funcao retorna o diretÃ³rio temporÃ¡rio do sistema operacional
     *
     * @returns (string) directory name
     */
    static public function getSystemTempDir()
    {
        $tempFile = tempnam(md5(uniqid(rand(), TRUE)), '');
        if ($tempFile) {
            $tempDir = realpath(dirname($tempFile));
            unlink($tempFile);

            return $tempDir;
        } else {
            return '/tmp';
        }
    }

    function getMemUsage()
    {

        if (function_exists('memory_get_usage')) {
            return memory_get_usage();
        } else if (substr(PHP_OS, 0, 3) == 'WIN') {
            // Windows 2000 workaround

            $output = array();
            exec('pslist ' . getmypid(), $output);
            return trim(substr($output[8], 38, 10));
        } else {
            return '<b style="color: red;">no value</b>';
        }
    }

    function unix2dos($arquivo)
    {
        $file = file("$arquivo");
        foreach ($file as $texto) {
            $conteudo.= substr($texto, 0, -1) . "\r\n";
        }
        if (is_writable($arquivo)) {
            $manipular = fopen("$arquivo", "w");
            fwrite($manipular, $conteudo);
            fclose($manipular);
        } else {
            throw new EControlException("O arquivo: \"$arquivo\"  n&atilde;o possui permiss&otilde;es de leitura/escrita.");
        }
    }

    public static function formatValue($value, $precision = 2)
    {
        return number_format($value, $precision, ',', '.');
    }

    public static function invertDate($date)
    {
        $mdate = new \Maestro\Types\MDate($date);
        return $mdate->invert();
    }

    /**
     * Searches the array recursively for a given value and returns the corresponding key if successful.
     *
     * @param (string) $needle
     * @param (array) $haystack
     * @return (mixed) If found, returns the key, othreways FALSE.
     */
    public static function arraySearchRecursive($needle, $haystack)
    {
        $found = FALSE;
        $result = FALSE;

        foreach ($haystack as $k => $v) {
            if (is_array($v)) {
                for ($i = 0; $i < count($v); $i++) {
                    if ($v[$i] === $needle) {
                        $result = $v[0];
                        $found = TRUE;
                        break;
                    }
                }
            } else {
                if ($found = ($v === $needle)) {
                    $result = $k;
                }
            }

            if ($found == TRUE) {
                break;
            }
        }

        return $result;
    }

    /**
     * Função para ordenar um array de array por ordem das colunas passadas em um array
     * @param array $vetor: array de array que desejo ordernar
     *                 exemplo: $vetor = array(array('a', 'b', 'c'), array('d', 'e', 'f'),...)
     * @param array $order: array com ordem das colunas de ordenação
     *                 exemplo: $vetor = array(1,5,17,2,65,0)
     * * @return array
     */
    public static function orderArrayByArray(array $vetor, array $order)
    {
        usort($vetor, function($a, $b) use($order) {
            for ($i = 0; $i < count($order); $i++) {
                $comp = strcasecmp($a[$order[$i]], $b[$order[$i]]);
                if ($comp != 0) {
                    break;
                }
            }
            return $comp;
        });
        return $vetor;
    }

    /**
     * Return an array of (or one, if indicated)  MFile objects from $_FILES
     * $files => $_FILES
     */
    public static function parseFiles($id, $index = NULL)
    {
        $array = array();
        mdump($_FILES);
        if (count($_FILES)) {
            foreach ($_FILES as $var => $file) {
                if (strpos($var, $id) !== false) {
                    if (is_array($file['name'])) {
                        $n = count($file['name']);
                        $f = array();
                        for ($i = 0; $i < $n; $i++) {
                            if ($file['size'][$i] > 0) {
                                $f['name'] = $file['name'][$i];
                                $f['type'] = $file['type'][$i];
                                $f['tmp_name'] = $file['tmp_name'][$i];
                                $f['error'] = $file['error'][$i];
                                $f['size'] = $file['size'][$i];
                                $array[] = new \Maestro\Types\MFile($f);
                            }
                        }
                    } else {
                        if ($file['size'] > 0) {
                            $array[] = new \Maestro\Types\MFile($file);
                        }
                    }
                }
            }
        }
        if (count($array)) {
            return ($index !== NULL ? $array[$index] : $array);
        } else {
            return NULL;
        }
    }

    public static function arrayColumn($array, $key, $insert = NULL)
    {
        if (is_array($key) || !is_array($array)) {
            return $array;
        }
        if (is_null($insert)) {
            $func = create_function('$e', 'return is_array($e) && array_key_exists("' . $key . '",$e) ? $e["' . $key . '"] : null;');
            return array_map($func, $array);
        } else {
            $return = array();
            foreach ($array as $i => $row) {
                $return[$i] = $row ? : array();
                $return[$i][$key] = $insert[$i];
            }
            return $return;
        }
    }

    public static function arrayTree($array, $group, $node)
    {
        $tree = array();
        if ($rs = $array) {
            $node = explode(',', $node);
            $group = explode(',', $group);
            foreach ($rs as $row) {
                $aNode = array();
                foreach ($node as $n) {
                    $aNode[] = $row[$n];
                }
                $s = '';
                foreach ($group as $g) {
                    $s .= "[" . $row[$g] . "]";
                }
                eval("\$tree{$s}" . "[] = \$aNode;");
            }
        }
        return $tree;
    }

    /**
     * Adds a record at the beginning of the array.
     *
     * @param (array) $array
     * @param (mixed) $chave
     * @param (mixed) $valor
     * @return (array) $array
     */
    public static function arrayInsert($array, $chave = null, $valor = null)
    {
        $array = array_reverse($array, true);
        $array[$chave] = $valor;
        return array_reverse($array, true);
    }

    public static function parseArray($value)
    {
        if (!is_array($value)) {
            $value = array($value);
        }
        return $value;
    }

    public static function arrayMergeOverwrite($arr1, $arr2)
    {
        foreach ($arr2 as $key => $value) {
            if (array_key_exists($key, $arr1) && is_array($value)) {
                $arr1[$key] = MUtil::arrayMergeOverwrite($arr1[$key], $arr2[$key]);
            } else {
                $arr1[$key] = $value;
            }
        }
        return $arr1;
    }

    public static function detectUTF8($string)
    {
        return preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
        )+%xs', $string);
    }

    public static function roundBetter($amount, $precision, $direction = 'down')
    {
        $cf = new MCurrencyFormatter();

        $amount = $cf->toDecimal($amount);
        $factor = pow(10, $precision);
        $mult = $amount * $factor;
        $mult = $cf->toDecimal("$mult");

        return ((strtolower($direction) == 'down') ? floor($mult) : ceil($mult)) / $factor;
    }

    public static function roundDown($amount, $precision)
    {
        return self::roundBetter($amount, $precision, 'down');
    }

    public static function roundUp($amount, $precision)
    {
        return self::roundBetter($amount, $precision, 'up');
    }

    public static function upper($value)
    {
        $charset = \Manager::getConf("options.charset");
        return mb_strtoupper($value, $charset);
    }

    /**
     * Função para verificar se todos os atributos de um objeto são nulos
     * @param type $object
     * @return true se o objeto tiver todos atibutos nulos | false se, pelo menos, um atributo do objeto não for nulo
     */
    public static function isObjectNull($object)
    {
        $isNull = true;
        $arrayObject = get_object_vars($object);
        foreach ($arrayObject as $key) {
            if ($key != null) {
                $isNull = false;
                break;
            }
        }
        return $isNull;
    }

}

class MDummy
{
    
}

class MDataObject
{
    
}

?>
