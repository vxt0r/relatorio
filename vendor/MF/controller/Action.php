<?php

namespace MF\controller;

class Action{

    protected $view;

    public function __construct(){
        $this->view = new \stdClass();
    }

    protected function render($view,$layout){
        $this->view->page = $view;
        
        if(file_exists('../app/views/'.$layout.'.phtml')){
            require_once '../app/views/'.$layout.'.phtml';
        }
        else{
            $this->content();
        }
    }

    protected function content(){
        $actualClass = get_class($this);
        $actualClass = str_replace('app\\controllers\\','',$actualClass);
        $actualClass = strtolower(str_replace('Controller','',$actualClass));
        require_once '../app/views/'.$actualClass.'/'.$this->view->page.'.phtml';
    }
}