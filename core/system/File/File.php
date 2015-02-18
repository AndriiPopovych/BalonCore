<?php

namespace Balon\System;

use Balon\DBProc;

class File
{

    function a()
    {
    }

    /**
     *
     * Function returning file list in directory ($dir).
     *
     * @param String $dir - name directory
     * @param bool $class - class of which this function
     * @param bool $name - if $name(true) then return only names files (without expention)
     * @return array
     */

    static public function fileList($dir, $class = false, $name = false)
    {
        if ($class) {
            if (!($file_list = scandir("cmp_system/$class/$dir"))) {
                $file_list = scandir("admin/cmp_system/$class/$dir");
            }
        } else {
            $file_list = scandir("$dir");
        }
        unset($file_list[0]);
        unset($file_list[1]);
        $file_list = array_values($file_list);
        if ($name) {
            foreach ($file_list as $files) {
                $files_l = explode(".", $files);
                $file[] = $files_l[0];
            }
            $file_list = $file;
        }
        return $file_list;
    }


    static public function resizeImage($tmp_name, $file_name, $size_image)
    {
        $end = explode(".", $tmp_name);
        $end = end($end);
        if (file_exists($tmp_name)) {
            if ($size_image[0] || $size_image[1]) {
                if ($size_image[0] == 100) {
                    $a = 0;
                    while ($a < 2) {
                        if ($a == 0) {
                            $size_image = [1000];
                        }
                        elseif ($a == 1) {
                            $size_image = [192];
                            $file_name = preg_replace("/(.*)\.([a-z]+)$/","$1_small.$2",$file_name);
                            $path = preg_replace("/(.*)\.([a-z]+)$/","$1_small.$2",$path);
                        }
                        $a++;
                        $filename = $tmp_name;
                        $size = getimagesize($filename);
                        $size_w = $size[0]; // ширина оригіналу
                        $size_h = $size[1]; // висота оригіналу
                        $w = $size_image[0]; // потрібна ширина
                        $h = $size_image[1]; // потрібна висота
                        if ($size_w > $size_h) {
                            $h = $size_image[0];
                            $w = round($h * $size_w / $size_h);
                        }
                        else {
                            $w = $size_image[0];
                            $h = round($w * $size_h / $size_w);
                        }
                        $type = $size['mime'];
                        // щоб виликі картинки норм завантажувались
                        if (($size[0] > $w) or ($size[1] > $h)) {
                            exec('mogrify -resize ' . $w . 'x' . $h . ' ' . $filename);
                            $size = getimagesize($filename);
                            $size_w = $w; // ширина оригіналу
                            $size_h = $h; // висота оригіналу
                        }
                        switch ($type) {
                            case 'image/png':
                                $image = imagecreatefrompng($filename);
                                break;
                            case 'image/jpeg':
                                $image = imagecreatefromjpeg($filename);
                                break;
                            case 'image/gif':
                                $image = imagecreatefromgif($filename);
                                break;
                        }
                        $new_image = imagecreatetruecolor($w, $h);
                        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $w, $h, $size_w, $size_h);
                        if ($path) {
                            imagejpeg($new_image, $path, 75);
                        } else {
                            imagejpeg($new_image, $file_name, 75);
                        }
                        imagedestroy($new_image);
                        /*if (file_exists($tmp_name) && !$unlinked) {
                            unlink($tmp_name);
                        }*/
                    }
                }
                else {
                    $filename = $tmp_name;
                    $size = getimagesize($filename);
                    $size_w = $size[0]; // ширина оригіналу
                    $size_h = $size[1]; // висота оригіналу
                    $w = $size_image[0]; // потрібна ширина
                    $h = $size_image[1]; // потрібна висота
                    if (!$h) {
                        $h = round($w * $size_h / $size_w);
                    }
                    $type = $size['mime'];
                    // щоб виликі картинки норм завантажувались
                    if (($size[0] > $w) or ($size[1] > $h)) {
                        exec('mogrify -resize ' . $w . 'x' . $h . ' ' . $filename);
                        $size = getimagesize($filename);
                        $size_w = $w; // ширина оригіналу
                        $size_h = $h; // висота оригіналу
                    }
                    switch ($type) {
                        case 'image/png':
                            $image = imagecreatefrompng($filename);
                            break;
                        case 'image/jpeg':
                            $image = imagecreatefromjpeg($filename);
                            break;
                        case 'image/gif':
                            $image = imagecreatefromgif($filename);
                            break;
                    }
                    $new_image = imagecreatetruecolor($w, $h);
                    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $w, $h, $size_w, $size_h);
                    if ($path) {
                        imagejpeg($new_image, $path, 75);
                    } else {
                        imagejpeg($new_image, $file_name, 75);
                    }
                    imagedestroy($new_image);
                    if (file_exists($tmp_name) && !$unlinked) {
                        unlink($tmp_name);
                    }
                }
            } else {
                copy($tmp_name, $file_name);
            }
        }
    }

    function download($file) {


            $info = getimagesize($file);
            if ($info) {
                $file_name = end(explode("/",$file));
                $mime = $info['mime'];
                //header ("Content-Type: application/octet-stream");
                header ("Content-Type: application/$mime");
                header ("Accept-Ranges: bytes");
                header ("Content-Length: ".filesize($file));
                header ("Content-Disposition: attachment; filename=".$file_name);
                readfile($file);
            }
    }

    function toZip() {
        $file_folder = "lib/img/part_photoshots/";
        if ($_GET['id']) {
            $db = DBProc::instance();
            $post= $db->select("part_photoshots",false,["id_photoshots" => $_GET['id']],false,false,[0,995]);
            $name_album = $db->select("photoshots",false,["id_photoshots" => $_GET['id']])[0]['title'];
            $zip = new \ZipArchive();
            $zip_name = time() . ".zip";
            if ($zip->open($zip_name, \ZIPARCHIVE::CREATE) !== TRUE) {
                $error = "* Sorry ZIP creation failed at this time";
            }
            foreach ($post as $file) {
                $zip->addFile($file_folder . $file['image_photoshot'],$file['image_photoshot']);
            }
            $zip->close();
            if (file_exists($zip_name)) {
                header('Content-type: application/zip');
                header ("Accept-Ranges: bytes");
                header ("Content-Length: ".filesize($zip_name));
                header('Content-Disposition: attachment; filename="' . $name_album . '"');
                readfile($zip_name);
                unlink($zip_name);
            }
        }
    }

}

?>