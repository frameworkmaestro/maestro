<?php

use Zend\Math\Rand;
use Zend\Math\BigInteger\BigInteger;
use Zend\Captcha\Image;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Server;
use Zend\Soap\Client;
use Zend\Session;

Manager::import('apps::exemplos::services::soapService');

class ZendController extends MController {

    public function formVersion() {
        $this->data->versao = "Zend Version = " . Zend\Version\Version::getLatest();
        $this->render();
    }

    public function formRand() {
        $bytes = Rand::getBytes(32, true);
        $this->data->bytes = "Random bytes (in Base64): " . base64_encode($bytes);

        $boolean = Rand::getBoolean();
        $this->data->boolean = "Random boolean: " . ($boolean ? 'true' : 'false');

        $integer = Rand::getInteger(0, 1000);
        $this->data->integer = "Random integer in [0-1000]: " . $integer;

        $float = Rand::getFloat();
        $this->data->float = "Random float in [0-1): " . $float;

        $string = Rand::getString(32, 'abcdefghijklmnopqrstuvwxyz', true);
        $this->data->string = "Random string in latin alphabet:" . $string;

        $this->render();
    }

    public function formBigInteger() {
        $bigInt = BigInteger::factory('bcmath');
        $x = Rand::getString(100, '0123456789');
        $y = Rand::getString(100, '0123456789');
        $sum = $bigInt->add($x, $y);
        $len = strlen($sum);
        $this->data->bigint = "{$x} + {$y} = {$sum}";
        $this->render();
    }

    private function getCaptcha($name) {
        $font = Manager::getPublicPath('', '', 'fonts/ttf/arial.ttf');
        $path = Manager::getFilesPath();
        return new Zend\Captcha\Image(array(
                    'name' => $name,
                    'font' => $font,
                    'imgDir' => $path
                ));
    }

    public function formCaptcha() {
        $captcha = $this->getCaptcha('mycaptcha');
        $id = $captcha->generate();
        $this->data->captcha = Manager::getDownloadURL('files', $id . '.png', true);
        $this->data->captchaId = $id;
        $this->render();
    }

    public function captcha() {
        $captcha = $this->getCaptcha('mycaptcha');
        $test = array('id' => $this->data->captchaId, 'input' => $this->data->mycaptcha);
        if ($captcha->isValid($test, $_POST)) {
            $this->renderPrompt('information', 'Validado!');
        } else {
            $this->renderPrompt('error', 'Não validado!');
        }
    }

    public function formSession() {
        // O gerenciamento de sessão do Maestro utiliza o Zend
        // Usando o container global do Maestro
        $teste = Manager::getSession()->teste + 1;
        Manager::getSession()->teste = $teste;
        $this->data->teste = "Contador (refresh na página para incrementar): " . $teste;

        // Usando containers
        $container = Manager::getSession()->container('exemplo');
        $container->integer = $container->integer + 1;
        $container->string = "String";
        $object = new StdClass();
        $object->name = 'name';
        $object->value = 10;
        $container->object = $object;
        $this->data->integer = "Contador em container (refresh na página para incrementar): " . $container->integer;

        // Usando containers expiration
        $limited = Manager::getSession()->container('limited');
        $limited->setExpirationSeconds(15);
        $limited->contador = $limited->contador + 1;
        $this->data->limited = "Contador com prazo de expiração (15 seg): " . $limited->contador;

        $this->render();
    }

    public function SOAPServer() {
        try {
            if (isset($this->data->wsdl)) {
                $ad = new AutoDiscover();
                $ad->setClass('SOAPService');
                $uri = Manager::getAppURL('exemplos', 'zend/soapServer', true);
                $ad->setURI($uri);
                $wsdl = $ad->toXML();
                $this->renderStream($wsdl);
            } else {
                $uri = Manager::getAppURL('exemplos', 'zend/soapServer?wsdl', true);
                $server = new Server($uri);
                $server->setSoapVersion(SOAP_1_2);
                $server->setClass('SOAPService');
                $server->setReturnResponse(true);
                $response = $server->handle();
                $this->renderBinary($response);
            }
        } catch (Exception $e) {
            mdump($e->getMessage());
            mdump($e->getTraceAsString());
        }
    }

    public function formSOAPClient() {
        ini_set("soap.wsdl_cache_enabled", "0");
        // Acessando o webservice definido no método SOAPServer (acima)
        $client = new Client();
        $client->setSoapVersion(SOAP_1_2);
        try {
            $uri = Manager::getAppURL('exemplos', 'zend/soapServer?wsdl', true);
            $client->setWSDL($uri);
            $this->data->response1 = $client->method1("Teste");
            $this->data->response2 = $client->method2(10, 'Framework Maestro + Zend');
        } catch (Exception $e) {
            mdump($e->getMessage());
            mdump($e->getTraceAsString());
        }
        // Acessando um webservice externo como exemplo
        $clientCountry = new Client();
        $clientCountry->setWSDL('http://www.webservicex.net/country.asmx?WSDL');
        $countries = simplexml_load_string($clientCountry->GetCountries()->GetCountriesResult);
        $this->data->country->name = $countries->Table[29]->Name;
        $Country->CountryCode = "BR";
        $code = simplexml_load_string($clientCountry->GetCountryByCountryCode($Country)->GetCountryByCountryCodeResult);
        $this->data->country->code = 'Code: ' . $code->Table[0]->countrycode;
        $this->data->country->country = 'Country: ' . $code->Table[0]->name;

        $this->render();
    }

}

?>