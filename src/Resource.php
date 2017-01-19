<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill;

/**
 * Abstract Resource class for handling Mandrill responses.
 *
 * @author Unit6 <team@unit6websites.com>
 */
abstract class Resource implements ResourceInterface
{
    protected $id;
    protected $idField;

    protected $data = array();

    public function __construct(array $response = array())
    {
        if ( ! empty($response)) {
            $this->data = $response;

            if (isset($this->idField, $response[$this->idField])) {
                $this->id = $response[$this->idField];
            }
        }
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return (isset($this->data[$name]) ? $this->data[$name] : null);
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    public function __call($name, $arguments)
    {
        $pattern = '/(get|set)([A-Z]{1}[\S]+)/';
        $found = preg_match($pattern, $name, $matches);

        if ($found) {
            list($name, $prefix, $key) = $matches;

            // fix the camelCasing.
            $key = lcfirst($key);

            if ($prefix === 'set') {
                $this->data[$key] = (isset($arguments[0]) ? $arguments[0] : null);
                return;
            } elseif ($prefix === 'get') {
                return (isset($this->data[$key]) ? $this->data[$key] : null);
            }
        }

        throw new Exception\Resource('Undefined method in Resource class.');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}