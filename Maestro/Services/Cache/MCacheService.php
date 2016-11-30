<?php

namespace Maestro\Services\Cache;

use Maestro\Manager;
/**
 * Classe genérica que define a interface dos serviços de cache.
 */
abstract class MCacheService {

    /**
     * Factory Method para criação da instância correta do serviço de cache.
     *
     * @return MCacheService
     * @throws Exception
     */
    public static function getCacheService() {
        $serviceClass =  Manager::getConf('cache.type');

        if (!class_exists($serviceClass)) {
            mdump("A definição do serviço de cache [$serviceClass] não foi encontrada. Carregando a implementação padrão.", 'error');
            return new MNullCache();
        }

        if (method_exists($serviceClass, 'getInstance')) {
            return $serviceClass::getInstance();
        } else {
            return new $serviceClass;
        }
    }

    public abstract function add($name, $value, $ttl = -1);
    public abstract function set($name, $value, $ttl = -1);
    public abstract function get($name);
    public abstract function increment($name, $by = 1);
    public abstract function decrement($name, $by = 1);
    public abstract function delete($name);
    public abstract function deleteMultiple(array $keys);
    public abstract function clear();
    public abstract function getKeys($pattern = '*');
    public abstract function getAllKeys();
    public abstract function serviceIsAvailable();
}