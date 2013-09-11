<?php
// max photo size 4M
// max photo resolution 2880x1800
// preferred resolution 1920x1080

// scaled version
// 800x600 gallery
// 170x125 ico

$srv_base = '/srv/puntaindiani.it/';

$db_name = 'gallery';
$db_user = 'gallery';
$db_pass = 'gallery';
$db_host = 'localhost';

ini_set('post_max_size', '22M'); // max post size
ini_set('upload_max_filesize', '5M'); // max size per file
ini_set('max_file_uploads', '4'); // max 4 upload a time
ini_set('max_input_time', '600'); // max upload time 10 minutes

function get_normalized_FILES () {
    $newfiles = array();
    foreach($_FILES as $fieldname => $fieldvalue)
        foreach($fieldvalue as $paramname => $paramvalue)
            foreach((array)$paramvalue as $index => $value)
                $newfiles[$fieldname][$index][$paramname] = $value;
    return $newfiles;
}

include_once('libs/img-resizer.php');

$original_path = $srv_base.'media/original/';
$big_path = $srv_base.'media/800x600/';
$big_ext = 'def.png';
$ico_path = $srv_base.'media/170x125/';
$ico_ext = 'min.png';

$res = array();

foreach (get_normalized_FILES()['photoes'] as $key => $photo) {
    //TODO: use db to preserve links
    $now = new DateTime('NOW');
    $new_name = md5(($now->format('Y-m-d H:i:s')).(rand(10000,65535)));

    // original
    $ext = strrchr($photo['name'], '.');
    $ext = strtolower($ext);
    $original = $original_path.$new_name.$ext;
    move_uploaded_file($photo['tmp_name'], $original);

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

    $res[]= array(
        'build-time' => $now,
        'original' => $original,
        'big' => $big,
        'ico' => $ico
    );
}

// update db
$link = mysql_connect($db_host, $db_user, $db_pass) or die('Error '.mysqli_error($link));
if ($stmt = $link->prepare("INSERT INTO photoes (name, file_name, uploader_email, authorized, uploaded_datetime, album) VALUES (?,?,?,?,?,?)")) {

    /* bind parameters for markers */
    $stmt->bind_param("s", $city);

    /* execute query */
    $stmt->execute();

    /* bind result variables */
    $stmt->bind_result($district);

    /* fetch value */
    $stmt->fetch();

    printf("%s is in district %s\n", $city, $district);

    /* close statement */
    $stmt->close();
}

/* close connection */
$link->close();
print(json_encode($res));
?>