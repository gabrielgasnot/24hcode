<?php

namespace Retournejson\Model;

use Application\Model\HcObj;

class Starship extends HcObj {
    protected $id;
    protected $name;
    protected $model;
    protected $starship_class;
    protected $manufacturer;
    protected $cost_in_credits;
    protected $length;
    protected $crew;
    protected $passengers;
    protected $max_atmosphering_speed;
    protected $hyperdrive_rating;
    protected $MGLT;
    protected $cargo_capacity;
    protected $consumables;
    protected $films;
    protected $pilots;
    protected $url;
    protected $created;
    protected $edited;

    private $data = array();

    // GETTER
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getModel() {
        return $this->model;
    }
    public function getStarship_class() {
        return $this->starship_class;
    }
    public function getManufacturer() {
        return $this->manufacturer;
    }
    public function getCost_in_credits() {
        return $this->cost_in_credits;
    }
    public function getLength() {
        return $this->length;
    }
    public function getCrew() {
        return $this->crew;
    }
    public function getPassengers() {
        return $this->passengers;
    }
    public function getMax_atmosphering_speed() {
        return $this->max_atmosphering_speed;
    }
    public function getHyperdrive_rating() {
        return $this->hyperdrive_rating;
    }
    public function getMGLT() {
        return $this->MGLT;
    }
    public function getCargo_capacity() {
        return $this->cargo_capacity;
    }
    public function getConsumables() {
        return $this->consumables;
    }
    public function getFilms() {
        return $this->films;
    }
    public function getPilots() {
        return $this->pilots;
    }
    public function getUrl() {
        return $this->url;
    }
    public function getCreated() {
        return $this->created;
    }
    public function getEdited() {
        return $this->edited;
    }

    // SETTER
    public function setId($value) {
        $this->id = $value;
    }
    public function setName($value) {
        $this->name = $value;
    }
    public function setModel($value) {
        $this->model = $value;
    }
    public function setStarship_class($value) {
        $this->starship_class = $value;
    }
    public function setManufacturer($value) {
        $this->manufacturer = $value;
    }
    public function setCost_in_credits($value) {
        $this->cost_in_credits = $value;
    }
    public function setLength($value) {
        $this->length = $value;
    }
    public function setCrew($value) {
        $this->crew = $value;
    }
    public function setPassengers($value) {
        $this->passengers = $value;
    }
    public function setMax_atmosphering_speed($value) {
        $this->max_atmosphering_speed = $value;
    }
    public function setHyperdrive_rating($value) {
        $this->hyperdrive_rating = $value;
    }
    public function setMGLT($value) {
        $this->MGLT = $value;
    }
    public function setCargo_capacity($value) {
        $this->cargo_capacity = $value;
    }
    public function setConsumables($value) {
        $this->consumables = $value;
    }
    public function setFilms($value) {
        $this->films = $value;
    }
    public function setPilots($value) {
        $this->pilots = $value;
    }
    public function setUrl($value) {
        $this->url = $value;
    }
    public function setCreated($value) {
        $this->created = $value;
    }
    public function setEdited($value) {
        $this->edited = $value;
    }

    public function initFromJson($jsonData)
    {
        $this->name = $jsonData->name;
        $this->model = $jsonData->model;
        $this->starship_class = $jsonData->starship_class;
        $this->manufacturer = $jsonData->manufacturer;
        $this->cost_in_credits = $jsonData->cost_in_credits;
        $this->length = $jsonData->length;
        $this->crew = $jsonData->crew;
        $this->passengers = $jsonData->passengers;
        $this->max_atmosphering_speed = $jsonData->max_atmosphering_speed;
        $this->hyperdrive_rating = $jsonData->hyperdrive_rating;
        $this->MGLT = $jsonData->MGLT;
        $this->cargo_capacity = $jsonData->cargo_capacity;
        $this->consumables = $jsonData->consumables;
        $this->films = $jsonData->films;
        $this->pilots = $jsonData->pilots;
        $this->url = $jsonData->url;
        $this->created = $jsonData->created;
        $this->edited = $jsonData->edited;
    }

    public function getDataArray($bId = false)
    {
        if($bId) {
            $this->data["id"] = $this->__get("id");
        }
        
        $this->data["name"] = $this->__get("name");
        $this->data["model"] = $this->__get("model");
        $this->data["starship_class"] = $this->__get("starship_class");
        $this->data["manufacturer"] = $this->__get("manufacturer");
        $this->data["cost_in_credits"] = $this->__get("cost_in_credits");
        $this->data["length"] = $this->__get("length");
        $this->data["crew"] = $this->__get("crew");
        $this->data["passengers"] = $this->__get("passengers");
        $this->data["max_atmosphering_speed"] = $this->__get("max_atmosphering_speed");
        $this->data["hyperdrive_rating"] = $this->__get("hyperdrive_rating");
        $this->data["MGLT"] = $this->__get("MGLT");
        $this->data["cargo_capacity"] = $this->__get("cargo_capacity");
        $this->data["consumables"] = $this->__get("consumables");
        $this->data["films"] = $this->__get("films");
        $this->data["pilots"] = $this->__get("pilots");
        $this->data["url"] = $this->__get("url");
        $this->data["created"] = $this->__get("created");
        $this->data["edited"] = $this->__get("edited");

        return $this->data;
    }


    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->model = !empty($data['model']) ? $data['model'] : null;
        $this->starship_class = !empty($data['starship_class']) ? $data['starship_class'] : null;
        $this->manufacturer = !empty($data['manufacturer']) ? $data['manufacturer'] : null;
        $this->cost_in_credits = !empty($data['cost_in_credits']) ? $data['cost_in_credits'] : null;
        $this->length = !empty($data['length']) ? $data['length'] : null;
        $this->crew = !empty($data['crew']) ? $data['crew'] : null;
        $this->passengers = !empty($data['passengers']) ? $data['passengers'] : null;
        $this->max_atmosphering_speed = !empty($data['max_atmosphering_speed']) ? $data['max_atmosphering_speed'] : null;
        $this->hyperdrive_rating = !empty($data['hyperdrive_rating']) ? $data['hyperdrive_rating'] : null;
        $this->MGLT = !empty($data['MGLT']) ? $data['MGLT'] : null;
        $this->cargo_capacity = !empty($data['cargo_capacity']) ? $data['cargo_capacity'] : null;
        $this->consumables = !empty($data['consumables']) ? $data['consumables'] : null;
        $this->films = !empty($data['films']) ? $data['films'] : null;
        $this->pilots = !empty($data['pilots']) ? $data['pilots'] : null;
        $this->url = !empty($data['url']) ? $data['url'] : null;
        $this->created = !empty($data['created']) ? $data['created'] : null;
        $this->edited         = !empty($data['edited']) ? $data['edited'] : null;
    }
}