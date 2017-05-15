<?php

namespace DW\DWBundle\Helper;

use JMS\Serializer\Serializer;
use Tbbc\CacheBundle\Cache\CacheManagerInterface;
use Tbbc\CacheBundle\Cache\KeyGenerator\KeyGeneratorInterface;

class CacheHelper
{
    private $cacheManager;
    private $keyGenerator;
    private $serializer;

    public function __construct(CacheManagerInterface $cacheManager,
                                KeyGeneratorInterface $keyGenerator,
                                Serializer $serializer)
    {
        $this->cacheManager = $cacheManager;
        $this->keyGenerator = $keyGenerator;
        $this->serializer = $serializer;
    }

    public function getFromCache($key, $name, $classType)
    {
        $cacheKey = $this->keyGenerator->generateKey($key);
        $cache = $this->cacheManager->getCache($name);
        $serialized = $cache->get($cacheKey);
        $deserialized = null;
        if ($serialized != null) {
            $deserialized = $this->deserialize($serialized, $classType);
        }
        return $deserialized;
    }

    public function saveToCache($key, $name, $value)
    {
        $cacheKey = $this->keyGenerator->generateKey($key);
        $cache = $this->cacheManager->getCache($name);
        $serialized = $this->serialize($value);
        $cache->set($cacheKey, $serialized);
    }

    public function deleteFromCache($key, $name)
    {
        $cacheKey = $this->keyGenerator->generateKey($key);
        $cache = $this->cacheManager->getCache($name);
        $cache->delete($cacheKey);
    }

    public function serialize($object)
    {
        return $this->serializer->serialize($object, 'json');
    }

    public function deserialize($data, $class)
    {
        return $this->serializer->deserialize($data, $class, 'json');
    }
}