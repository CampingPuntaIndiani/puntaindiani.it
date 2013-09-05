<?php
class Utils
{
    // param : file name
    // load json from file
    // return object
    public static function load_json($url)
    {
        if(!($file=file_get_contents($url))) return null;
        else return json_decode($file,false);
    }

    public static function load_file($url)
    {
        if(!($file=file_get_contents($url)))
            return '<h1>404 Page not found!</h1><h3>What were you donig? =( <a href="mailto:hkmartinb1993@gmail.com"> mail me: Admin</a></h3>';
        else
            return $file;
    }

    // param: current page
    // set current page
    // return: current lang
    public static function set_env_var($current_page=null, $force=false)
    {
        $av_page = array('home', 'booking', 'prices', 'gallery', 'location', 'map', 'offers', 'surroundings');
        if(session_id() == "") session_start();
        if($force or in_array($current_page, $av_page))
            $_SESSION['page'] = $current_page;
        $_SESSION['lang'] = (isset($_POST['lang']) ? $_POST['lang'] :
        (isset($_GET['lang']) ? $_GET['lang'] :
        (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'eng')));
        $_SESSION['page'] = (isset($_POST['page']) ? $_POST['page'] :
        (isset($_GET['page']) ? $_GET['page'] :
        (isset($_SESSION['page']) ? $_SESSION['page'] : 'home')));
        session_commit();
        return (object)array(
            'lang' => $_SESSION['lang'],
            'page' => $_SESSION['page']
        );
    }
}
?>
