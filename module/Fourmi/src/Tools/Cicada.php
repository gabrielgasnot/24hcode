<?php

namespace Fourmi\Tools;

use Application\Model\HcObj;

class Cicada extends HcObj
{
    const CICADA_REPORT_SOURCE = "https://f24h2018.herokuapp.com/api/tracks/otherTeams";
    const CICADA_REPORT_TRACE = "https://f24h2018.herokuapp.com/api/tracks/:id/positions";
    const CICADA_WAY = "http://overpass.openstreetmap.fr/api/interpreter";
    const CICADA_WAY_END = "(._;>;);out;";
    const CICADA_WRITE_ANALYSES = "https://f24h2018.herokuapp.com/api/analyses";

    private $authorization;
    private $table;

    public function __construct($authorization, CicadaTable $table)
    {
        $this->authorization = $authorization;
        $this->table = $table;
    }

    private function initCurl($url)
    {
        $ch = CurlyCrawler::init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $this->authorization));

        return $ch;
    }

    private function getTracks()
    {
        $ch = $this->initCurl(self::CICADA_REPORT_SOURCE);
        return CurlyCrawler::call($ch);
    }

    private function getTrack($id)
    {
        $fullQuery = str_replace(":id", $id, self::CICADA_REPORT_TRACE);
        $ch = $this->initCurl($fullQuery);
        return CurlyCrawler::call($ch);
    }

    private function getWay($lat, $lon)
    {
        $fullQuery = self::CICADA_WAY . "(way(around:5," . $lat . "," . $lon . ")[highway];);" . self::CICADA_WAY_END;
        $ch = $this->initCurl($fullQuery);
        return CurlyCrawler::call($ch);
    }

    private function storeInMemory($json)
    {
        foreach ($json as $track) {
            $dbTrack = $this->table->getByTrack($track->getId());
            if (is_null($dbTrack)) {
                $memo = new CicadaMemory(array(
                    "track_id" =>$track->getId(),
                    "done" => false
                ));
                $this->table->saveMemory($memo);
            }
        }
    }

    private function getFromMemory()
    {
        $tracks = $this->table->fetchAllNotDone();

        if (empty($tracks)) {
            $json = $this->getTracks();
            $this->storeInMemory($json);
        }

        return $this->table->fetchAllNotDone();
    }

    public function analyseTracks()
    {
        $tracks = $this->getFromMemory();

        foreach ($tracks as $track) {
            $jsonTrack = $this->getTrack($track->track_id);

            $array = array();
            $previous = null;
            foreach ($jsonTrack as &$recordTrack) {
                $array[] = (new TrackRecord())->fromJson($recordTrack, $previous);
                $previous = $recordTrack;
            }

            $this->analyseTrack($array);
        }
    }

    private function analyseTrack($array)
    {
        foreach ($array as $trackRecord) {
            $json = $this->getWay($trackRecord->lat, $trackRecord->lon);
            $maxSpeed = 50;
            $highway = "tertiary";
            $dureeStop = 0;

            if(!empty($json)) {
                foreach ($json->features as $feat) {
                    if (property_exists($feat->properties, "maxspeed")) {
                        $maxSpeed = $feat->properties->maxspeed;
                    }
                    if (property_exists($feat->properties, "highway")) {
                        $highway = $feat->properties->highway;
                    }
                }
            }
            $currentSpeed = $trackRecord->getSpeed();

            if($currentSpeed > $maxSpeed)
            {
                $ch = $this->initCurl(self::CICADA_WRITE_ANALYSES);

                $fields = array(
                    "trackId" => $trackRecord->track_id,
                    "positionId" => $trackRecord->_id,
                    "description" => "dépassement de vitesse : " . $currentSpeed . ">" . $maxSpeed
                );

                $fields_string = "";
                foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
                rtrim($fields_string, '&');

                curl_setopt($ch,CURLOPT_POST, count($fields));
                curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

                CurlyCrawler::call($ch);
            }

            $isSlow = in_array($highway, Highway::SLOW_ZONE);
            $isStop = in_array($highway, Highway::STOP_SIGN);
            if($isSlow && ($currentSpeed > 25 || $currentSpeed < 10))
            {
                $ch = $this->initCurl(self::CICADA_WRITE_ANALYSES);

                $fields = array(
                    "trackId" => $trackRecord->track_id,
                    "positionId" => $trackRecord->_id,
                    "description" => "Vitesse inadequate : " . $currentSpeed . ", attendu entre 10 et 25 kmh"
                );

                $fields_string = "";
                foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
                rtrim($fields_string, '&');

                curl_setopt($ch,CURLOPT_POST, count($fields));
                curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

                CurlyCrawler::call($ch);
            }


            $memo = $this->table->get($trackRecord->track_id);
            $memo->done = true;
            $this->table->saveMemory($memo);

            /*
            if($isStop && ($currentSpeed > 0) && $dureeStop < 2)
            {
                $ch = $this->initCurl(self::CICADA_WRITE_ANALYSES);

                $fields = array(
                    "trackId" => $trackRecord->track_id,
                    "positionId" => $trackRecord->_id,
                    "description" => "Vitesse inadequate : " . $currentSpeed . ", attendu entre 10 et 25 kmh"
                );

                $fields_string = "";
                foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
                rtrim($fields_string, '&');

                CurlyCrawler::call($ch);
            }
            else {
                $dureeStop += 1;
            }
            */
        }
    }
}

