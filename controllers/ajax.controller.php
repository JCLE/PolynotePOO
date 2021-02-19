<?php
session_start();
require_once 'config/config.php';
require_once 'config/Security.class.php';
require_once 'models/ajax.dao.php';
require_once 'models/image.dao.php';
require_once "public/useful/formatting.php";
require_once "public/useful/imgManager.php";

/**
 * Call Ajax Request
 *
 * @param  string $_search
 *
 * @return array array of search result
 */
function getCallAjax($_search)
{
    if(Security::checkAccess())
    {
        Security::generateCookiePassword();
        $search = Security::secureHTML($_search);

        // cut string into array and delete empty slots with array_filter
        $result = array_filter(explode(" ", $search));

        // return($result); // test if return blank spaces

        $reqArray = array();
        foreach($result as $key => $value)
        {
            $wordSearch = getSearch($value, $_SESSION['user']['id']);
            return $wordSearch;
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

        $min_return = 0;
        return array_slice($newArr,$min_return,LIMIT_AJAX_RETURN);
    }
}


function getInsertImageAjax($file)
{
    if(Security::checkAccess())
    {
        Security::generateCookiePassword();

        try
        {
            $filename =  explode('.', $file['name']); // 0-name 1-extension
            // var_dump($filename);
            $name = cleanString($filename[0]);
            $tempName = $name;
            $i = 1;
            // if image name exists, change his name as long as needed
            while(getIfImageExist($tempName,$_SESSION['user']['id']) > 0)
            {
                $tempName = $name.$i;
                $i++;
            }
            $fileImage = $file;

            $dir = "public/sources/images/images";
            // Create directory images if not exist
            if(!file_exists($dir)) mkdir($dir,0777);
            $directory = $dir."/user".$_SESSION['user']['id']."/";

            // var_dump($directory);
            $tmp_name_img = cleanString($tempName);
            $imgName = addImg($fileImage, $directory,$tmp_name_img);

             $description = "Image representant ".$name;
             $id_image =  insertImageIntoBD($tempName, $imgName, $description, $_SESSION['user']['id']);
             $image = getImageFromID($id_image, $_SESSION['user']['id']);

             if($image)
             {
                $data['id_image'] = $image['id'];
                $data['url'] = $image['url'];
                $data['desc'] = $image['description'];
                $data['id_user'] = $image['id_user'];
                return $data;
            }
        }
        catch(Exception $e)
        {
            return "Erreur lors de l'insertion de l'image";
        }
    }
}



// function getInsertImageAjax($file)
// {
//     if(Security::checkAccess())
//     {
//         Security::generateCookiePassword();

//         try
//         {
//             $filename =  explode('.', $file['name']); // 0-name 1-extension
//             var_dump($filename);
//             $name = cleanString($filename[0]);
//             $tempName = $name;
//             $i = 1;
//             // if image name exists, change his name as long as needed
//             while(getIfImageExist($tempName,$_SESSION['user']['id']) > 0)
//             {
//                 $tempName = $name.$i;
//                 $i++;
//             }
//             $fileImage = $file;
//             $directory = "public/sources/images/images/user".$_SESSION['user']['id']."/";
//             // var_dump($directory);
//             $tmp_name_img = cleanString($tempName);
//             $imgName = addImg($fileImage, $directory,$tmp_name_img);

//             $description = "Image representant ".$name;
//             $id_image =  insertImageIntoBD($tempName, $imgName, $description, $_SESSION['user']['id']);
//             // $image = getImageFromID($id_image, $_SESSION['user']['id']);

//             // $data['id_image'] = $id_image;
//             // $data['url'] = $image['url'];
//             // $data['desc'] = $image['desc'];
//             // $data['id_user'] = $image['id_user'];

//             $data['id_image'] = '111';
//             $data['url'] = 'url';
//             $data['desc'] = 'description';
//             $data['id_user'] = '1';

//             return $data;
//         }
//         catch(Exception $e)
//         {
//             return "Erreur lors de l'insertion de l'image";
//         }
//     }
// }
