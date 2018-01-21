<?php

namespace Fourmi\Tools;

use Application\Model\HcObj;

class Scout extends HcObj {
    const ROUTE_API_URL = "https://router.project-osrm.org/trip/v1/driving/";
    const ROUTE_OPTIONS = "?steps=true&annotations=true";

    private $startCoordinates;
    private $endCoordinates;

    public function __construct(array $startCoordinates, array $endCoordinates)
    {
        $this->startCoordinates = $startCoordinates;
        $this->endCoordinates = $endCoordinates;
    }

    private function mapTrip()
    {
        $fullQuery = self::ROUTE_API_URL
            . $this->startCoordinates["long"] . "," . $this->startCoordinates["lat"]
            . ";"
            . $this->endCoordinates["long"] . "," . $this->endCoordinates["lat"]
            . self::ROUTE_OPTIONS;

        $ch = CurlyCrawler::init($fullQuery);
        return CurlyCrawler::call($ch);
    }

    public function generateReports()
    {
        $json = $this->mapTrip();
        $reports[] = array();
        $sens = ScoutReport::SCOUT_REPORT_SENS_ALLER;

        foreach($json->trips as $trip) {
            foreach($trip->legs as $report) {
                $reports[$sens] = (new ScoutReport())->fromJSON($sens, $report);
                $sens =  ScoutReport::SCOUT_REPORT_SENS_RETOUR;
            }
        }

        return $reports;
    }
}