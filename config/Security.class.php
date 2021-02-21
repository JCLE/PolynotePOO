<?php

class Security 
{
    public static function secureHTML($string){
        return htmlspecialchars($string);
        // return htmlentities($string);
    }

    public static function encryptPassword($password){
        return password_hash(htmlspecialchars($password), PASSWORD_DEFAULT);
    }

    /**
     * Generate cookie to secure user session
     */
    public static function generateCookiePassword(){
        $ticket = session_id().microtime().rand(0,9999999);
        $ticket = hash("sha512", $ticket);
        setcookie(COOKIE_PROTECT, $ticket, time() + (60 * 20));
        $_SESSION[COOKIE_PROTECT] = $ticket;
    }

    /**
     * Check cookie for protected session
     */
    public static function checkCookie()
    {
        if(isset($_SESSION[COOKIE_PROTECT]) && isset($_COOKIE[COOKIE_PROTECT]) && $_COOKIE[COOKIE_PROTECT] === $_SESSION[COOKIE_PROTECT])
        {
            self::generateCookiePassword();
            return true;
        } else {
            session_destroy();
            throw new Exception("Vous n'avez pas le droit d'être là !");
        }
    }

    /**
     * Check if User session exist
    */
    public static function checkAccessSession(){
        return (isset($_SESSION['user']) && !empty($_SESSION['user']));
    }

    /**
     * Check Cookie and User session
     */
    public static function checkAccess(){
        return (self::checkAccessSession() && self::checkCookie());
    }

    /**
     * CHECK AND AFFECT ALERT IF EXIST
     */
    public static function checkAlert()
    {
        if(isset($_SESSION['alert']) && !empty($_SESSION['alert']))
        {
            $alert['msg'] = $_SESSION['alert']['msg'];
            $alert['type'] = $_SESSION['alert']['type'];
            unset($_SESSION['alert']);
            return $alert;
        }
    }
    
}