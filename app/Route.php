<?php

namespace app;

use MF\init\Bootstrap;

class Route extends Bootstrap{

    protected function initRoutes(){

        $routes['home'] = array(
            'route' => '/',
            'controller' => 'indexController',
            'action' =>  'index'
        );

        $routes['report'] = array(
            'route' => '/report',
            'controller' => 'indexController',
            'action' =>  'report'
        );

        $this->setRoutes($routes);
    }



}
