<?php

class NoteController
{    
    /**
     * getPageNote
     *
     * @return void
     */
    function getPageNote()
    {
        $alert = Security::checkAlert();
        $title = "Note";
        $description = "Page permettant de voir une note";

        if(Security::checkAccess())
        {
            if(isset($_GET['id']) && !empty($_GET['id']))
            {
                $id_note = Security::secureHTML($_GET['id']);
                try
                {
                    $note = getNoteFromID($id_note,$_SESSION['user']['id']);

                    $matches = findImgID($note['content']);
                    $nbMatches = count($matches);                
                    if($nbMatches  > 0)
                    {
                        for($i=0; $i<$nbMatches; $i++ )
                        {
                            $image[$i] = getImageFromID($matches[$i][1],$_SESSION['user']['id']);
                            $pattern[$i] = '[library]'.$image[$i]['id'].'[/library]';
                            $replace[$i] = createImgTag($image[$i]);
                        }
                        $note['content'] = str_replace($pattern, $replace, $note['content']);
                    }

                    $title = "Note - ".$note['name_category'];
                    $MyBreadcrumb = new MyBreadcrumb();
                    $MyBreadcrumb->add('Notes', 'categories');
                    $MyBreadcrumb->add($note['name_category'], 'category&id='.$note['id_category']);
                    $MyBreadcrumb->add('Note', '#');
                    $breadcrumb = $MyBreadcrumb->breadcrumb();
                    
                    require_once "views/back/notes/note.view.php";
                }
                catch(Exception $e)
                {
                    throw new Exception("aucune note correspondante");
                }
            }
            else
            {
                throw new Exception("Identifiant de note inexistant");
            }

        }
    }
    
    /**
     * getPageAddNote
     *
     * @return void
     */
    function getPageAddNote()
    {
        $alert = Security::checkAlert();
        $title = "Ajout d'une note";
        $description = "Page permettant l'ajout d'une note";
        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', 'categories');

        if(Security::checkAccess())
        {
            $images = getUnusedImages($_SESSION['user']['id']);
        
            if(isset($_GET['id']) && !empty($_GET['id']))
            {
                $id_category = Security::secureHTML($_GET['id']);
            }
            if(isset($_POST['id']) && !empty($_POST['id']))
            {
                $id_category = Security::secureHTML($_POST['id']);
            }

            $category = getCategoryFromID($id_category, $_SESSION['user']['id']);
            if($category === false)
            {
                throw new Exception("Acces interdit - Identifiant incorrect");
            }
            $MyBreadcrumb->add($category['name'], 'category&id='.$id_category);


            if( isset($_POST['title']) && !empty($_POST['title']) 
                && isset($_POST['content']) && !empty($_POST['content'])
                )
            {
                $title = Security::secureHTML($_POST['title']);
                $content = Security::secureHTML($_POST['content']);
                if(isset($_POST['tags']) && !empty($_POST['tags']) )
                    $tags = Security::secureHTML($_POST['tags']);
                else
                    $tags="";
                try
                {
                    $id_note = insertNoteFromCategory($title, $content, $tags, $id_category, $_SESSION['user']['id']);
                    $note = getNoteFromID($id_note,  $_SESSION['user']['id']);

                    // Start insert note uses image ***********
                    $matches = findImgID($note['content']);
                    $nbMatches = count($matches);                
                    if($nbMatches  > 0)
                    {
                        for($i=0; $i<$nbMatches; $i++ )
                        {
                            if(getImageFromID($matches[$i][1],$_SESSION['user']['id']))
                            {
                                insertNoteUsesImg($note['id'], $matches[$i][1]);
                            }
                        }
                    }
                    // End uses ********************************

                    $alert['msg'] = "La note a été ajoutée";
                    $alert['type'] = ALERT_SUCCESS;
                    Alert::setAlert($alert);
                    header ('Location: note&id='.$id_note);
                    return;
                }
                catch(Exception $e)
                {
                    throw new Exception("Enregistrement de la note impossible");
                }
            }
            else
            {
                if(isset($_POST['title']) && empty($_POST['title']))
                {
                    $alert['msg']  = "Le titre ne peut être laissé vide";
                    $alert['type']  = ALERT_DANGER;
                }
                elseif(isset($_POST['content']) && empty($_POST['content']))
                {
                    $alert['msg']  = "Le contenu ne peut être laissé vide";
                    $alert['type']  = ALERT_DANGER;
                }
                Alert::setAlert($alert);
            }
            
            $MyBreadcrumb->add('Ajout Note', '#');
            $breadcrumb = $MyBreadcrumb->breadcrumb();
            require_once "views/back/notes/addNote.view.php";
        }
        else 
        {
            throw new Exception("Accès interdit si vous n'êtes pas authentifié");
        }
    }
    
    /**
     * getPageEditNote
     *
     * @return void
     */
    function getPageEditNote()
    {
        $alert = Security::checkAlert();
        $title = "Edition d'une note";
        $description = "Page permettant l'édition d'une note";
        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', 'categories');

        if(Security::checkAccess())
        {
            if(isset($_POST['id_note']) && !empty($_POST['id_note'])
            && isset($_POST['title']) && !empty($_POST['title'])
            && isset($_POST['content']) && !empty($_POST['content'])
            && isset($_POST['tags'])
            && isset($_POST['id_category']) && !empty($_POST['id_category']))
            {
                try 
                {
                    $id_note = Security::secureHTML($_POST['id_note']);
                    $id_category = Security::secureHTML($_POST['id_category']);
                    $title = Security::secureHTML($_POST['title']);
                    $content = Security::secureHTML($_POST['content']);
                    $tags = Security::secureHTML($_POST['tags']);
        
                    $note_updated = updateNoteFromUser($id_note, $title, $content, 
                        $tags, $id_category, $_SESSION['user']['id']);

                    // Start update note uses image ***********
                    $note = getNoteFromID($id_note, $_SESSION['user']['id']);

                    $matches = findImgID($note['content']);
                    // delete all uses and insert afterward
                    deleteAllNoteUsesImg($id_note);
                    $nbMatches = count($matches);                
                    if($nbMatches  > 0)
                    {
                        for($i=0; $i<$nbMatches; $i++ )
                        {
                            if(getImageFromID($matches[$i][1],$_SESSION['user']['id']))
                            {
                                insertNoteUsesImg($note['id'], $matches[$i][1]);
                            }
                        }
                    }
                    // End uses ********************************
                    
                    header ('Location: note&id='.$id_note);
                    return;
                } 
                catch (Exception $e) 
                {
                    throw new Exception("Erreur lors de l'insertion en base de donnée");
                }
            }
            else
            {
                if(isset($_POST['title']) && empty($_POST['title']))
                {
                    $alert['msg'] = "Le titre ne peut rester vide";
                    $alert['type'] = ALERT_DANGER;
                }
                elseif(isset($_POST['content']) && empty($_POST['content']))
                {
                    $alert['msg'] = "Le contenu ne peut être laissé vide";
                    $alert['type'] = ALERT_DANGER;
                }
                Alert::setAlert($alert);
                try
                {
                    if(isset($_GET['id']) && !empty($_GET['id']))
                        $id_note = Security::secureHTML($_GET['id']);
                    elseif(isset($_POST['id_note']) && !empty($_POST['id_note']))
                        $id_note = Security::secureHTML($_POST['id_note']);
                    else
                        throw new Exception("Identifiant de note inexistant");

                    $note = getNoteFromID($id_note, $_SESSION['user']['id']);
                    // Merge img from this note and unused img
                    $images = array_merge(getImagesFromNote($id_note, $_SESSION['user']['id']),
                                            getUnusedImages($_SESSION['user']['id']));

                    $matches = findImgID($note['content']);
                    // delete all uses and insert afterward ****
                    deleteAllNoteUsesImg($id_note);
                    $nbMatches = count($matches);                
                    if($nbMatches  > 0)
                    {
                        for($i=0; $i<$nbMatches; $i++ )
                        {
                            if(getImageFromID($matches[$i][1],$_SESSION['user']['id']))
                            {
                                insertNoteUsesImg($note['id'], $matches[$i][1]);
                            }
                        }
                    }
                    // End uses ********************************

                    $MyBreadcrumb->add($note['name_category'], 'category&id='.$note['id_category']);
                    $MyBreadcrumb->add('Edition Note', '#');
                    $breadcrumb = $MyBreadcrumb->breadcrumb();
                    require_once "views/back/notes/editNote.view.php";
                }
                catch(Exception $e)
                {
                    throw new Exception("aucune note correspondante");
                }
            }
        }
        else 
        {
            throw new Exception("Accès interdit si vous n'êtes pas authentifié");
        }
    }
    
    /**
     * getPageDeleteNote
     *
     * @return void
     */
    function getPageDeleteNote()
    {
        $title = "Supprimer note";
        $description = "Page de suppression de note";

        if(Security::checkAccess())
        {
            if(isset($_GET['del']))
            {
                $id_note = Security::secureHTML($_GET['del']);
                
                try
                {
                    deleteAllNoteUsesImg($id_note);
                    if(deleteNote($id_note,$_SESSION['user']['id'])<1)
                    {
                        throw new Exception ("la suppression de la note n'a pas fonctionné");
                    }
                    $alert['msg'] = "La note à été suprimée";
                    $alert['type'] = ALERT_WARNING;
                } catch(Exception $e){
                    $alert['msg'] = "La suppression de la catégorie n'a pas fonctionnée";
                    $alert['type'] = ALERT_DANGER;
                }
                Alert::setAlert($alert);
            }
            header ('Location: categories');
        }
        else 
        {
            throw new Exception("Accès interdit si vous n'êtes pas authentifié");
        }
    }
    
    /**
     * getPageSearch
     *
     * @return void
     */
    function getPageSearch()
    {
        $alert = Security::checkAlert();
        $title = "Recherche";
        $description = "Page de recherche";

        if(Security::checkAccess())
        {
            if(isset($_POST['search']))
            {
                $search = Security::secureHTML($_POST['search']);
                if(strlen($search) <= 0)
                {
                    $alert['msg'] = 'La recherche ne peut être laissée vide';
                    $alert['type'] = ALERT_DANGER;
                    Alert::setAlert($alert);
                    header ('Location: home');
                    return;
                } 
            }
            elseif(isset($_GET['s']))
            {
                $search = Security::secureHTML($_GET['s']);
            }
            else
            {
                throw new Exception("Erreur d'acces !");
            }
            try 
            {
                if(isset($_POST['id_note']) && is_numeric($_POST['id_note']))
                {
                    header ('Location: note&id='.$_POST['id_note']);
                    return;
                }
                else
                {
                    $pageNum = (!empty($_GET['pageNum']) ? Security::secureHTML($_GET['pageNum']) : 1);
                    // cut string into array and delete empty slots with array_filter
                    $result = array_filter(explode(" ", $search));                    
                    $reqArray = array();

                    foreach($result as $key => $value)
                    {
                        $wordSearch = getSearch($value, $_SESSION['user']['id']);
                        $reqArray = array_merge($wordSearch, $reqArray);
                    }
                    
                    $newArr = array(); // new array without duplication id
                    $arTemp = array(); // contains id to avoid
                    // Eliminate data duplication
                    foreach($reqArray as $ar)
                    {
                        if(!in_array($ar['id'], $arTemp)) 
                        {
                            $newArr[] = $ar;
                            $arTemp[] = $ar['id'];
                        }
                    }
                    
                    $ResultNumber = count($newArr);
                    $notes = array_slice($newArr,$pageNum-1,LIMIT_NOTES_BY_PAGE);

                    $MyBreadcrumb = new MyBreadcrumb();
                    $MyBreadcrumb->add('Notes', 'categories');
                    $MyBreadcrumb->add('Resultats pour la recherche : <strong>'.$search.'</strong>', '#');
                    $breadcrumb = $MyBreadcrumb->breadcrumb();
                    require_once "views/back/notes/search.view.php";
                }
            } 
            catch (Exception $e) 
            {
                throw new Exception("Impossible de récupérer la page existante");
            }        
        }
        else
        {
            echo "Tu recherches <strong> $search </strong> et tu n'a rien trouvé ?<br />C'est normal :)";
        }
    }
}