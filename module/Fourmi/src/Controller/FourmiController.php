<?php

namespace Fourmi\Controller;

use Fourmi\Tools\CicadaTable;
use Fourmi\Tools\Nest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Fourmi\Model\Seed;
use Fourmi\Model\Jwt_TokenTable;
use Fourmi\Tools\ScoutMaster;

class FourmiController extends AbstractActionController
{
    const OVER_API_URL = "http://overpass-api.de/api/interpreter?data=";
    private $nest;

    public function __construct(Jwt_TokenTable $table, CicadaTable $cicadaTable)
    {
        $this->nest = new Nest($table, $cicadaTable);
    }

    public function indexAction()
    {
        // curl call API : https://f24h2018.herokuapp.com/api/seeds/search
        $jsonData = $this->nest->getSeeds();

        if($jsonData == "retry") {
            $jsonData = $this->nest->getSeeds();
        }

        $seedArray = array();
        foreach($jsonData as $seed)
        {
            $seedArray[] = (new Seed())->fromJson($seed);
        }

        // Time to meet the Queen.
        $queen = $this->nest->meetTheQueen($seedArray);
        // The Queen hatch a scout to check if there is any danger on the way to the next edible seed
        $scout = $queen->hatchScout();
        // Ask the scout report.
        $reports = $scout->generateReports();
        // Submit the reports to the ScoutMaster
        ScoutMaster::annotateReports($reports);
        // Send the ant !
        $fourmi = $queen->hatchAnt();

        return new JsonModel([
            "status" => "SUCCESS",
            "message" => "Call Overpass",
            "data" => count($seedArray)
        ]);
    }

    public function cicadaAction()
    {
        $cicada = $this->nest->wakeCicada();

        $cicada->analyseTracks();
    }

    private function curlCallOverpass($url)
    {
        $ch = $this->initCall($url);

        // Si problème de certificat.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        return $this->curlCall($ch);
    }

}
