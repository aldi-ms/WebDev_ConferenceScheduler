<?php

declare(strict_types = 1);


class VenueModel
{
    public static function venueExists(string $venueName) : bool
    {
        $database = DbFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT venue_id FROM venues WHERE venue_name = :venue_name LIMIT 1");
        $query->execute(array(':venue_name' => $venueName));
        if ($query->rowCount() == 0) {
            return false;
        }

        return true;
    }

    public static function getVenueByName(string $venueName)
    {
        $database = DbFactory::getFactory()->getConnection();
        $sql = "SELECT venue_id, venue_name
                  FROM venues
                 WHERE venue_name = :venue_name
                 LIMIT 1";

        $query = $database->prepare($sql);
        $query->execute(array(':venue_name' => $venueName));

        return $query->fetch();
    }

    public static function createVenueInDb(string $venueName)
    {
        $database = DbFactory::getFactory()->getConnection();

        // write new users data into database
        $sql = "INSERT INTO venues (venue_name)
                    VALUES (:venue_name)";
        $query = $database->prepare($sql);
        $query->execute(array(':venue_name' => $venueName));
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        }

        return false;
    }
}