<?php
/**
 * Created by PhpStorm.
 * User: Balon
 * Date: 17.02.2015
 * Time: 01:44
 */

namespace View;
use Balon\System;

class News extends System\View{

    function __construct()
    {
        // TODO: Implement __construct() method.
    }

    public function loadNews($data)
    {
        //TODO: Потрібно реалізувати слайдер в середині новини
        $this->loadHead();
        $this->loadHeader();
        $this->loadContent();
        include('view/content/post.php');
        include('view/sidebar.php');
        include('view/sidebar/advice.php');
        include('view/sidebar/video.php');
        include('view/sidebar/recklama.php');
        include('view/sidebar/blog.php');
        $this->loadModal();
        $this->loadFooter();
    }

    public function loadList($data) {
        $this->loadHead();
        $this->loadHeader();
        $this->loadContent();
        include('view/content/news.php');
        include('view/sidebar.php');
        include('view/sidebar/advice.php');
        include('view/sidebar/video.php');
        include('view/sidebar/recklama.php');
        include('view/sidebar/blog.php');
        $this->loadModal();
        $this->loadFooter();
    }
}