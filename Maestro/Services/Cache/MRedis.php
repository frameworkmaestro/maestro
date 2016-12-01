<?php
namespace Maestro\Services\Cache;

use Maestro\Manager;
/**
 * Serviço de cache que utiliza o Redis como base.
 */
class MRedis extends MCacheService {

    private static $instance;
    private $redis;
    private $isAvailable;

    private function __construct() {
       $this->init();
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    protected function init() {
        $host = Manager::getConf('cache.Redis.host');
        $port = Manager::getConf('cache.Redis.port');
        $this->redis = new \Redis();
        $this->isAvailable = $this->redis->connect($host, $port);
    }

    public function add($name, $value,  $ttl = -1) {
        $this->set($name, $value, $ttl);
    }

    public function set($name, $value, $ttl = -1) {
        if ($ttl < 0) {
            $ttl = Manager::getConf('cache.Redis.expirationDefault');
        }

        return $this->redis->set($name, serialize($value), $ttl);
    }

    public function increment($name, $by = 1) {
        return $this->redis->incrBy($name, $by);
    }

    public function decrement($name, $by = 1) {
        return $this->redis->decrBy($name, $by);
    }

    public function get($name) {
        return unserialize($this->redis->get($name));
    }

    public function delete($name) {
        return $this->redis->delete($name);
    }

    public function clear() {
        return $this->redis->flushAll();
    }

    public function getAllKeys() {
        return $this->getKeys();
    }

    public function getKeys($pattern = '*') {
        return $this->redis->keys($pattern);
    }

    public function deleteMultiple(array $keys) {
        return $this->redis->delete($keys);
    }

    public function serviceIsAvailable() {
        return $this->isAvailable;
    }
}