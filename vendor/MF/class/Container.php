<?php

namespace MF\class;

class Container{

    public static function getClass($class){
        $class = 'app\\classes\\'.ucfirst($class);
        return new $class();
    }

}