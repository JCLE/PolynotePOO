<?php

class MyBreadcrumb
{
    private $breadcrumb;
    private $i;

    function __construct()
    {
        $this->i=0;
    }

    public function breadcrumb()
    {
        return $this->breadcrumb;
    }

    public function add ($name, $page)
    {
        $this->breadcrumb[$this->i]['name'] = $name;
        $this->breadcrumb[$this->i]['page'] = $page;
        $this->i++;
    }
}

?>