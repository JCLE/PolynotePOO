<?php

class Alert 
{
    public static function setAlert($message)
    {
        if(isset($message) && !empty ($message))
        {
            $_SESSION['alert'] = $message;
        }
    }

    public static function setAlertType($type)
    {
        if(isset($type) && !empty ($mestypesage))
        {
            $_SESSION['alert_type'] = $type;
        }
    }

    public static function getAlert()
    {
        return $_SESSION['alert'];
    }

    public static function getAlertType()
    {
        return $_SESSION['alert_type'];
    }
    
}