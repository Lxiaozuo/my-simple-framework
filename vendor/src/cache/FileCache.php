<?php

namespace sf\cache;


class FileCache implements CacheInterface
{
    public $cachePath;

    public function buildKey($key)
    {
        // TODO: Implement buildKey() method.
        if (!is_string($key)) {
            $key = json_encode($key);
        }
        return md5($key);
    }

    public function get($key)
    {
        $key = $this->buildKey($key);
        $cacheFile = $this->cachePath . $key;
        // filemtime获取文件的修改时间，如果文件的修改时间小于当前时间，则文件已失效
        if (@filemtime($cacheFile) > time()) {
            return unserialize(@file_get_contents($cacheFile));
        } else {
            return false;
        }
    }

    public function exists($key)
    {
        $key = $this->buildKey($key);
        $cacheFile = $this->cachePath . $key;
        return filemtime($cacheFile) > time();
    }

    public function mget($keys)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key);
        }
        return $result;
    }

    public function set($key, $value, $duration = 0)
    {
        $key = $this->buildKey($key);
        $cacheFile = $this->cachePath . $key;
        $value = serialize($value);
        if (@file_put_contents($cacheFile, $value, LOCK_EX) !== false) {
            if ($duration <= 0) {
                $duration = 24 * 2600 * 365;
            }
            $res = touch($cacheFile, time() + $duration);
            return $res;
        } else {
            return false;
        }

    }

    public function mset($items, $duration = 0)
    {
        $failedKeys = [];
        foreach ($items as $key => $item) {
            if ($this->set($key, $item, $duration) === false) {
                $failedKeys[] = $key;
            }
        }
        return $failedKeys;
    }

    public function add($key, $value, $duration = 0)
    {
        if (!$this->exists($key)) {
            return $this->set($key, $value, $duration);
        } else {
            return false;
        }
    }

    /**
     * Stores multiple items in cache. Each item contains a value identified by a key.
     */
    public function madd($items, $duration = 0)
    {
        $failedKeys = [];
        foreach ($items as $key => $value) {
            if ($this->add($key, $value, $duration) === false) {
                $failedKeys[] = $key;
            }
        }
        return $failedKeys;
    }

    /**
     * Deletes a value with the specified key from cache
     */
    public function delete($key)
    {
        $key = $this->buildKey($key);
        $cacheFile = $this->cachePath . $key;

        return unlink($cacheFile);

    }

    /**
     * Deletes all values from cache.
     * Be careful of performing this operation if the cache is shared among multiple applications.
     * @return boolean whether the flush operation was successful.
     */
    public function flush()
    {
        $dir = @$dir($this->cachePath);
        while ($file = $dir->read() !== false) {
            if ($file !== '.' || $file !== '..') {
                unlink($this->cachePath . $file);
            }
        }
        $dir->close();
    }
}