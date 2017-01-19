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
 * Model class for handling Mandrill requests.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Model
{
    #protected $id;
    protected $data = array();

    public function __construct()
    {
        //
    }

    public function __call($name, $arguments)
    {
        $pattern = '/(get|set)([A-Z]{1}[\S]+)/';
        $found = preg_match($pattern, $name, $matches);

        if ($found) {
            list($name, $prefix, $key) = $matches;

            // convert camelCaseName to underscore_name.
            $this->camelCaseToUnderscore($key);

            $parameters = $this->getParameters();

            if (isset($parameters[$key])) {
                if ($prefix === 'set') {
                    $this->data[$key] = (isset($arguments[0]) ? $arguments[0] : null);
                    return;
                } elseif ($prefix === 'get') {
                    return (isset($this->data[$key]) ? $this->data[$key] : null);
                }
            }
        }

        throw new Exception\ModelMethodUndefined($name, $arguments);
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    public function __get($name)
    {
        if ( ! isset($this->data[$name])) {
            throw new Exception\ModelPropertyUndefined($name);
        }

        return $this->data[$name];
    }

    /*
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
    */

    public function getData()
    {
        return $this->data;
    }

    public function appendToList(/* index, [key|value], value */)
    {
        $num = func_num_args();

        if ($num >= 2) {
            $args = func_get_args();

            $i = $args[0];

            if ( ! isset($this->data[$i])) {
                $this->data[$i] = array();
            }

            if ($num === 2) {
                $this->data[$i][] = $args[1];
            } elseif ($num === 3) {
                $this->data[$i][] = $args[2];
            }
        }
    }

    public function camelCaseToUnderscore(&$str)
    {
        // fix the camelCasing.
        $str = lcfirst($str);

        // convert camelCaseName to underscore_name.
        $str = preg_replace('/([a-z])([A-Z])/', '$1_$2', $str);

        // convert entirely to lowercase underscore str.
        $str = strtolower( $str );

        return $str;
    }

    public function getParameters()
    {
        $parts = explode('\\', get_called_class());
        $name  = lcfirst(end($parts));

        return Parameters::${$name};
    }

    public function assignData($source)
    {
        if ( ! empty($source)) {
            $parameters = $this->getParameters();

            foreach ($source as $key => $value) {
                if (isset($parameters[$key])) {
                    $type = $parameters[$key];

                    // coerce valid types.
                    if ( ! is_null($value) &&
                        gettype($value) !== $type &&
                        in_array( $type, Parameters::$typeOptions ) )
                    {
                        settype( $value, $type );
                    }

                    $this->data[$key] = $value;
                }
            }
        }
    }
}