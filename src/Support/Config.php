<?php
namespace Halweg\SzPaymentGateway\Support;

use ArrayAccess;
use Halweg\SzPaymentGateway\Exceptions\InvalidArgumentException;

class Config implements ArrayAccess
{
    protected $config;
    
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
    
    public function get($key = null, $default = null)
    {
        $config = $this->config;
        
        if (is_null($key)) {
            return $config;
        }
        
        if (isset($config[$key])) {
            return $config[$key];
        }
        
        foreach (explode('.', $key) as $segment) {
            if (!is_array($config) || !array_key_exists($segment, $config)) {
                return $default;
            }
            $config = $config[$segment];
        }
        
        return $config;
    }
    
    public function set(string $key, $value)
    {
        if ($key == '') {
            throw new InvalidArgumentException('Invalid config key.');
        }
        
        $keys = explode('.', $key);
        switch (count($keys)) {
            case '1':
                $this->config[$key] = $value;
                break;
            case '2':
                $this->config[$keys[0]][$keys[1]] = $value;
                break;
            case '3':
                $this->config[$keys[0]][$keys[1]][$keys[2]] = $value;
                break;
            
            default:
                throw new InvalidArgumentException('Invalid config key.');
        }
        
        return $this->config;
    }
    
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->config);
    }
    
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
    
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }
    
    public function offsetUnset($offset)
    {
        $this->set($offset, null);
    }
}