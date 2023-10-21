<?php

class SiteController extends Controller {

    public function index() {
        $images = array(
            'portfolio-1.jpg',
            'portfolio-2.jpg',
            'portfolio-3.jpg',
            'portfolio-4.jpg'
        );
        $this->viewSite('index', $images);
        // $login = new LoginController();
        // return $login->index();
    }
}