<?php
namespace Maestro\Services\Cache;

use Maestro\Manager;
/**
 * Classe que utiliza o padrão Null Object para situações onde a cache não está configurada.
 */
class MNullCache extends MCacheService {
    public function add($name, $value, $ttl = 0) {
        return true;
    }

    public function set($name, $value, $ttl = 0) {
        return true;
    }

    public function get($name) {
        return false;
    }

    public function delete($name) {
        return true;
    }

    public function clear() {
        return true;
    }

    public function getKeys($pattern = '*') {
        return [];
    }

    public function getAllKeys() {
        return [];
    }

    public function deleteMultiple(array $keys) {
        return true;
    }

    public function increment($name, $by = 1) {
        return true;
    }

    public function decrement($name, $by = 1) {
        return true;
    }

    public function serviceIsAvailable() {
        return false;
    }
}