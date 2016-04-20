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
namespace Maestro\Security;

use Maestro\Manager;


/**
 * Classe para agrupar serviços de encriptação e decriptação utilizando SSL.
 *
 * @author Marcello
 */
class MSSL {
    
    /**
     * Gera um par de chaves Publica/Privada.
     * 
     * @param int $size Tamanho em bits da chave
     * @return array Chaves pública e privada
     */
    public static function generateKeyPair($size = 4096) {
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => $size,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        
        $privKey = null;        
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privKey);
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];
        
        return array('public' => $pubKey, 'private' => $privKey);
    }
    
    /**
     * Criptografia assimétrica: usa uma chave privada para descriptografar
     * o conteúdo criptografado com uma chave pública.
     * 
     * @param string $data Conteúdo criptografado
     * @param string $privKey Chave privada
     * @param bool $base64Decode Informa de $data deve ser convertido de base64
     * @return Valor descriptografado ou null em caso de erro.
     */
    public static function decryptPrivate($data, $privKey, $base64Decode = true) {
        $decoded = $base64Decode ? base64_decode($data) : $data;
        $decrypted = null;
        openssl_private_decrypt ($decoded, $decrypted, $privKey);
        
        return $decrypted;
    }
}

?>
