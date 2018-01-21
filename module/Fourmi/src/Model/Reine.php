<?php

namespace Fourmi\Model;

use Application\Model\HcObj;
use Fourmi\Tools\Scout;

class Reine extends HcObj {
    private $arrayDistance = [
        "pas trop loin" => 1,
        "après lycée Sud" => 2
    ];

    const MAX_WEIGHT = 1000;

    private $knowSeeds = array();

    /**
     * Reine constructor.
     * @param array $knowSeeds
     */
    public function __construct(array $knowSeeds)
    {
        $this->knowSeeds = $knowSeeds;
    }

    /**
     * @return array
     */
    public function getKnowSeeds()
    {
        return $this->knowSeeds;
    }

    /**
     * @param array $knowSeeds
     */
    public function setKnowSeeds($knowSeeds)
    {
        $this->knowSeeds = $knowSeeds;
    }

    public function getHomeSeed()
    {
        foreach($this->knowSeeds as $seed)
        {
            if($seed->type == "home")
            {
                return $seed;
            }
        }

        return null;
    }

    /**
     * Return active seeds.
     *
     * @return array
     */
    private function getActiveSeeds() {
        $activeSeeds = array();

        foreach($this->knowSeeds as $seed)
        {
            if($seed->type == "seed" && $seed->active) {
                $activeSeeds[] = $seed;
            }
        }

        return $activeSeeds;
    }

    /**
     * Add weight to seed through distance.
     *
     * @return bool
     */
    private function getWeightedSeeds() {
        $activeSeeds = $this->getActiveSeeds();
        $weightedSeeds = array();

        foreach($activeSeeds as $seed) {
            $poids = array_key_exists($seed->info, $this->arrayDistance) ? $this->arrayDistance[$seed->info] : self::MAX_WEIGHT;

            $weightedSeeds[$poids][] = $seed;
        }

        ksort($weightedSeeds);

        return $weightedSeeds;
    }

    /**
     * Look for closer edigle seed
     *
     * @return mixed
     */
    private function getCloserSeeds() {
        $weightedSeeds = $this->getWeightedSeeds();

        // Key avec poids minimum
        $closerSeeds = $weightedSeeds[array_keys($weightedSeeds, min($weightedSeeds))[0]];

        return $closerSeeds;
    }


    /**
     * Determine which seed should the ant go look after.
     *
     * @return null
     */
    public function determineSeed()
    {
        $edibleSeeds = $this->getCloserSeeds();

        if(count($edibleSeeds) == 0)
        {
            return null;
        }
        else {
            return is_array($edibleSeeds) ? $edibleSeeds[0] : $edibleSeeds;
        }
    }

    /**
     * Hatch a scout to check the road to the next location
     * @return Scout
     */
    public function hatchScout()
    {
        // The Queen decides which seed should be harvest.
        $edibleSeed = $this->determineSeed();
        // The Queen reminds its people where they are standing.
        $homePoint = $this->getHomeSeed();
        // Send the scout for the exit seed.
        return new Scout($edibleSeed->location->coordinates, $homePoint->location->coordinates);
    }

    public function hatchAnt(array $reports)
    {

    }
}