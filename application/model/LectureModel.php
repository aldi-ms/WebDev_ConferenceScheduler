<?php

declare(strict_types = 1);


class LectureModel
{
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