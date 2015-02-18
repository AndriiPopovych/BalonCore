<?php

    /**
     * Class Control
     * Add in view control's elements
     */
    class Control {

        function __construct() {
            if (!\Balon\System\User::trueAdmin()) return false;
        }

        /**
         * This control printing plus in view. On click to this plus
         * script add one rows to table $table in database
         *
         * @param String $table - name table
         * @param bool $parent_id parent_id for new element
         * @param bool $return - "return" or "echo" this control
         * @return String
         */
        static public function add($table,$parent_id = Array(),$return = false) {
            if (!\Balon\System\User::trueAdmin()) return false;
            $a = '<i class="icon-plus add" data-table="'.$table.'"';
            //if ($parent_id) $a.= 'data-parent_id="'.$parent_id;
            if ($parent_id) {
                $parent_id = json_encode($parent_id);
                $parent_id = str_replace('"',"'",$parent_id);
                $a.= "data-data=\"".$parent_id."\"";
            }
            $a .= '></i>';
            $text = '<div class="control_block min left">'.$a.'</div>';
            if ($return) return $text;
            else echo $text;
        }





        /**
         * This control printing pencil in view. On click to this pencil
         * opening modal window with edit form.
         *
         * @param String $table - name table
         * @param bool $id id element in table (from database)
         * @param bool $return - "return" or "echo" this control
         * @return String
         */
        static public function edit($table,$id,$return = false) {
            if (!\Balon\System\User::trueAdmin()) return false;
            $control = '<div class="control_block min right" >';
            $control .= '<i class="icon-edit" data-table="'.$table.'" data-id="'.$id.'"></i>';
            $control .= '</div>';
            if ($return) {
                return $control;
            }
            else {
                echo $control;
            }
        }

        static public function remove($table,$id,$return = false,$ties = Array()) {
            if (!\Balon\System\User::trueAdmin()) return false;
            if (is_array($ties)) {
                $parent_id = json_encode($ties);
                $parent_id = str_replace('"',"'",$parent_id);
                $a = "data-data=\"".$parent_id."\"";
            }
            $control = '<div class="control_block min left" >';
            $control .= '<i class="icon-remove remove" data-table="'.$table.'" data-id="'.$id.'" '.$a.'></i>';
            $control .= '</div>';
            if ($return) return $control;
            else echo $control;
        }

        static public function remove_add($table,$id,$return = false,$ties = Array(),$parent,$position) {
            if (!\Balon\System\User::trueAdmin()) return false;
            if (is_array($ties)) {
                $parent_id = json_encode($ties);
                $parent_id = str_replace('"',"'",$parent_id);
                $a = "data-data=\"".$parent_id."\"";
            }
            if ($parent) {
                $parent = json_encode($parent);
                $parent = str_replace('"',"'",$parent);
                $as= "data-data=\"".$parent."\"";
            }
            $tag = '<i class="icon-plus add" data-table="'.$table['add'].'" '.$as.'></i>';
            $control = '<div class="control_block min left" >';
            $control .= $tag;
            if ($position) {
                if ($position[0]) {
                    $control .= '<i class="icon-left position" data-table="'.$table['position'].'" data-id="'.$position[1].'" data-new="'.$position[0].'"></i>';
                }
                if ($position[2]) {
                    $control .= '<i class="icon-right position" data-table="'.$table['position'].'" data-id="'.$position[1].'" data-new="'.$position[2].'"></i>';
                }
            }
            $control .= '<i class="icon-remove remove" data-table="'.$table['remove'].'" data-id="'.$id.'" '.$a.' ></i>';
            $control .= '</div>';
            if ($return) return $control;
            else echo $control;
        }

        static public function editRemove($table,$id,$return = false) {
            if (!\Balon\System\User::trueAdmin()) return false;
            $control = '<div class="control_block min right" >';
            $control .= '<i class="icon-edit" data-table="'.$table.'" data-id="'.$id.'"></i>';
            $control .= '<i class="icon-remove remove" data-table="'.$table.'" data-id="'.$id.'"></i>';
            $control .= '</div>';
            if ($return) return $control;
            else echo $control;
        }

        static public function editRemoveVisibility($table,$id,$visibility,$return = false) {
            if (!\Balon\System\User::trueAdmin()) return false;
            $control = '<div class="control_block min right" >';
            $control .= '<i class="icon-edit" data-table="'.$table.'" data-id="'.$id.'"></i>';
            if ($visibility) {
                $control .= '<i class="icon-eye-open visibility" data-table="'.$table.'" data-id="'.$id.'" data-visibility="1"></i>';
            }
            else {
                $control .= '<i class="icon-eye-close visibility" data-table="'.$table.'" data-id="'.$id.'" data-visibility="0"></i>';
            }
            $control .= '<i class="icon-remove remove" data-table="'.$table.'" data-id="'.$id.'"></i>';
            $control .= '</div>';
            if ($return) return $control;
            else echo $control;
        }


        /**
         * This function return or print control panel to the element
         *
         * @param array $array - Arrays where the keys are the controllers that you want to insert
         * @param string $table - name table
         * @param int $id - id row in $table. (for edit,remove,change visibility)
         * @param int $visibility - status visibility element (for visibility)
         * @param int $return - return html-text or print on screen
         * @return bool|string
         */

        static public function controllers($array = Array(),$table = "",$id = 0,$visibility = 0,$return = 0, $ties = Array(), $position = Array()) {
            //if not admin return false
            if (!\Balon\System\User::trueAdmin()) return false;
            // in this variable been result this function (html)
            $control = "";
            $old_table = $table;
            if (is_array($id)) {
                $what = array_keys($id)[0];
                $id = array_values($id)[0];
            }
            if ((isset($array['add']) && count($array) > 1) || (empty($array['add']) && count($array) > 0)) {
                $control .= '<div class="control_block min right">';
                foreach ($array as $key => $value) {
                    if (is_array($value)) {
                        $table = $value[1];
                        $value = $value[0];
                    }
                    switch ($value) {
                        case "edit":
                            $control .= '<i class="icon-edit" data-table="'.$table.'" data-id="'.$id.'" data-what="'.$what.'"></i>';
                            break;
                        case "remove":
                            if (is_array($ties)) {
                                $parent_id = json_encode($ties);
                                $parent_id = str_replace('"',"'",$parent_id);
                                $a = "data-data=\"".$parent_id."\"";
                            }
                            $control .= '<i class="icon-remove remove" data-table="'.$table.'" data-id="'.$id.'"  data-what="'.$what.'" '.$a.'></i>';
                            break;
                        case "visibility":
                            if ($visibility) {
                                $control .= '<i class="icon-eye-open visibility" data-table="'.$table.'" data-id="'.$id.'" data-what="'.$what.'" data-visibility="1"></i>';
                            }
                            else {
                                $control .= '<i class="icon-eye-close visibility" data-table="'.$table.'" data-id="'.$id.'"  data-what="'.$what.'" data-visibility="0"></i>';
                            }
                            break;
                        case "position":
                            if ($position) {
                                if ($position[3]) {
                                    $whats = $position[3];
                                }
                                else {
                                    $whats = "id";
                                }
                                if ($position[0]) {
                                    $control .= '<i class="icon-left position" data-table="'.$table.'" data-what="'.$whast.'"  data-id="'.$position[1].'" data-new="'.$position[0].'"></i>';
                                }
                                if ($position[2]) {
                                    $control .= '<i class="icon-right position" data-table="'.$table.'" data-what="'.$whats.'"  data-id="'.$position[1].'" data-new="'.$position[2].'"></i>';
                                }
                            }
                            break;
                    }
                    $table = $old_table;
                }
                $control .= '</div>';
                if ($return) return $control;
                else echo $control;
                return true;
            }

        }
    }

?>