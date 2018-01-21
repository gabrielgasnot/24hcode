<?php

namespace Fourmi\Tools;

use Application\Model\HcObj;
use Fourmi\Model\Calcul;

class TrackRecord extends HcObj
{
    private $_id;
    private $__v;
    private $lat;
    private $lon;
    private $timestamp;
    private $track_id;
    private $previousPosition;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getV()
    {
        return $this->__v;
    }

    /**
     * @param mixed $_v
     */
    public function setV($_v)
    {
        $this->__v = $_v;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * @param mixed $lon
     */
    public function setLon($lon)
    {
        $this->lon = $lon;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getTrack_id()
    {
        return $this->track_id;
    }

    /**
     * @param mixed $track_id
     */
    public function setTrack_id($track_id)
    {
        $this->track_id = $track_id;
    }

    public function getSpeed()
    {
        $speedPerHour = 0;

        if (!is_null($this->previousPosition)) {
            $d = Calcul::getDistanceM($this->previousPosition->lat, $this->previousPosition->lon, $this->lat, $this->lon);
            $t = Calcul::duree($this->previousPosition->timestamp, $this->timestamp);

            if($t == 0)
            {
                return $speedPerHour;
            }
            $speedPerHour = $d / $t * 3600;
        }

        return $speedPerHour;
    }

    public function fromJson($json, $previous)
    {
        $this->_id = $json->_id;
        $this->__v = $json->__v;
        $this->lat = $json->lat;
        $this->lon = $json->lon;
        $this->timestamp = $json->timestamp;
        $this->track_id = $json->trackId;
        $this->previousPosition = $previous;

        return $this;
    }
}