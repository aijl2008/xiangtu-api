<?php
$i = 234;
$dir = '/data/code/git/xiangtu/service/public/cover/';
foreach (scandir($dir) as $file) {
    if (substr($file, 0, 1) == '.') {
        continue;
    }
    echo "cp $dir$file ./public/cover/$i.png";
    echo PHP_EOL;
    $i--;
}
?>
