<?php

namespace Fourmi\Model;

use Application\Model\HcObj;
use Fourmi\Tools\CurlyCrawler;
use Fourmi\Tools\ScoutReport;

class Fourmi extends HcObj
{
    private $seedStartId;
    private $seedEndId;
    private $tripAller;
    private $tripRetour;
    private $currentSpeed;
    const SPEED_STEP = 5;
    private $authorization;

    private function initCurl($url)
    {
        $ch = CurlyCrawler::init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $this->authorization));

        return $ch;
    }

    /**
     * @return mixed
     */
    public function getTripAller()
    {
        return $this->tripAller;
    }

    /**
     * @param mixed $tripAller
     */
    public function setTripAller($tripAller)
    {
        $this->tripAller = $tripAller;
    }

    /**
     * @return mixed
     */
    public function getTripRetour()
    {
        return $this->tripRetour;
    }

    /**
     * @param mixed $tripRetour
     */
    public function setTripRetour($tripRetour)
    {
        $this->tripRetour = $tripRetour;
    }

    public function __construct($seedStart, $seedEnd, $tripAller, $tripRetour, $auth)
    {
        $this->seedStartId = $seedStart;
        $this->seedEndId = $seedEnd;
        $this->tripAller = $tripAller;
        $this->tripRetour = $tripRetour;
        $this->currentSpeed = 0;
        $this->authorization = $auth;
    }

    private function getTimestamp(\DateTime $date)
    {
        return str_replace('+00:00', 'Z', gmdate('c', strtotime($date->format('c'))));
    }

    public function go($sens = null)
    {
        $dateStart = new \DateTime();
        $timeStamp = $this->getTimestamp($dateStart);

        if ($sens == ScoutReport::SCOUT_REPORT_SENS_ALLER) {
            $currentTrip = $this->tripAller;
        } else {
            $currentTrip = $this->tripRetour;
        }


        $myId = $this->getMyInfo();

        $createTrack = "https://f24h2018.herokuapp.com/api/tracks";
        $ch = $this->initCurl($createTrack);
        $fields = array(
            "name" => "useless",
            "info" => "none",
            "startSeedId" => $this->seedStartId,
            "endSeedId" => $this->seedEndId
        );

        $fields_string = http_build_query($fields);

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        $track = CurlyCrawler::call($ch);

        $ch = $this->initCurl($createTrack);
        $position ="https://f24h2018.herokuapp.com/api/positions/bulk";

        $ch = $this->initCurl($position);
        $fields = array(
            "trackId" => $track->trackId,
            "positions" => array()
        );

        foreach ($currentTrip as $node) {
            $fields["positions"][] = array(
                "lat" => $node->coordinates["lat"],
                "lon" => $node->coordinates["long"],
                "timestamp" => $timeStamp
            );

            $dateStart->add("P1S");
            $timeStamp = $this->getTimestamp($dateStart);
        }


        $fields_string = http_build_query($fields);

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        CurlyCrawler::call($ch);

        $endTrack = str_replace(":id", $myId,"https://f24h2018.herokuapp.com/api/tracks/:id/end");

        $fields = array(
            "name" => "useless",
            "info" => "fin"
        );

        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);


    }

    private function getMyInfo()
    {
        $str = "https://f24h2018.herokuapp.com/api/users/me";
        $ch = $this->initCurl($str);
        $result = CurlyCrawler::call($ch);

        return $result->_id;
    }

}