<?php

namespace Application\Model;

abstract class HcObj {
    public function __construct(array $form_values = null,array $context = null) {
        if(is_array($context) && is_array($form_values)){
            //cumul des deux
            $options = array_merge($form_values, $context);
        } else {
            //seulement le premier
            $options = $form_values;
        }

        if(is_array($options)){
            $this->setOptions($options);
        }
    }

    public function __set($name,$value) {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new \RuntimeException('Invalid property = '.$name);
        }
        $this->$method($value);
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new \RuntimeException('Invalid property = '.$name);
        }
        return $this->$method();
    }

    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if(in_array($method, $methods)) {
                $this->$method($value);
            }
        }
    }
}