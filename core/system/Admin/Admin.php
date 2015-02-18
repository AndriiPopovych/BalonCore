<?php

/**
 * Класс виконує всі бажжання адміна ;)
 */
namespace Balon;

use Balon\System\File;

class Admin
{

    // масив з розмірами картинок в залежності від назви таблиці

    private $imageSize = [
        "tour" => [100],
        "country" => [134],
        "image" => [100],
        "points" => [100]
    ];

    private final function __construct()
    {
        $this->db = DBProc::instance();
        //$this->cache = Cache::instance();
        $this->site = $_SESSION['site'];
        $this->table = "none";
        $this->replace = 'Запит виконано успішно.<script>history.go(-1)</script>';
    }

    /*function __destruct() {
        if ($this->table != "none") {
            $this->cache->refresh($this->table);
        }
    }*/

    static function instance()
    {
        static $instance;
        if (isset($instance)) {
            return $instance;
        }
        $instance = new static;
        return $instance;
    }


    static function status()
    {
        if ($_SESSION['admin']) {
            return true;
        } else {
            return false;
        }
    }


    function remove($array)
    {
        $id = $array['id'];
        $table = $array['table'];
        $this->table = $table;
        if ($array['what']) {
            $image = $this->db->select($table, false, [$array['what'] => $id]);
            if ($image) {
                foreach ($image as $val) {
                    foreach ($val as $k => $v) {
                        if ($v) {
                            if (preg_match("/.*image.*/", $k) && !preg_match("/.*id\_.*/", $k)) {
                                unlink('../lib/image/' . $table . '/' . $v);
                            }
                        }
                    }
                }
            }
        }
        $parent_id = $array['parent_id'];
        if ($array['what']) {
            $val[$array['what']] = $array['id'];
        } else {
            $val["id"] = $id;
        }
        if ($parent_id) $val["parent_id"] = $parent_id;
        if ($array['data']) {
            $data = str_replace("'", "\"", $array['data']);
            $data = json_decode($data);
            foreach ($data as $key => $vals) {
                $tbl = $key;
                foreach ($vals as $k => $v) {
                    $where[$k] = $vals->$k;
                }
                $this->db->delete($tbl, $where);
            }
        }
        $result = $this->db->delete("$table", $val);
        if (!$result) {
            echo "okey";
        } else {
            echo $result;
        }
    }

    function add($array)
    {
        $table = $array['table'];
        $this->table = $table;
        $sql = [
            "TABLE_SCHEMA" => $_SESSION['db_name'],
            "TABLE_NAME" => $_SESSION['prefix'] . "_$table"
        ];
        //$sql = "TABLE_SCHEMA = ".$_SESSION['db_name'].";TABLE_NAME =".$_SESSION['prefix']."_$table";
        $rows = $this->db->select("information_schema.COLUMNS", false, $sql);
        if (is_array($rows)) {
            foreach ($rows as $row) {
                if ($row["COLUMN_NAME"] == "create_date") {
                    $date = Date::getDate();
                    $parent_id = "create_date = $date";
                    $k = ";";
                }
                elseif($row['COLUMN_NAME'] == "order") {
                    $pos = $this->db->send_query("SELECT MAX(`order`)
                        FROM  `t_".$table."`");
                    if ($pos[0]['MAX(`order`)']) {
                        $result['order'] = ++$pos[0]['MAX(`order`)'];
                    }
                    else {
                        $result['order'] = 1;
                    }
                }
            }
        }
        if ($array['data']) {
            $data = str_replace("'", "\"", $array['data']);
            $data = json_decode($data);
            foreach ($data as $key => $val) {
                $result[$key] = $data->$key;
            }
            //$parent_id .= "$k parent_id = ".$array['parent_id'];
        }
        echo $this->db->insert($table, $result);
    }



    function getEditHtmlNew($array) {
        $table = $array['table'];
        $this->table = "none";
        $_SESSION[md5('table')] = $table;
        $_SESSION[md5('id')] = $array['id'];
        $_SESSION[md5('what')] = $array['what'];
        if ($array['what']) {
            $what = $array['what'];
        } else {
            $what = "id";
        }
        //забираємо з масиву посилання сторінки, на яку потрібно перенаправити юзера
        if ($array['returnHref']) {
            $returnHref = $array['returnHref'];
            unset($array['returnHref']);
        }
        $id = $array['id'];
        $config = file_get_contents("conf.json");
        $fields= json_decode($config,true)[$table];
        $html = ""; // html forms with value fields
        $k = $q = 0; // count inputs for files and checkbox elements
        $db = DBProc::instance();
        $oldValues = $db->select($table,false,[$what => $id])[0];
        foreach ($fields['fields'] as $column_name => $field) {
            if (is_array($field)) {
                $column_type = $field[0];
                $column_text = $field[1];
                if ($column_type == "resize_image") {
                    $width = $field[2];
                    $height = $field[3];
                }
            }
            switch ($column_type) {
                case "text":
                    $html .= $column_text.':
                        <p>
                            <input name="' . $column_name . '" type="text"  value="' . $oldValues[$column_name] . '" />
                        </p>';
                    break;
                case "image":
                    $html .= '
                        <div class="file_upld">
                            <br/>
                            '. $column_text .'
                            <br/>
                            <input type="file" class="fileUpload" multiple type="file" name ="file[]" value="' . $oldValues[$column_name] . '"/>
                            <input type="hidden" name="image_what' . $k . '" value="' . $column_name . '">
                        </div><br />';
                    $k++;
                    break;
                case "resize_image":
                    /*
                        //TODO: дописати метод для кропу фоток
                    */
                    $html .= '
                        <div class="file_upld">
                            <div id="crop_img_box1" class="crop_box slider_img_box" data-w="' . $width . '" data-h="' . $height . '"></div>
                            <input type="hidden" name="file' . $k . '" value="' . $oldValues[$column_name] . '" class="js-cropic_file" />
                            <input type="hidden" name="image_what' . $k . '" value="' . $column_name . '">
                            <script>
                                var croppicContainerModalOptions = {
                                    uploadUrl:  "/img_save_to_file.php",
                                    cropUrl:    "/img_crop_to_file.php",
                                    modal:      true,
                                    imgEyecandyOpacity: 0.4
                                };
                                var cropContainerModal = new Croppic("crop_img_box1", croppicContainerModalOptions);
                            </script>
                        </div><br />';
                    $k++;
                    break;
                case "audio":
                    $html .= $column_text . '
                        <div class="file_upld">
                            <input type="file" name="audio' . $k . '" value="' . $oldValues[$column_name] . '" class="fileUpload" />
                            <input type="hidden" name="audio_what' . $k . '" value="' . $column_name . '">
                        </div><br />';
                    break;
                case "video":
                    $html .= $column_text . '
                        <div class="file_upld">
                            <input type="file" name="file' . $k . '" value="' . $oldValues[$column_name] . '" class="fileUpload" />
                            <input type="hidden" name="image_what' . $k . '" value="' . $column_name . '">
                        </div><br />';
                    break;
                case "select":
                    $html .= "<div class='select'><select name='$column_name'>";
                    $selectList = $fields[2];
                    foreach ($selectList as $key => $value) {
                        if ($value == $oldValues[$column_name]) {
                            $check = "selected";
                        }
                        $html .= "<option value='$value' $check>$value</option>";
                        $chck = "";
                    }
                    $html .= "</select></div>";
                    break;
                case "checkbox":
                    $html .= '
                        <div class="inp_container">
                            <input type="checkbox" class="checkbox-style" id="checkbox' . $g . '" name="' . $column_name. '" ';
                    if ($oldValues[$column_name] == "on") $html .= "checked";
                    $html .= "/>";
                    $html .= '
                            <label class="label-checkbox" for="checkbox' . $g . '">' . $column_text . '</label>
                        </div><br />';
                    $g += 1;
                    break;
                case "editor":
                    $bb = new \bbcode($oldValues[$column_name]);
                    $html .= "
                        <div class='textarea'>
                            <textarea id=\"editor\" name='$column_name'>";
                    $html .= $bb->get_html();
                    $html .= "</textarea>
                        </div>";
                    break;
            }
            $html .= "\n";
        }
        if ($returnHref) {
            $html .= "<input type='hidden' name='returnedHref' value='$returnHref' />";
        }
        $html .= '
            <div class="mod_footer">
                <input class="ok" type="submit" value="Ok">
            </div>';
        echo $html;
    }

    function getEditHtml($array)
    {
        $table = $array['table'];
        $this->table = "none";
        $_SESSION[md5('table')] = $table;
        $_SESSION[md5('id')] = $array['id'];
        $_SESSION[md5('what')] = $array['what'];
        if ($array['what']) {
            $what = $array['what'];
        } else {
            $what = "id";
        }
        $id = $array['id'];
        if ($id == "most") {
            $text = '<input class="normal center_bl block" name="most"
                        type="text"  value="' . $result[$comment] . '" />';
            $text .= '<input class="btn btn_reply strong center_bl ok" type="submit" value="Ok">';
            return;
        }
        $rows = $this->db->select($table, false, [$what => $id]);
        $result = $rows[0];
        $sql = [
            "TABLE_SCHEMA" => $_SESSION['db_name'],
            "TABLE_NAME" => $_SESSION['prefix'] . "_$table"
        ];
        //$sql = "TABLE_SCHEMA = ".$_SESSION['db_name'].";TABLE_NAME =".$_SESSION['prefix']."_$table";
        $rows = $this->db->select("information_schema.COLUMNS", false, $sql);
        $text = "";
        foreach ($rows as $row) {
            if ($row['COLUMN_COMMENT']) {
                $comment = $row['COLUMN_NAME'];
                $image = explode(" ", $row['COLUMN_COMMENT']);
                if ($image[0] == 'Картинка' || $comment == "multiple") {
                    //if ($row["COLUMN_NAME"] != "image_full") {
                    $word = str_replace("Картинка", "", $row['COLUMN_COMMENT']);
                    if (!$word) $word = "Картинка";
                    $text .= $word . " :";
                    //<input type="hidden" name="file' . $k . '" value="' . $result[$comment] . '" class="js-cropic_file" />
                    if ($image[1] == "Розмір") {
                        $text .= '
                        <div class="file_upld">
                            <div id="crop_img_box1" class="crop_box slider_img_box" data-w="' . $image[2] . '" data-h="' . $image[3] . '"></div>
                            <input type="hidden" name="file' . $k . '" value="' . $result[$comment] . '" class="js-cropic_file" />
                            <input type="hidden" name="image_what' . $k . '" value="' . $row['COLUMN_NAME'] . '">
                            <script>
                                var croppicContainerModalOptions = {
                                    uploadUrl:  "/img_save_to_file.php",
                                    cropUrl:    "/img_crop_to_file.php",
                                    modal:      true,
                                    imgEyecandyOpacity: 0.4
                                };
                                var cropContainerModal = new Croppic("crop_img_box1", croppicContainerModalOptions);
                            </script>
                        </div><br />';
                    } else {
                        $text .= '
                        <div class="file_upld">
                            <br/>
                            Завантажити файл
                            <br/>
                            <input type="file" class="fileUpload" multiple type="file" name ="file" value="' . $result[$comment] . '"/>
                            <input type="hidden" name="image_what' . $k . '" value="' . $row['COLUMN_NAME'] . '">
                        </div><br />';
                    }
                    //}
                    $k += 1;
                } elseif ($image[0] == 'Chackbox') {
                    $comment_text = preg_replace("/Chackbox[\s]/","",$row['COLUMN_COMMENT']);
                    $text .= '
                        <div class="inp_container">
                            <input type="checkbox" class="checkbox-style" id="checkbox' . $g . '" name="' . $row['COLUMN_NAME'] . '" ';
                    if ($result[$comment] == "on") $text .= "checked";
                    $text .= "/>";
                    $text .= '
                            <label class="label-checkbox" for="checkbox' . $g . '">' . $comment_text . '</label>
                        </div><br />';
                    $g += 1;
                } elseif ($image[0] == 'Відео') {
                    $word = str_replace("Відео", "", $row['COLUMN_COMMENT']);
                    $text .= $word . ":";
                    $text .= '
                        <div class="file_upld">
                            <input type="file" name="file' . $k . '" value="' . $result[$comment] . '" class="fileUpload" />
                            <input type="hidden" name="image_what' . $k . '" value="' . $row['COLUMN_NAME'] . '">
                        </div><br />';
                } elseif ($image[0] == 'Аудіо') {
                    $word = str_replace("Аудіо", "", $row['COLUMN_COMMENT']);
                    $text .= $word . ":";
                    $text .= '
                        <div class="file_upld">
                            <input type="file" name="audio' . $k . '" value="' . $result[$comment] . '" class="fileUpload" />
                            <input type="hidden" name="audio_what' . $k . '" value="' . $row['COLUMN_NAME'] . '">
                        </div><br />';
                } elseif ($image[0] == 'Editor') {
                    $bb = new \bbcode($result[$comment]);
                    $text .= "
                        <div class='textarea'>
                            <textarea id=\"editor\" name='{$row['COLUMN_NAME']}'>";
                    if (is_array($result)) {
                        $text .= $bb->get_html();
                    }
                    $text .= "</textarea>
                        </div>";
                    $g += 1;
                } elseif ($image[0] == "Select") {
                    $select = $this->db->select("$image[1]");
                    $text .= "<div class='select'><select name='{$row['COLUMN_NAME']}'>";
                    foreach ($select as $key => $value) {
                        // $result[$comment];
                        if ($result[$comment] == $value['id_column']) $check = "selected";
                        $text .= "<option value='{$value['id_column']}' $check>{$value['name_column']}</option>";
                        $check = "";
                    }
                    $text .= "</select></div>";
                    $g += 1;
                } else {
                    $text .= $row['COLUMN_COMMENT'] . ":";
                    $text .= '
                        <p>
                            <input name="' . $row['COLUMN_NAME'] . '" type="text"  value="' . $result[$comment] . '" />';
                    if (($row['COLUMN_NAME']  == "author_id" && $table == "article") || ($row['COLUMN_NAME']  == "author_id" && $table == "photoshots")) {
                        $text .= '<a href = "'.SITE.'My/Admin" target="_blank"><i class="icon-search_toolbar js-open_search_box"></i></a>';
                    }
                    $text .= '
                        </p>';
                }
                $text .= "\n";
            }
        }
        $text .= '
            <div class="mod_footer">
                <input class="ok" type="submit" value="Ok">
            </div>';
        echo $text;
    }


    public function newEdit($array,$files) {
        //TODO: implements this method
    }

    function edit($array, $files)
    {
        //забираємо з масиву посилання сторінки, на яку потрібно перенаправити юзера
        if ($array['returnedHref']) {
            $returnHref = $array['returnedHref'];
            unset($array['returnedHref']);
        }
        $table = $_SESSION[md5('table')];
        $this->table = $table;
        // Увага костиль!!!!!! Щоб за умовчуванням стояла стаття не спеціальна. Проблема в тому, що
        // Чекбокс не повертає значення виключеного стану
        // Я це пишу 17.10.2014 їду додому в Прилуки) щасти тобі)
        if ($table == "tour" && !isset($array['type'])) {
            $array['type'] = "Off";
        }
        $id = $_SESSION[md5('id')];
        if ($array['what']) {
            $what = $array['what'];
            unset($array['what']);
        } else {
            if ($_SESSION[md5('what')]) {
                $what = $_SESSION[md5('what')];
            } else {
                $what = "id";
            }
        }
        $files_list = $files;
        $files = [];
        $i = 0;
        // crunches!!!!!
        /*print_r ($array['file']);
        if ($array['file'] && !$last) {
            if (explode("/", $array['file'])[1]) {
                $files['file']['name'] = explode("/", $array['file'])[1];
                $files['file']['tmp_name'] = $array['file'];
                unset($array['file']);
                unset($array['image_what']);
                $j++;
            }
        }*/
        /*if (is_array($files_list['file']['name'])) {
            $files["file"]['tmp_name'] = $files_list['file']['tmp_name'][$i];
            $files["file"]['name'] = $files_list['file']['name'][$i];
        }*/
        /*var_dump($files_list);
        var_dump($files);
        var_dump($array);*/
        foreach ($array as $key => $value) {
            if (preg_match("/image_what/", $key)) {
                if ($value == "multiple") {
                    $count = count($files_list['file']['name']);
                    for ($i = 0; $i < $count + $k; $i++) {
                        if (isset($files_list['file']['tmp_name'][$i])) {
                            $files["file" . $j]['tmp_name'] = $files_list['file']['tmp_name'][$i];
                            $files["file" . $j]['name'] = $files_list['file']['name'][$i];
                            unset($files_list['file']['tmp_name'][$i]);
                            unset($files_list['file']['name'][$i]);
                            $last = $i;
                            $j++;
                        }
                    }
                }
                elseif ($value == "image1") {
                    if (isset($files_list['file']['name'][0])) {
                        $files["file1"]["name"] = $files_list['file']['name'][0];
                        $files["file1"]["tmp_name"] = $files_list['file']['tmp_name'][0];
                        unset($files_list['file']['tmp_name'][0]);
                        unset($files_list['file']['name'][0]);
                    }
                    elseif (isset($files_list['file']['name'][1])) {
                        $files["file1"]["name"] = $files_list['file']['name'][1];
                        $files["file1"]["tmp_name"] = $files_list['file']['tmp_name'][1];
                        unset($files_list['file']['tmp_name'][1]);
                        unset($files_list['file']['name'][1]);
                    }
                }
                else { // ($value == "image") {
                    if (isset($array['file']) && $table == "article") {
                        $files["file"]["name"] = $array['file'];
                        $files["file"]["tmp_name"] = "temp/".$array['file'];/*
                        unset($files_list['file']['tmp_name'][0]);
                        unset($files_list['file']['name'][0]);*/
                    }
                    elseif (isset($files_list['file']['name'][0])) {
                        $j++;
                        $k = 1;
                        $files["file"]["name"] = $files_list['file']['name'][0];
                        $files["file"]["tmp_name"] = $files_list['file']['tmp_name'][0];
                        unset($files_list['file']['tmp_name'][0]);
                        unset($files_list['file']['name'][0]);
                    }
                    elseif (isset($files_list['file']['name'])) {
                        $files["file"]["name"] = $files_list['file']['name'];
                        $files["file"]["tmp_name"] = $files_list['file']['tmp_name'];
                        unset($files_list['file']['tmp_name']);
                        unset($files_list['file']['name']);
                    }
                }
            }
        }
        if (!$files) {

        }
        print_r ($files);
        while (isset($files["file$a"]['name'])) {
            if ($files["file$a"]['name'] && $files["file$a"]['tmp_name']) {
                $name = iconv("utf-8", "cp1251", $files["file$a"]['name']);
                $name = str_replace("#","",$name);
                $name = str_replace("/","",$name);
                //це піздець які тут костилі... але по іншому просто ніяк...
                if ($array['image_what' . $a] && $array['image_what' . $a] != "multiple" && !$crunches) {
                    $tbl = $array['image_what' . $a];
                    //unset($array['image_what'.$a]);
                    $image = $this->db->select("$table", $tbl, [$what => $id]);
                } elseif ($array['image_what1'] == "multiple") {
                    $crunches = true;
                } elseif (!$crunches) {
                    $tbl = "image";
                    $image = $this->db->select("$table", $tbl, [$what => $id]);
                }
                if ($array['image_what'.$a] != "image1") {
                    $resize =  $this->imageSize[$table];
                }
                else {
                    $resize = $this->imageSize['image_special'];
                }
                if ($image && !$crunches) {
                    $exp = "";
                    if (explode("/", $files["file$a"]['tmp_name'])[0] == "temp") {
                        $exp = "../";
                    }
                    if (file_exists('../lib/image/' . $table . '/' . $image) && file_exists($files["file$a"]['tmp_name'])) {
                        if (unlink('../lib/image/' . $table . '/' . $image)) {
                            if (!is_dir("../lib/image/$table")) mkdir("../lib/image/$table", 0777);
                            File::resizeImage($exp . $files["file$a"]['tmp_name'], "../lib/image/$table/$name",$resize);
                            if (file_exists($exp . $files["file$a"]['tmp_name']) && $name) {
                                unlink($exp . $files["file$a"]['tmp_name']);
                            }
                        }
                    } else {
                        if (!is_dir("../lib/image/$table")) mkdir("../lib/image/$table", 0777);
                        File::resizeImage($exp . $files["file$a"]['tmp_name'], "../lib/image/$table/$name",$resize);
                        if (file_exists($exp . $files["file$a"]['tmp_name']) && $name) {
                            unlink($exp . $files["file$a"]['tmp_name']);
                        }
                    }
                } else {
                    $exp = "";
                    if (explode("/", $files["file$a"]['tmp_name'])[0] == "temp") {
                        $exp = "../";
                    }
                    if (!$crunches) {
                        if (!is_dir("../lib/image/$table")) mkdir("../lib/image/$table", 0777);
                        File::resizeImage($exp . $files["file$a"]['tmp_name'], "../lib/image/$table/$name", $resize);
                    } else {
                        if (!is_dir("../lib/image/part_photoshots")) mkdir("../lib/image/part_photoshots", 0777);
                        File::resizeImage($exp . $files["file$a"]['tmp_name'], "../lib/image/part_photoshots/$name", $this->imageSize["part_photoshots"]);
                        $this->db->insert("part_photoshots", ["id_photoshots" => $_SESSION[md5('id')], "image_photoshot" => $name]);
                    }

                }
                if ($array['image_what' . $a]) {
                    $img = $array['image_what' . $a];
                    unset($array['image_what' . $a]);
                    if ($name) {
                        $result[$img] = $name;
                    }
                } else {
                    if (!$crunches && $name) {
                        $result["image$a"] = $name;
                    }
                }
            }
            $k = ";;";
            $a += 1;
        }
        unset($array['what']);
        if ($files_list["video"]['name']) {
            $name = iconv("utf-8", "cp1251", $files_list["video"]['name']);
            $image = $this->db->select("$table", 'video', [$what => $id]);
            if ($image) {
                if (unlink('../lib/image/' . $table . '/' . $image)) {
                    if (!is_dir("../lib/image/$table")) mkdir("../lib/image/$table", 0777);
                    copy($files_list["video"]['tmp_name'], "../lib/image/$table/$name");
                } else {
                    if (!is_dir("../lib/image/$table")) mkdir("../lib/image/$table", 0777);
                    copy($files_list["video"]['tmp_name'], "../lib/image/$table/$name");
                }
            } else {
                if (!is_dir("../lib/image/$table")) mkdir("../lib/image/$table", 0777);
                copy($files_list["video"]['tmp_name'], "../lib/image/$table/$name");
            }
            if ($array['video_what']) {
                $img = $array['video_what'];
                unset($array['video_what']);
                $result[$img] = $name;
            } else {
                $result["video"] = $name;
            }
            $k = ";;";
        }
        if ($files_list["audio"]['name']) {
            $name = iconv("utf-8", "cp1251", $files_list["audio"]['name']);
            if ($array['audio_what']) {
                $image = $this->db->select("$table", $array['audio_what'], [$what => $id]);
                $img = $array['audio_what'];
            } else {
                $image = $this->db->select("$table", 'audio', [$what => $id]);
            }
            if ($image) {
                if (unlink('../lib/audio/' . $table . '/' . $image)) {
                    if (!is_dir("../lib/audio/$table")) mkdir("../lib/audio/$table", 0777);
                    copy($files_list["audio"]['tmp_name'], "../lib/audio/$table/$name");
                } else {
                    if (!is_dir("../lib/audio/$table")) mkdir("../lib/audio/$table", 0777);
                    copy($files_list["audio"]['tmp_name'], "../lib/audio/$table/$name");
                }
            } else {
                if (!is_dir("../lib/audio/$table")) mkdir("../lib/audio/$table", 0777);
                copy($files_list["audio"]['tmp_name'], "../lib/audio/$table/$name");
            }
            if ($array['audio_what']) {
                $img = $array['audio_what'];
                unset($array['audio_what']);
                $result[$img] = $name;
            } else {
                $result["audio"] = $name;
            }
            $k = ";;";
        }
        foreach ($array as $key => $value) {
            if (preg_match("/image_what/", $key)) {
                unset($array[$key]);
            }
        }
        unset($array['video_what']);
        unset($array['audio_what']);
        unset($array['what']);
        unset($array['image_what1']);
        unset($array['file']);
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                //$value = preg_replace("/\<.*\>.*\<\/.*\>/", "", $value);
                //$value = preg_replace("/\[([a-z]+)\]/", "<$1>", $value);
                //$value = preg_replace("/\[\/([a-z]+)\]/", "</$1>", $value);
                $result["$key"] = $value;
            }
        }
        if ($table == "article") {
            $result['href_name'] = Reports::translit($array['title']);
        }
        if (!$this->db->update("$table", $result, [$what => $id])) {
            try {
                /*if (!header("Location:$returnHref")) {
                    throw new \Exception("Сталася якась помилка. Заголовок не було выдправлено");
                }*/
            }
            catch (\Exception $e){
                echo $e->getMessage();
                //echo $this->replace;
            }
            //echo $this->replace;
        }
    }

    function hz($array, $text, $name)
    {
        $arr = $this->db->select($text);
        $end = '<label for="' . $val['name'] . '">Оберіть ' . $name . '</label>';
        $end .= '<select size="1" id="' . $text . '" name="' . $text . '">';
        foreach ($arr as $val) {
            $end .= '<option  value="' . $val['name'] . '"';
            if ($array[$text] == $val['name']) $end .= " selected ";
            $end .= '>' . $val['name'] . '</option>';
        }
        $end .= '<option value="None">None</option></select><br>';
        return $end;
    }

    function edit_visibility($array)
    {
        $table = $array['table'];
        $id = $array['id'];
        $visibility = $array['visibility'];
        if ($array['what']) {
            $what = $array['what'];
        }
        else {
            $what = "id";
        }
        $this->db->update($table, ["visibility" => $visibility], [$what => $id]);
    }

    function position($array)
    {
        //crunches!!!
        //if ($array['table'] == 'part_article') {
        $array = $array['array'];
        $id = $array['id'];
        $new = $array['new'];
        if ($array['what']) {
            $what = $array['what'];
        }
        else {
            $what = "id";
        }
        if ($array['table']) {
            $order = $this->db->select($array['table'], "order", [$what => $new]);
            $order1 = $this->db->select($array['table'], "order", [$what => $id]);
            $this->db->update($array['table'],["order" => $order1,$what => $new],[$what => $new]);
            $this->db->update($array['table'], ["order" => $order, $what => $id], [$what => $id]);
        }
        else {

            $order = $this->db->select("part_article", "order", [$what => $new]);
            $order1 = $this->db->select("part_article", "order", [$what => $id]);
            $this->db->update("part_article", ["order" => $order1, $what=> $new], [$what=> $new]);
            /*$sql = "UPDATE `t_part_article` SET
              `order` = :order1 WHERE `id` = :new";
            print_r ($array);
            try {
                $query = $this->db->pdo->prepare($sql);
                $query->bindValue(":order1", $order1, \PDO::PARAM_INT);
                $query->bindValue(":new", $new, \PDO::PARAM_INT);
                $query->execute();
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
            $sql = "UPDATE `t_part_article` SET
              `order` = :orde WHERE `id` = :id";*/
            $this->db->update("part_article", ["order" => $order, $what => $id], [$what => $id]);
            /*try {
                $query = $this->db->pdo->prepare($sql);
                $query->bindValue(":orde", $order, \PDO::PARAM_INT);
                $query->bindValue(":id", $id, \PDO::PARAM_INT);
                $query->execute();
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }*/
            //}
        }
    }


    /*
     * Type
     * 1 - звичайний текст
     * 2 - Текст в рамці
     * 3 - Картинка
     * 4 - Цитата (з фоткою та підписом)
     * 5 - слайдер
     * 6 - відео
     * 7 - Таблиця
     * 8 - Посилання на соц. мережі
     * 9 - spec image
     * 10 - аудіо
     * 11 - підпис до спецстаттец
     * 12 - список
     */

    public function add_article($array)
    {
        switch ($array['type']) {
            case "p":
                $id = $this->db->insert("article_text", false, true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 1, "type_id" => $id, "order" => $pos]);
                break;
            case "p_border":
                $id = $this->db->insert("text_border", false, true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 2, "type_id" => $id,"order" => $pos]);
                break;
            case "image":
                $id = $this->db->insert("article_image", false, true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 3, "type_id" => $id,"order" => $pos]);
                break;
            case "quote":
                $id = $this->db->insert("quote", false, true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 4, "type_id" => $id,"order" => $pos]);
                break;
            case "slider":
                $id = $this->db->insert("article_slider", ["article" => $array['id']], true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 5, "type_id" => $id,"order" => $pos]);
                break;
            case "video":
                $id = $this->db->insert("video", false, true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 6, "type_id" => $id,"order" => $pos]);
                break;
            case "table":
                $id = $this->db->insert("article_table", false, true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 7, "type_id" => $id,"order" => $pos]);
                break;
            case "nav":
                $id = $this->db->insert("nav", false, true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 8, "type_id" => $id,"order" => $pos]);
                break;
            case "image-spec":
                $id = $this->db->insert("article_image_spec", false, true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 9, "type_id" => $id,"order" => $pos]);
                break;
            case "audio":
                $id = $this->db->insert("audio", false, true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 10, "type_id" => $id,"order" => $pos]);
                break;
            case "signature":
                $id = $this->db->insert("signature", false, true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 11, "type_id" => $id,"order" => $pos]);
                break;
            case "timeline":
                $id = $this->db->insert("article_timelist", ["id_article" => $array['id']], true);
                $pos = $this->db->select("part_article", "order", ["article_id" => $array['id']]);
                if ($pos) {
                    if (is_array($pos)) {
                        $max = $pos[0]['order'];
                        for ($i = 1; $i <= count($pos); $i++) {
                            if ($pos[$i]['order'] > $max) {
                                $max = $pos[$i]['order'];
                            }
                        }
                        $pos = ++$max;
                    }
                    else {
                        $pos += 1;
                    }
                }
                else {
                    $pos = 1;
                }
                $this->db->insert("part_article", ["article_id" => $array['id'], "type" => 12, "type_id" => $id,"order" => $pos]);
                break;
            default:
                echo "You shell not pass";
                break;
        }
    }

    public function editTable($array) {
        $this->db->update("article_table",["table" => $array["table"]],["id_table" => $array['id']]);
        echo $this->replace;
    }


}

?>