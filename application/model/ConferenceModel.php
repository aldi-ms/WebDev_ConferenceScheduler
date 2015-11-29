<?php

declare(strict_types = 1);


class ConferenceModel
{
    public static function createConference()
    {
        Auth::checkAuthentication();

        $conferenceTitle = Request::post("conference_title", true);
        $conferenceVenueName = Request::post("conference_venue", true);

        if (!self::validateConference($conferenceTitle, $conferenceVenueName)) {
             return false;
        }

        $venue = VenueModel::getVenueByName($conferenceVenueName);

        return self::createConferenceInDb($conferenceTitle, (int)$venue->venue_id, (int)Session::get('user_id'));
    }

    public static function updateConference(int $confId) : bool
    {
        Auth::checkAuthentication();

        $conferenceTitle = Request::post("conference_title", true);
        $conferenceVenueName = Request::post("conference_venue", true);

        if (!self::validateConference($conferenceTitle, $conferenceVenueName)) {
            return false;
        }

        $venue = VenueModel::getVenueByName($conferenceVenueName);

        return self::updateConferenceInDb($confId, $conferenceTitle, (int)$venue->venue_id, (int)Session::get('user_id'));
    }

    public static function createConferenceInDb(string $conferenceTitle, int $conferenceVenueId, int $conferenceOwnerId) : bool
    {
        $database = DbFactory::getFactory()->getConnection();

        // write new users data into database
        $sql = "INSERT INTO conferences (title, venue_id, conference_owner_id)
                    VALUES (:title, :venue_id, :conference_owner_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':title' => $conferenceTitle,
            ':venue_id' => $conferenceVenueId,
            ':conference_owner_id' => $conferenceOwnerId));
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        }

        return false;
    }

    public static function updateConferenceInDb(int $confId, string $conferenceTitle, int $conferenceVenueId, int $conferenceOwnerId) : bool
    {
        $database = DbFactory::getFactory()->getConnection();

        // write conference data into database
        $sql = "UPDATE conferences SET title = :title, venue_id = :venue_id, conference_owner_id = :conference_owner_id
              WHERE conference_id = :conference_id
              LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(
            ':title' => $conferenceTitle,
            ':venue_id' => $conferenceVenueId,
            ':conference_owner_id' => $conferenceOwnerId,
            ':conference_id' => $confId));

        $count = $query->rowCount();
        echo $count;
        if ($count == 1) {
            return true;
        }

        return false;
    }

    public static function deleteConferenceFromDB(int $conferenceId)
    {
        $database = DbFactory::getFactory()->getConnection();

        // write new users data into database
        $sql = "DELETE FROM conferences WHERE conference_id = :conference_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':conference_id' => $conferenceId));
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        }

        return false;
    }

    public static function getConferenceById(int $id)
    {
        $database = DbFactory::getFactory()->getConnection();

        $sql = "SELECT c.conference_id, c.title AS conference_title, u.user_name AS created_by_user_name,
                  u.user_email AS created_by_user_email, v.venue_name, l.title AS lecture_title, lu.user_name AS speaker_name,
                  l.start_timestamp AS lecture_start, l.end_timestamp AS lecture_end
                  FROM conferences AS c
                 INNER JOIN users AS u ON u.user_id = c.conference_owner_id
                 INNER JOIN venues AS v ON v.venue_id = c.venue_id
                 LEFT JOIN lectures AS l ON l.conference_id = c.conference_id
                 LEFT JOIN users AS lu ON l.speaker_id = lu.user_id
                 WHERE c.conference_id = :conference_id AND c.deleted = 0";

        $query = $database->prepare($sql);
        $query->execute(array(':conference_id' => $id));

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getConferenceByTitle(string $title)
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "SELECT conference_id, conference_owner_id, title, venue_id
                  FROM conferences
                 WHERE title = :title AND deleted = 0
                 LIMIT 1";

        $query = $database->prepare($sql);
        $query->execute(array(':title' => $title));

        return $query->fetch();
    }

    public static function getAllConferences()
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "SELECT c.title, c.conference_id, u.user_name, u.user_id, v.venue_name
                  FROM conferences AS c
                 INNER JOIN users AS u ON u.user_id = c.conference_owner_id
                 INNER JOIN venues AS v ON v.venue_id = c.venue_id
                 WHERE c.deleted = 0";

        $query = $database->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function validateConference($confTitle, $venueName)
    {
        if (empty($confTitle) || empty($venueName)) {
            Session::push('feedback_negative', Text::get('CONFERENCE_FIELD_EMPTY'));
            return false;
        }

        // no conference by this title should exist
        if (ConferenceModel::getConferenceByTitle($confTitle)) {
            Session::push('feedback_negative', Text::get('CONFERENCE_ALREADY_EXISTS'));
            return false;
        }

        if (!VenueModel::venueExists($venueName)) {
            if (!VenueModel::createVenueInDb($venueName)) {
                Session::push('feedback_negative', Text::get('UNKNOWN_ERROR'));
                return false;
            }
        }

        return true;
    }
}