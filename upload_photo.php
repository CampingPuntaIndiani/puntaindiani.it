<?php
// max photo size 10M
// max photo resolution 2880x1800
// preferred resolution 1920x1080

// scaled version
// 800x600 gallery
// 170x125 ico

$srv_base = '/web/htdocs/www.puntaindiana.it/home/'; // /srv/puntaindiani.it/

include_once 'libs/pwd.inc.php';
include_once 'libs/media.inc.php';


// TODO: test if aruba allow ini_set
ini_set('post_max_size', '60M'); // max post size //!ARUBA: 30M
ini_set('upload_max_filesize', '10M'); // max size per file //!ARUBA: 25M
ini_set('max_file_uploads', '5'); // max files upload a time //!ARUBA 20
ini_set('max_input_time', '600'); // max upload time 10 minutes //!ARUBA: 120

// Aruba seams allows ini_set so...
ini_set("memory_limit", "128M");
ini_set("max_execution_time", "300");
$UPLOADS_MAX_FILESIZE = 10 * 1024 * 1024 ;

function get_normalized_FILES () {
    $newfiles = array();
    foreach($_FILES as $fieldname => $fieldvalue)
        foreach($fieldvalue as $paramname => $paramvalue)
            foreach((array)$paramvalue as $index => $value)
                $newfiles[$fieldname][$index][$paramname] = $value;
    return $newfiles;
}

include_once('libs/img-resizer.php');


$uploaded = array(
    'rejected' => array(),
    'accepted' => array()
);

$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('Error '.mysqli_error($link));

$norm_FILES=get_normalized_FILES();
foreach ($norm_FILES['photoes'] as $key => $photo) {
    $ext = strrchr($photo['name'], '.');
    $ext = strtolower($ext);

    if ((!in_array($ext, array('.jpg', '.jpeg', '.png', '.gif'))) or $photo['size'] > $UPLOADS_MAX_FILESIZE) {
        $uploaded['rejected'][] = $photo['name'];
        continue;
    }

    $now = new DateTime('NOW');
    $new_name = md5((microtime()).(rand(10000,65535)));

    // original
    $original = $original_path.$new_name.$ext;
    if(!move_uploaded_file($photo['tmp_name'], $original) or strpos(mime_content_type($original), "image/") !== 0) {
        $uploaded['rejected'][] = $photo['name'];
        unlink($original);
        continue;
    }

    // 800x600
    $resizer = new resize($original);
    $resizer->resizeImage(800, 600, 'auto'); 
    $big = $big_path.$new_name.$big_ext;
    $resizer->saveImage($big);

    // 170x125
    $resizer = new resize($original);
    $resizer->resizeImage(170, 125, 'exact'); 
    $ico = $ico_path.$new_name.$ico_ext;
    $resizer->saveImage($ico);

    // update db
    if ($stmt = $link->prepare("INSERT INTO photoes (name, file_name, uploader_email, authorized, uploaded_datetime, album) VALUES (?,?,?,0,?,?)")) {
        $stmt->bind_param("sssss", $photo['name'], $new_name, $_POST['email'], $now->format('Y-m-d H:i:s'), strtolower($_POST['album']));
        $stmt->execute();
        $stmt->close();
    }

    $uploaded['accepted'][] = $photo['name'];
}
/* close connection */
$link->close();
print(json_encode($uploaded));
?>
