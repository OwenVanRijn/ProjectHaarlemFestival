<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once("activityBaseService.php");
require_once("foodactivityService.php");
require_once($root . "/DAL/restaurantDAO.php");
require_once("restaurantTypeLinkService.php");

class restaurantService extends baseService
{
    public function __construct()
    {
        $this->db = new restaurantDAO();
    }

    public function getAll(): array
    {
        return $this->db->get();
    }

    // TODO: maybe convert to pass in restaurant object?
    public function updateRestaurant(int $id, ?string $name, ?string $description, ?int $stars, ?int $seats, ?int $phoneNumber, ?float $price, ?int $locationId, ?string $parking, ?string $website, ?string $menu, ?string $contact): bool
    {
        $update = [
            "id" => $id,
        ];

        if (!is_null($name))
            $update["name"] = $name;

        if (!is_null($description))
            $update["description"] = $description;

        if (!is_null($stars))
            $update["stars"] = $stars;

        if (!is_null($seats))
            $update["seats"] = $seats;

        if (!is_null($phoneNumber))
            $update["phonenumber"] = $phoneNumber;

        if (!is_null($price))
            $update["price"] = $price;

        if (!is_null($locationId))
            $update["locationid"] = $locationId;

        if (!is_null($parking))
            $update["parking"] = $parking;

        if (!is_null($website))
            $update["website"] = $website;

        if (!is_null($menu))
            $update["menu"] = $menu;

        if (!is_null($contact))
            $update["contact"] = $contact;

        return $this->db->update($update);
    }

    public function insertRestaurant(string $name, string $description, int $stars, int $seats, int $phoneNumber, float $price, int $locationId, string $parking, string $website, string $menu, string $contact)
    {
        $insert = [
            "name" => $name,
            "description" => $description,
            "stars" => $stars,
            "seats" => $seats,
            "phonenumber" => $phoneNumber,
            "price" => $price,
            "locationid" => $locationId,
            "parking" => $parking,
            "website" => $website,
            "menu" => $menu,
            "contact" => $contact
        ];

        return $this->db->insert($insert);
    }

    public function getById($id)
    {
        try {
            return $this->db->get([
                "restaurant.id" => $id
            ]);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public function getTimesByRestaurantId($restaurantId)
    {
        try {
            $foodActivityService = new foodactivityService();
            $activities = $foodActivityService->getByRestaurantId($restaurantId);
            return $this->getTimes($activities);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public function getDatesByRestaurantId($restaurantId)
    {
        try {
            $foodActivityService = new foodactivityService();
            $activities = $foodActivityService->getByRestaurantId($restaurantId);
            return $this->getDates($activities);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    function getTimes($foodactivities)
    {
        try {
            if (!is_array($foodactivities)) {
                return null;
            }
            $times = array();

            foreach ($foodactivities as $foodactivity) {
                $startTime = $foodactivity->getActivity()->getStartTime();
                $endTime = $foodactivity->getActivity()->getEndTime();
                $startTimeStr = date_format($startTime, 'H:i');
                $endTimeStr = date_format($endTime, 'H:i');

                $times["$startTimeStr"] = $endTimeStr;
            }
            return $times;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    function getDates($foodactivities)
    {
        try {
            if (!is_array($foodactivities)) {
                return null;
            }
            $dates = array();

            foreach ($foodactivities as $foodactivity) {
                $date = $foodactivity->getActivity()->getDate();
                $date = date_format($date, "Y-m-d");
                $dates["$date"] = $date;
            }
            return $dates;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public function getAllRestaurantsAsStr()
    {
        try {
            $restaurants = $this->db->getArray();
            $restaurantStr = [];
            foreach ($restaurants as $b) {
                $restaurantStr[(string)$b->getId()] = $b->getName();
            }
            return $restaurantStr;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public function getBySearch($searchTerm, $cuisine, $stars3, $stars4)
    {
        try {
            $filter = array();

            $filter = array_merge($filter, array("restaurant.name" => new dbContains($searchTerm)));

            $stars = array();
            if ($stars3) {
                $stars[] = "3";
            }

            if ($stars4) {
                $stars[] = "4";
            }
            if (count($stars) > 0) {
                $filter = array_merge($filter, array("restaurant.stars" => $stars));
            }
            return $this->db->getArray($filter);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public function getBySearchTerm($searchTerm)
    {
        try {
            $restaurants = $this->db->getArray(["restaurant.name" => new dbContains($searchTerm)]);
            return $restaurants;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public function getByStars($stars3, $stars4)
    {
        try {
            if (!$stars3 && !$stars4) {
                return null;
            }

            $stars = array();
            if ($stars3) {
                $stars[] = "3";
            }

            if ($stars4) {
                $stars[] = "4";
            }
            return $this->db->getArray(["restaurant.stars" => $stars]);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}