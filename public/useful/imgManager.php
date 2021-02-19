<?php

/**
 * UPLOAD FILE
 * @param array $file $_FILES array
 * @param string $dir Location where adding img - ex : 'myDirectory/MyFolder/'
 * @param string $name New image name - ex : Symfony
 * 
 * @return string 
 *  Image name ex : 'mon_image.jpg'
 * 
 */
function addImg($file, $dir, $name)
{
    if(!isset($file['name']) || empty($file['name']))
        throw new Exception("Vous devez indiquer une image");

    if(!file_exists($dir)) mkdir($dir,0777);

    $extension = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
    $target_file = $dir. $name .".".$extension;
    $i = 1;
    
    if(!getimagesize($file["tmp_name"]))
        throw new Exception("Le fichier n'est pas une image");
    if($extension !== "jpg" && $extension !== "jpeg" && $extension !== "png" && $extension !== "gif")
        throw new Exception("L'extension du fichier n'est pas reconnu");
    // IF FILE EXIST => ADD NUMBER
    while(file_exists($target_file))
    {
        $i++;
        $target_file = $dir. $name .$i.".".$extension;
    }
    // IF NOT RENAME
    if($i === 1)
    {
        $i = null;
    }

    // TODO : A remettre ou pas ?
    // if($file['size'] > 500000)
    //     throw new Exception("Le fichier est trop gros");

    if(!move_uploaded_file($file['tmp_name'], $target_file))
        throw new Exception("l'ajout de l'image n'a pas fonctionné");
    else return ($name .$i.".".$extension);
}

/**
 * Resize image with transparency preserved from many types (jpeg, bmp, gif, png, jpg)
 * @param string $src Source image - ex : /MyDirectory/MyFolder/MyImage.png
 * @param string $dst Destination Folder for image - ex : /MyDirectory/MyNewFolder/MyResizedImage.png
 * @param int $width
 * @param int $height
 * @param bool $crop Resize Square/Rectangle - True/False - ( Test with rectangular image to see what happen )
 * 
 * @return string|bool
 *  Return error message or true if everything is ok
 * 
 */
function image_resize($src, $dst, $width, $height, $crop=true)
{
    if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";
    
    $type = strtolower(substr(strrchr($src,"."),1));
    if($type == 'jpeg') $type = 'jpg';
    switch($type){
      case 'bmp': $img = imagecreatefromwbmp($src); break;
      case 'gif': $img = imagecreatefromgif($src); break;
      case 'jpg': $img = imagecreatefromjpeg($src); break;
      case 'png': $img = imagecreatefrompng($src); break;
      default : return "Unsupported picture type!";
    }
    
    // resize
    if($crop){
      if($w < $width or $h < $height) return "Picture is too small!";
      $ratio = max($width/$w, $height/$h);
      $h = $height / $ratio;
      $x = ($w - $width / $ratio) / 2;
      $w = $width / $ratio;
    }
    else{
      if($w < $width and $h < $height) return "Picture is too small!";
      $ratio = min($width/$w, $height/$h);
      $width = $w * $ratio;
      $height = $h * $ratio;
      $x = 0;
    }
    
    $new = imagecreatetruecolor($width, $height);
    
    // preserve transparency
    if($type == "gif" or $type == "png"){
      imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
      imagealphablending($new, false);
      imagesavealpha($new, true);
    }
    
    imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
    
    switch($type){
      case 'bmp': imagewbmp($new, $dst); break;
      case 'gif': imagegif($new, $dst); break;
      case 'jpg': imagejpeg($new, $dst); break;
      case 'png': imagepng($new, $dst); break;
    }
    return true;
  }


  /**
   * Delete file
   *
   * @param  string $url
   *
   * @return void
   */
  function deleteFile($url)
  {
    // if( file_exists ( $url))
    unlink($url) or die("Couldn't delete file");
  }
