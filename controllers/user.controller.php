<?php
// require_once "public/useful/formatting.php";
// require_once "models/user.dao.php";
// require_once "models/note.dao.php";
// require_once "models/category.dao.php";
// require_once "models/image.dao.php";
// require_once "public/useful/MyBreadcrumb.php"; 
// require_once "public/useful/imgManager.php";
// require_once "public/useful/alertManager.php";
// require_once "models/image.dao.php";
// require_once "models/admin.dao.php";
// require_once "config/config.php";

class UserController
{
    /**
     * LOGIN PAGE
     */
    public function getPageLogin()
    {
        $alert = Security::checkAlert();
        // $menu_state = MENU_STATE_INITIAL; // TODO : refactoriser menu
        $title = "Page de connexion";
        $description = "Page permettant l'authentification";

        // Already logged in
        if(Security::checkAccess())
        {
            Security::generateCookiePassword();
            header ("Location: home");
        }

        // Check account
        if(isset($_POST['pseudo']) && !empty($_POST['pseudo']) &&
        isset($_POST['password']) && !empty($_POST['password']))
        {
            $pseudo = Security::secureHTML($_POST['pseudo']);
            $password = Security::secureHTML($_POST['password']);

            if(isConnexionValid($pseudo,$password))
            {
                $_SESSION['user'] = getUser($pseudo,$password);
                Security::generateCookiePassword();

                if(isset($_POST['remember_me']) && $_POST['remember_me'] === 'on')
                    $this->getRememberMe($pseudo,$password);
                else
                    $this->getDeleteRememberMe();

                header ("Location: home");
            } 
            else 
            {
                $alert['msg'] = "Pseudo ou mot de passe invalide";
                $alert['type'] = ALERT_DANGER;
            }
        }
        require_once "views/back/login.view.php";
    }

    /**
     * LOGOUT PAGE
     */
    public function getPageLogout()
    {
        unset($_SESSION['user']);
        header("Location: home");
    }

    /**
     * CREATE COOKIE
     */
    private function getRememberMe($pseudo, $password)
    {
        setcookie(COOKIE_PSEUDO, $pseudo, time() + 365*24*3600, null, null, false, true);
        setcookie(COOKIE_PASSWORD, $password, time() + 365*24*3600, null, null, false, true);
    }

    /**
     * DELETE COOKIE
     */
    private function getDeleteRememberMe()
    {
        setcookie(COOKIE_PSEUDO, NULL, -1);
        setcookie(COOKIE_PASSWORD, NULL, -1);
    }

    /**
     * REGISTER PAGE
     */
    public function getPageRegister()
    {
        $alert = Security::checkAlert();
        // $menu_state = MENU_STATE_INITIAL;
        $title = "Page d'enregistrement";
        $description = "Page permettant de s'enregistrer sur le site";


        if(isset($_POST) && !empty($_POST))
        {
            $email = (isset($_POST['email'])) ? $this->checkAndSecure($_POST['email']) : null;
            $pseudo = (isset($_POST['pseudo'])) ? $this->checkAndSecure($_POST['pseudo']) : null;
            $password = (isset($_POST['password'])) ? $this->checkAndSecure($_POST['password']) : null;
            $password_check = (isset($_POST['password_check'])) ? $this->checkAndSecure($_POST['password_check']) : null;

            $emailExist = ($email != null) ? getIfEmailExist($email) : false;
            $pseudoExist = ($pseudo != null) ? getIfPseudoExist($pseudo) : false;

            if($email != null && $emailExist)
            {
                $validate_email['valid'] = false;
                $validate_email['text'] = "Cet email existe déja dans la base de données";
            }
            else
            {
                $validate_email = $this->checkInput($email);
            }
    
            if($pseudo != null && $pseudoExist)
            {
                $validate_pseudo['valid'] = false;
                $validate_pseudo['text'] = "Ce pseudo existe déja dans la base de données";
            }
            else
            {
                $validate_pseudo = $this->checkInput($pseudo);
            }
    
            if ($password != $password_check)
            {
                $validate_password['valid'] = false;
                $validate_password_check['valid'] = false;
                $validate_password_check['text'] = "Les mots de passe ne correspondent pas";
            }
            else
            {
                $validate_password = $this->checkInput($password);
                $validate_password_check = $this->checkInput($password_check);
            }

            if($validate_email['valid'] && $validate_pseudo['valid'] && $validate_password['valid'] && $validate_password_check['valid'])
            {
                try
                {
                    $password = Security::encryptPassword($password);
                    insertMember($email, $pseudo, $password);
                    $_SESSION['alert']['msg'] = "L'enregistrement de ".$pseudo." a été effectué";
                    $_SESSION['alert']['type'] = ALERT_SUCCESS;

                    header ("Location: login"); // header ne transmets pas les variables -> A Réglé
                }catch(Exception $e)
                {
                    $alert['msg'] = "L'enregistrement n'a pas marché. ". $e->getMessage();
                    $alert['type'] = ALERT_DANGER;
                }
            }

        } 
        require_once "views/back/register.view.php";
    }

    private function checkAndSecure($value)
    {
        if( isset($value) && !empty($value) )
        {
            return Security::secureHTML($value);
        }
        else
        {
            return null;
        }
    }

    /**
     * Check input to valid or invalid feedback
     */
    private function checkInput($value)
    {
        if($value === null)
        {
            $validate_input['valid'] = false;
            $validate_input['text'] = "Ce champ ne peut être laissé vide";
        }
        else
        {
            $validate_input['valid'] = true;
        }
        return $validate_input;
    }
}



