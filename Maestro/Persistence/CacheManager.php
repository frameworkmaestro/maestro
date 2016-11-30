<?php

/**
 * Classe para gerência de objetos em cache
 * User: Marcello
 * Date: 02/09/2016
 * Time: 16:03
 */
namespace Maestro\Persistence;

use Maestro\Services\Cache\MCacheService;

class CacheManager {

    private static $instance;

    const DEFAULT_TTL = 300;


    private $cache;

    private function __construct(MCacheService $cache) {
        $this->cache = $cache;
    }

    public static final function getInstance() {
        if (!self::$instance) {
            self::$instance = new CacheManager(MCacheService::getCacheService());
        }

        return self::$instance;
    }

    public function saveToCache(\MBusinessModel $model) {
        $key = $this->buildCacheKey($model);
        $data = $model->getData();
        $this->cache->set($key, $data, $this->getTTL($model));
    }

    private function getTTL(\MBusinessModel $model) {
        $config = $model->config();
        return isset($config['cache']['ttl']) ? $config['cache']['ttl'] : self::DEFAULT_TTL;
    }

    /**
     * Preenche o objeto do modelo com os dados da cache.
     *
     * @param MBusinessModel $model
     * @return bool
     */
    public function loadFromCache(\MBusinessModel $model) {
        $data = $this->getDataFromCache($model);
        if(!$data) {
            return false;
        }

        $this->setDataAsString($data, $model);
        $model->setPersistent(true);
        return true;
    }

    /**
     * Seta todos os valores no model como strings.
     *
     * Essa função é necessária porque na primeira instanciação, os objetos MBusinessModel tem todas as suas propriedades
     * do tipo string. Na chamada do getData, esses métodos são convertidos para seu tipo correto.
     * Como quero entregar uma cópia exatamente igual ao que resultaria da chamada de um retrieve() estou fazendo esse
     * trabalho extra aqui.
     *
     * @param $data
     */
    private function setDataAsString($data, $model) {
        $properties = get_object_vars($data);
        foreach ($properties as $property => $value) {
            $method = 'set' . $property;
            if (method_exists($model, $method)) {
                $model->$method("$value");
            }
        }
    }

    private function getDataFromCache(\MBusinessModel $model) {
        $key = $this->buildCacheKey($model);
        return $this->cache->get($key);
    }

    public function delete(\MBusinessModel $model) {
        $key = $this->buildCacheKey($model);
        $this->cache->delete($key);
    }

    public function clear() {
        $keys = $this->cache->getKeys("siga:*");
        $this->cache->deleteMultiple($keys);
    }

    public function cacheIsEnabled() {
        return $this->cache->serviceIsAvailable();
    }

    private function buildCacheKey(\MBusinessModel $model) {
        $class = get_class($model);
        $classSimple = end(explode('\\', $class));

        return 'siga:' . "$classSimple:" . md5($class . ":" . $model->getId());
    }
}