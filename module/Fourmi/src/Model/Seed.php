<?php

namespace Fourmi\Model;

use Application\Model\HcObj;

class Seed extends HcObj {
    private $_id;
    private $name;
    private $info;
    private $active;
    private $type;
    private $location;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }


    public function fromJson($jsonData)
    {
        $this->_id = $jsonData->_id;
        $this->name = $jsonData->name;
        $this->info = $jsonData->info;
        $this->active = $jsonData->active;
        $this->type = $jsonData->type;
        $this->location = (new SeedLocation())->fromJson($jsonData->location);

        return $this;
    }
}

class SeedLocation extends HcObj {
    private $type;
    private $coordinates = array();

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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

    public function fromJson($jsonData)
    {
        $this->type = $jsonData->type;
        $this->coordinates =[
            "lat" => $jsonData->coordinates[0],
            "long" => $jsonData->coordinates[1]
        ];

        return $this;
    }
}