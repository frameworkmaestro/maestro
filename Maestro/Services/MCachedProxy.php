<?php

/**
 * Essa classe visa a implementação de cache de forma transparente.
 * User: Marcello
 * Date: 08/09/2016
 * Time: 10:23
 */
namespace Maestro\Services;
use Maestro\Services\Cache\MCacheService;

class MCachedProxy
{
    private $object;
    private $cache;
    private $expiration;

    private function __construct($object)
    {
        $this->object = $object;
        $this->cache = MCacheService::getCacheService();
        $this->expiration = 60 * 60 * 24;
    }

    public static function proxify($object)
    {
        return new self($object);
    }

    public function setExpiration($seconds)
    {
        $this->expiration = $seconds;
        return $this;
    }

    public function __call($name, $parameters)
    {
        $key = $this->buildKey($name, $parameters);
        $result = $this->cache->get($key);

        if (!$result) { //cache miss
            $result = call_user_func_array([$this->object, $name], $parameters);
            $this->cache->set($key, $result, $this->expiration);
        }

        return $result;
    }

    private function buildKey($name, $arguments)
    {
        $join = get_class($this->object) . $name . serialize($arguments);
        return 'maestro:' . md5($join);
    }

}