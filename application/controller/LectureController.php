<?php

declare(strict_types = 1);


class LectureController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create(string $confId)
    {
        if (!empty($confId)) {
            $conference = ConferenceModel::getConferenceById((int)$confId)[0];
            $data = array(
                "confId" => $confId,
                "confName" => $conference['conference_title']
            );
            $this->view->render('lecture/create', $data);
        }
    }

    public function create_action(string $confId)
    {
        $success = LectureModel::createLecture((int)$confId);
        if ($success) {
            Redirect::to('conference/show/'.$confId);
        } else {
            Redirect::to('conference/index');
        }
    }

    public function edit(string $lectureId)
    {

    }

    public function delete(string $lectureId)
    {

    }
}