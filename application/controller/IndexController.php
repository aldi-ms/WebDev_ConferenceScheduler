<?php

declare(strict_types = 1);

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * URL/index
     */
    public function index()
    {
        $this->view->render('index/index');
    }
}