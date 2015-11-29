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

    public static function getConferenceByTitle(string $title)
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "SELECT conference_id, conference_owner_id, title, venue_id, deleted
                  FROM conferences
                 WHERE title = :title
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