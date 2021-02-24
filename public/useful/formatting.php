<?php 

/**
 * Display Title
 * @param string $text siteweb name
 * @param string $class class name
 * 
 * @return string 
 *  Return Bootstrap HTML Alert
 * 
 */
function styleSiteName($text, $class){
    $txt = "<a tabindex=-1 class='".$class."' href='".URL."home' ><div class='text-center'>";
    $txt .= $text;
    $txt .= "</div></a>";
    return $txt;
}

/**
 * Display Bootstrap Alert
 * @param string $text Text Alert displayed
 * @param int $type type of the alert
 * 
 * @return string 
 *  Return Bootstrap HTML Alert
 * 
 */
function displayAlert($text, $type, $is_resealable=true){
    $alert_type = "";
    switch($type)
    {
        case ALERT_SUCCESS : $alert_type = "success";
        break;
        case ALERT_WARNING : $alert_type = "warning";
        break;
        case ALERT_DANGER : $alert_type = "danger";
        break;
        case ALERT_INFO :
        default : $alert_type = "info";
        break;
    }
    $txt = '<div class="col-10 offset-1"><div class="alert alert-'.$alert_type.' alert-dismissible fade show" role="alert">';
    $txt .= $text;
    if($is_resealable) 
    {
        $txt .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    }
    $txt .= '</div></div>';
    return $txt;
}

function cleanString($string) 
{
    $string = preg_replace("#([^.a-z0-9]+)#i", "", $string);
    return $string;
}

/**
 * Cut a text to summarize it
 * @param string $str a string to summarize
 * @param int $size number of characters who will be displayed
 * 
 * @return string 
 *  Return $str summarized
 * 
 */
function displayCut($str,$size){
    $desc = "";
    $str = nl2br($str);
    if(strlen($str) > $size/2)
    {
        if(strpos($str, '<br />', ($size/2)) < $size){
            $desc = substr($str, 0, strpos($str, '<br />', ($size/2)));
        } else if(strpos($str, '.', ($size/2)) < $size){
            $desc = substr($str, 0, strpos($str, '.', ($size/2)));
        } else if(strpos($str, '<br />', 0) <= ($size/2)){
            $desc = substr($str, 0, strpos($str, '<br />', 0));
        } else if(strpos($str, '.', 0) <= ($size/2)){
            $desc = substr($str, 0, strpos($str, '.', 0));
        } else {
            $desc = substr($str, 0, strpos($str, ' ', ($size/3)));
        }
    } else {
        $desc = $str;
    }
    $desc .= "<b class='text-primary'>[...]</b>";
    return $desc;
}

function summarize($str, $size){
    $desc = "";
    $str = nl2br($str);
    if(strlen($str) > $size)
    {
        // var_dump(strlen($str).'<br />');
        // var_dump(strlen($str).'<br />');
        // var_dump('position : '.strpos($str, '[', ($size/2)).'<br />');
        // var_dump((strpos($str, '[', ($size/2)) == false).'<br />');

        if( !(strpos($str, '[', ($size/2)) == false) 
            && strpos($str, '[', ($size/2)) < $size)
        {
            $pos = strpos($str, '[', ($size/2));
            $desc = substr($str, 0, $pos);
            // var_dump('Balise [ <br />');
        }
        else if( !(strpos($str, '<br />', ($size/2)) == false) 
        && strpos($str, '<br />', ($size/2)) < $size)
        {
            $desc = substr($str, 0, strpos($str, '<br />', ($size/2)));
            // var_dump('Balise br <br />');
        }
        else if( !(strpos($str, '.', ($size/2)) == false) 
        && strpos($str, '.', ($size/2)) < $size)
        {
            $desc = substr($str, 0, strpos($str, '.', ($size/2)));
            $desc .='.';
            // var_dump('Balise . <br />');
        }
        else
        {
            $desc = substr($str, 0, strpos($str, ' ', ($size/2)));
        }
    } else {
        $desc = $str;
    }
    // $desc .= "[kbd]...[/kbd]";
    return $desc;
}

/**
 * Calculation between two dates
 * @param timestamp $date1 date more recently than $date2
 * @param timestamp $date2 date before $date1
 * 
 * @return array 
 *  Return array with params 'day', 'hour', 'minute' and 'second'
 * 
 */
function dateDiff($date1, $date2){
    $diff = abs($date1 - $date2); // abs to have absolute value, so avoid having negative difference
    $retour = array();
 
    $tmp = $diff;
    $retour['second'] = $tmp % 60;
 
    $tmp = floor( ($tmp - $retour['second']) /60 );
    $retour['minute'] = $tmp % 60;
 
    $tmp = floor( ($tmp - $retour['minute'])/60 );
    $retour['hour'] = $tmp % 24;
 
    $tmp = floor( ($tmp - $retour['hour'])  /24 );
    $retour['day'] = $tmp;
 
    return $retour;
}

/**
 * Return formatted text with a dateDiff return
 * @param array $dateDiff array with params 'day', 'hour', 'minute' and 'second'
 * @param string $isCreate bool value defined true by default - TRUE if create, False if edit
 * 
 * @return string formatted text
 * 
 */
function timeDiffToTxt($dateDiff,$isCreate = true)
{
    $formatedText = $isCreate ? 'Créé il y a ' : 'Modifié il y a ';
    if($dateDiff['day'] > 365)
    {
        $nb_year = floor($dateDiff['day'] / 365);
        $formatedText .= ($nb_year==1) ? $nb_year.' an' : $nb_year.' ans';
    }
    elseif($dateDiff['day'] > 0)
    {
        $formatedText .= ($dateDiff['day']==1) ? $dateDiff['day'].' jour' : $dateDiff['day'].' jours';
    }
    elseif($dateDiff['hour'] > 0)
    {
        $formatedText .= ($dateDiff['hour']==1) ? $dateDiff['hour'].' heure' : $dateDiff['hour'].' heures';
    }
    elseif($dateDiff['minute'] > 0)
    {
        $formatedText .= ($dateDiff['minute']==1) ? $dateDiff['minute'].' minute' : $dateDiff['minute'].' minutes';
    }
    elseif($dateDiff['second'] > 0)
    {
        $formatedText .= ($dateDiff['second']==1) ? $dateDiff['second'].' seconde' : $dateDiff['second'].' secondes';
    }
    elseif($dateDiff['second'] === 0)
    {
        $formatedText = 'Modifié à l\'instant';
    }
    return $formatedText;
}

/**
 *   elapsedTime between Edition timestamp or Creation timestamp
 * -- if edition doesn't exist, it will take creation by default --
 *
 * @param  int $date_create
 * @param  int $date_edit
 *
 * @return string Return formated text like : Modifié i y a 1 jour OR Créé il y a 3 secondes
 */
function elapsedTime($date_create, $date_edit)
{
    $actual_timestamp = new DateTime();
    $note_timestamp = (empty($date_edit)) ? strtotime($date_create) : strtotime($date_edit);
    $isCreate = (empty($date_edit)) ? true : false; 
    $diff  = dateDiff($actual_timestamp->getTimestamp(), $note_timestamp);

    return timeDiffToTxt($diff, $isCreate);
}


/**
 * find id between library pattern
 *
 * @param  string $txt
 *
 * @return array $matches
 */
function findImgID($txt)
{
    $tag = '/\[library\](.*?)\[\/library\]/is';
    // $matches = array();
    preg_match_all($tag, $txt, $matches, PREG_SET_ORDER);

    return $matches;
}

function createImgTag($image)
{
    $link = '<img class="img-fluid" src="'.USER_DIRECTORY.'images/user'.
        $image['id_user'].'/'.$image['url'].'" alt="'.$image['description'].'"/>';
    return $link;
}

// Convert message to HTML
function BBCode2Html($aTxt){
	// 1- replace line return by tag <br />
	$aTxt = nl2br($aTxt);

	// 2- BBCode tags list
	$tag = array(
		'/\[b\](.*?)\[\/b\]/is',
		'/\[i\](.*?)\[\/i\]/is',
		'/\[u\](.*?)\[\/u\]/is',
		'/\[s\](.*?)\[\/s\]/is',
		'/\[sup\](.*?)\[\/sup\]/is',
		'/\[sub\](.*?)\[\/sub\]/is',
		'/\[size\=(.*?)\](.*?)\[\/size\]/is',
		'/\[color\=(.*?)\](.*?)\[\/color\]/is',
		'/\[code\](.*?)\[\/code\]/is',
		'/\[quote\](.*?)\[\/quote\]/is',
		'/\[quote\=(.*?)\](.*?)\[\/quote\]/is',
		'/\[left](.*?)\[\/left\]/is',
		'/\[right](.*?)\[\/right\]/is',
		'/\[center](.*?)\[\/center\]/is',
		'/\[justify](.*?)\[\/justify\]/is',
		'/\[list\](.*?)\[\/list\]/is',
		'/\[list=1\](.*?)\[\/list\]/is',
		'/\[\*\](.*?)(\n|\r\n?)/is',
		'/\[img\](.*?)\[\/img\]/is',
		'/\[url\](.*?)\[\/url\]/is',
		'/\[url\=(.*?)\](.*?)\[\/url\]/is',
		'/\[email\](.*?)\[\/email\]/is',
        '/\[email\=(.*?)\](.*?)\[\/email\]/is',
        '/\[w\=(.*?)\](.*?)\[\/w\]/is',
        '/\[col\=(.*?)\](.*?)\[\/col\]/is',
        '/\[kbd](.*?)\[\/kbd\]/is',
        '/\[float=l](.*?)\[\/float\]/is',
        '/\[float=r](.*?)\[\/float\]/is'
	);

	// 3- correspondence HTML
	$h = array(
		'<strong>$1</strong>',
		'<em>$1</em>',
		'<u>$1</u>',
		'<span style="text-decoration:line-through;">$1</span>', 
		'<sup>$1</sup>',
		'<sub>$1</sub>',
		'<span style="font-size:$1px;">$2</span>',  
		'<span style="color:$1;">$2</span>',   
		'<pre><code class="card alert-secondary d-inline">$1</code></pre>', 
		'<blockquote>$1</blockquote>',
		'<blockquote><cite>$1 : </cite>$2</blockquote>',  
		'<div style="text-align:left;">$1</div>',
		'<div style="text-align:right;">$1</div>',
		'<div style="text-align:center;">$1</div>',
		'<div style="text-align:justify;">$1</div>',
		'<ul>$1</ul>',
		'<ol>$1</ol>',
		'<li>$1</li>',
		'<img class="img-fluid" src="$1" />',
		'<a href="$1">$1</a>',
		'<a href="$1">$2</a>',
		'<a href="mailto:$1">$1</a>',
        '<a href="mailto:$1">$2</a>',
        '<span class="d-inline-block w-$1">$2</span>',
        '<span class="d-inline-block col-3 col-md-$1">$2</span>',
        '<kbd>$1</kbd>',
        '<div class="float-left">$1</div>',
        '<div class="float-right">$1</div>'
	);

	// 4- replace BBCode tags by HTML tags in the text
	$n = 1;
	while($n > 0){
		$aTxt = preg_replace($tag, $h, $aTxt, -1, $n);
	}

	// 5- video tag
	// if(function_exists(VidProviderUrl2Player)) $aTxt = preg_replace_callback('/\[video\](.*?)\[\/video\]/is', 'VidProviderUrl2Player', $aTxt);

	// 6- clear the remaining tags
	return preg_replace(array('/\[(.*?)\]/is', '/\[\/(.*?)\]/is'), '', $aTxt);
}


// Convert message to HTML
function replaceByArray(){
	// 1- replace line return by tag <br />
	$aTxt = nl2br($aTxt);

	// 4- replace BBCode tags by HTML tags in the text
	$n = 1;
	while($n > 0){
		$aTxt = preg_replace($tag, $h, $aTxt, -1, $n);
	}

	// 5- video tag
	// if(function_exists(VidProviderUrl2Player)) $aTxt = preg_replace_callback('/\[video\](.*?)\[\/video\]/is', 'VidProviderUrl2Player', $aTxt);

	// 6- clear the remaining tags
	return preg_replace(array('/\[(.*?)\]/is', '/\[\/(.*?)\]/is'), '', $aTxt);
}

?>