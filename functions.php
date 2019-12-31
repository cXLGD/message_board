<?php

function pre($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function del($dirname)
{
    unlink($dirname);
}

// 下载图片
function down_img($file)
{
    // 图片名称
    $img_name = time() . $file['name'][0];

    // 图片路径
    $img_dir = $file['tmp_name'][0];
    $dir = './uploads';
    if (!is_dir($dir)) {
        mkdir($dir);
    }

    $path = $dir . '/' . $img_name;
    // echo $path;

    // 下载图片
    if (move_uploaded_file($img_dir, $path)) {
        echo "<script>alert('图片留言成功!');</script>";
    }
    return $path;
}
