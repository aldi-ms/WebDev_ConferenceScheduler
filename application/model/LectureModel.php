<?php

declare(strict_types = 1);


class LectureModel
{
    public static function createLecture(int $confId) : bool
    {
        Auth::checkAuthentication();

        $startTime = Request::post('datetime_start', true);
        $endTime = Request::post('datetime_end', true);
        $lectureTitle = Request::post('lecture_title', true);

        if (!self::validateLecture($lectureTitle, $startTime, $endTime)) {
            return false;
        }
        $must_visit = 0;
        if (Request::post('must_visit')) {
            $must_visit = 1;
        }

        $startTimestamp = strtotime($startTime, time());
        $endTimestamp = strtotime($endTime, time());

        return self::createLectureInDb($confId, $lectureTitle, $startTimestamp, $endTimestamp, $must_visit);
    }

    public static function validateLecture(string $lectureTitle, string $startTime, string $endTime) : bool
    {
        if (empty($lectureTitle) || empty($startTime) || empty($endTime)) {
            return false;
        }

        return true;
    }

    public static function createLectureInDb(int $confId, string $lectureTitle, int $startTimestamp, int $endTimestamp, int $mustVisit = 0) : bool
    {
        $database = DbFactory::getFactory()->getConnection();

        // write new users data into database
        $sql = "INSERT INTO lectures (title, conference_id, must_visit, start_timestamp, end_timestamp)
                    VALUES (:title, :conference_id, :must_visit ,:start_timestamp, :end_timestamp)";
        $query = $database->prepare($sql);
        $query->execute(array(
            ':title' => $lectureTitle,
            ':conference_id' => $confId,
            ':must_visit' => $mustVisit,
            ':start_timestamp' => $startTimestamp,
            ':end_timestamp' => $endTimestamp));
        $count = $query->rowCount();
        var_dump($count);
        if ($count == 1) {
            return true;
        }

        return false;
    }

    public static function getLecturesByConferenceId(int $id)
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "SELECT *
                  FROM lectures AS l
                 WHERE l.conference_id = :conference_id";

        $query = $database->prepare($sql);
        $query->execute(array(':conference_id' => $id));

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}