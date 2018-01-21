<?php

namespace Fourmi\Tools;

use Application\Model\HcObj;


class ScoutReport extends HcObj {

    const SCOUT_REPORT_SENS_ALLER = "aller";
    const SCOUT_REPORT_SENS_RETOUR = "retour";

    private $sens;
    private $arrayStreets = array();
    private $nodes = array();
    private $ways = array();

    /**
     * @return mixed
     */
    public function getSens()
    {
        return $this->sens;
    }

    /**
     * @param mixed $sens
     */
    public function setSens($sens)
    {
        $this->sens = $sens;
    }

    /**
     * @return array
     */
    public function getArrayStreets()
    {
        return $this->arrayStreets;
    }

    /**
     * @param array $arrayStreets
     */
    public function setArrayStreets($arrayStreets)
    {
        $this->arrayStreets = $arrayStreets;
    }

    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param array $nodes
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @return array
     */
    public function getWays()
    {
        return $this->ways;
    }

    /**
     * @param array $ways
     */
    public function setWays($ways)
    {
        $this->ways = $ways;
    }

    public function fromJson($sens, $jsonData)
    {
        $this->sens = $sens;
        $this->arrayStreets = explode(",", trim($jsonData->summary));
        $this->nodes = ScoutNode::buildArrayStepFromAnnotations($jsonData->annotation);

        return $this;
    }

    /**
     * Build a string of node.
     *
     * @return string
     */
    public function getAllNodes()
    {
        $strNodes = "[out:json];(";

        foreach($this->nodes as $node)
        {
            $strNodes .=  "node(" . $node->node . ");";
        }

        $strNodes .= ");";

        return $strNodes;
    }
}

class ScoutNode extends HcObj {
    private $node;
    private $distance;
    private $duration;
    private $coordinates = array();
    private $notes = array();
    private $maxSpeed;

    /**
     * @return mixed
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param mixed $node
     */
    public function setNode($node)
    {
        $this->node = $node;
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param mixed $distance
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public static function buildArrayStepFromAnnotations($annotations)
    {
        $arrResult = array();
        // Node 0 = depart.
        foreach($annotations->nodes as $id => $node)
        {
            if($id == 0)
            {
                $distance = 0;
                $duration = 0;
            }
            else {
                // Decrement lié à Node 0 = départ.
                $reportId = $id - 1;
                $distance = $annotations->distance[$reportId];
                $duration = $annotations->duration[$reportId];
            }

            $arrResult[$node] = new ScoutNode(array(
                "node" => $node,
                "distance" => $distance,
                "duration" => $duration
            ));
        }
        return $arrResult;
    }

    private function checkNotes($key) {
        return array_key_exists($key);
    }

    /**
     * @return array
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param array $coordinates
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    }

    /**
     * @return array
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param array $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return mixed
     */
    public function getMaxSpeed()
    {
        return $this->maxSpeed;
    }

    /**
     * @param mixed $maxSpeed
     */
    public function setMaxSpeed($maxSpeed)
    {
        $this->maxSpeed = $maxSpeed;
    }

    public function isSlowArea() {
        return $this->checkNotes(Highway::SLOW);
    }

    public function isStopArea() {
        return $this->checkNotes(Highway::STOP);
    }

    public function isDriveable() {
        return $this->checkNotes(Highway::NO_GO_ZONE);
    }
}

class ScoutWay extends HcObj {
    private $maxSpeed;
    private $relationsCoordinates = array();

    /**
     * @return mixed
     */
    public function getMaxSpeed()
    {
        return $this->maxSpeed;
    }

    /**
     * @param mixed $maxSpeed
     */
    public function setMaxSpeed($maxSpeed)
    {
        $this->maxSpeed = $maxSpeed;
    }

    /**
     * @return array
     */
    public function getRelationsCoordinates()
    {
        return $this->relationsCoordinates;
    }

    /**
     * @param array $relationsCoordinates
     */
    public function setRelationsCoordinates($relationsCoordinates)
    {
        $this->relationsCoordinates = $relationsCoordinates;
    }

    public function hasNode(ScoutNode $node) {
        foreach($this->relationsCoordinates as $coord) {
            if($node->coordinates["lat"] == $coord["lat"] && $node->coordinates["long"] == $coord["long"]) {
                return true;
            }
        }
        return false;
    }
}