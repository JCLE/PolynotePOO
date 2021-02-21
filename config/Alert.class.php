<?php

class Alert 
{
    public static function setAlert($alert)
    {
        if(isset($alert['msg']) && !empty ($alert['msg']))
        {
            $_SESSION['alert']['msg'] = $alert['msg'];
        }
        if(isset($alert['type']) && !empty ($alert['type']))
        {
            $_SESSION['alert']['type'] = $alert['type'];
        }
    }

    public static function getAlert()
    {
        return $_SESSION['alert'];
    }    
}