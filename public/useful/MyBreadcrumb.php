<?php

class MyBreadcrumb
{
    private $_breadcrumb;
    private $î;

    function __construct()
    {
        $this->_i=0;
    }

    public function breadcrumb()
    {
        return $this->_breadcrumb;
    }

    public function add ($name, $page)
    {
        $this->_breadcrumb[$this->_i]['name'] = $name;
        $this->_breadcrumb[$this->_i]['page'] = $page;
        $this->_i++;
    }
}

?>