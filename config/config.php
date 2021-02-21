<?php 
    const COOKIE_PROTECT = 'memo';
    const COOKIE_PSEUDO = 'visit';
    const COOKIE_PASSWORD = 'nb_visit';

    const COLOR_TITLE = 'titleStyle';

    // const ALERT_SUCCESS = 1;
    // const ALERT_DANGER = 2;
    // const ALERT_INFO = 3;
    // const ALERT_WARNING = 4;

    const ALERT_SUCCESS = "success";
    const ALERT_DANGER = "danger";
    const ALERT_INFO = "info";
    const ALERT_WARNING = "warning";

    // const MENU_STATE_INITIAL = 1;
    // const MENU_STATE_LOGGED = 2;
    // const MENU_STATE_BREADCRUMB = 3;

    const LIMIT_NOTES_BY_PAGE = 10;

    const LIMIT_AJAX_RETURN = 5;
    const LIMIT_SEARCH_RETURN = 20;

    const USER_DIRECTORY = "public/sources/users/";

    define("URL",str_replace("index.php","", (isset($_SERVER["HTTPS"])? "https" : "http"). "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
?>