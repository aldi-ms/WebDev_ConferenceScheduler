<?php

declare(strict_types = 1);

class ConferenceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $dbData = ConferenceModel::getAllConferences();
        $this->view->render('conference/index', $dbData);
    }

    public function create()
    {
        $this->view->render('conference/create');
    }

    public function create_action()
    {
        $success = ConferenceModel::createConference();

        if ($success) {
            Redirect::to('conference/index');
        } else {
            Redirect::to('conference/create');
        }
    }
}