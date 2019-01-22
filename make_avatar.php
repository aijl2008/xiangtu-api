<?php
$i = 1;
$dir = '/Users/ajl/Desktop/高清头像打包下载_qq美女头像打包下载_高清电影打包下载 - 头像图片表情包大全_files/';
foreach (scandir($dir) as $file) {
    if (substr($file, 0, 1) == '.') {
        continue;
    }
    echo "cp  " . str_replace(' ', '\ ', $dir . $file) . " ./public/avatar/$i.jpg";
    echo PHP_EOL;
    $i++;
}
?>
