<?php

namespace Retournejson\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
//use JpGraph\JpGraph as jpGraph;
use Ghunti\HighchartsPHP\Highchart;
use Ghunti\HighchartsPHP\HighchartJsExpr;
use Ghunti\HighchartsPHP\HighchartOption;
use Ghunti\HighchartsPHP\HighchartOptionRenderer;
use Retournejson\Model\Starship;
use Retournejson\Model\StarshipTable;
use Retournejson\Model\Highway;
use Retournejson\Model\Calcul as Calculatrice;

class RetournejsonController extends AbstractActionController
{
    const SW_API_URL = "https://swapi.co/api/starships/";

    // Add this property:
    private $swTable;

    // Add this constructor:
    public function __construct(StarshipTable $swTable)
    {
        $this->swTable = $swTable;
    }

    public function indexAction()
    {
/* 
        // JPGRAPH
        $yData = array(40,21,17,14,23);

        // Load modules
        jpGraph::load();
        jpGraph::module('pie');

        // Some data
        $data = array(40,21,17,14,23);

        // Create the Pie Graph.
        $graph = new \PieGraph(350,250);

        $theme_class="DefaultTheme";

        // Set A title for the plot
        $graph->title->Set("A Simple Pie Plot");
        $graph->SetBox(true);

        // Create
        $p1 = new \PiePlot($data);
        $graph->Add($p1);

        $p1->ShowBorder();
        $p1->SetColor('black');
        $p1->SetSliceColors(array('#1E90FF','#2E8B57','#ADFF2F','#DC143C','#BA55D3'));
        $graph->Stroke();
        
        if(!is_dir('/Travail/24hcode-master/public/tmp')){
            mkdir('/Travail/24hcode-master/public/tmp', 0777, true);
        }
        if(file_exists('/Travail/24hcode-master/public/tmp/myimage.png')){
            unlink('/Travail/24hcode-master/public/tmp/myimage.png');
        }
 */
 
        // HIGHTCHART
        $chart = new Highchart(Highchart::HIGHCHART);

        $chart->chart = array(
            'renderTo' => 'container',
            'type' => 'line',
            'marginRight' => 130,
            'marginBottom' => 25
        );

        $chart->title = array(
            'text' => 'Monthly Average Temperature',
            'x' => - 20
        );
        $chart->subtitle = array(
            'text' => 'Source: WorldClimate.com',
            'x' => - 20
        );
        $chart->includeExtraScripts();

        $chart->xAxis->categories = array(
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        );

        $chart->yAxis = array(
            'title' => array(
                'text' => 'Temperature (°C)'
            ),
            'plotLines' => array(
                array(
                    'value' => 0,
                    'width' => 1,
                    'color' => '#808080'
                )
            )
        );
        $chart->legend = array(
            'layout' => 'vertical',
            'align' => 'right',
            'verticalAlign' => 'top',
            'x' => - 10,
            'y' => 100,
            'borderWidth' => 0
        );

        $chart->series[] = array(
            'name' => 'Tokyo',
            'data' => array(
                7.0,
                6.9,
                9.5,
                14.5,
                18.2,
                21.5,
                25.2,
                26.5,
                23.3,
                18.3,
                13.9,
                9.6
            )
        );
        $chart->series[] = array(
            'name' => 'New York',
            'data' => array(
                - 0.2,
                0.8,
                5.7,
                11.3,
                17.0,
                22.0,
                24.8,
                24.1,
                20.1,
                14.1,
                8.6,
                2.5
            )
        );
        $chart->series[] = array(
            'name' => 'Berlin',
            'data' => array(
                - 0.9,
                0.6,
                3.5,
                8.4,
                13.5,
                17.0,
                18.6,
                17.9,
                14.3,
                9.0,
                3.9,
                1.0
            )
        );
        $chart->series[] = array(
            'name' => 'London',
            'data' => array(
                3.9,
                4.2,
                5.7,
                8.5,
                11.9,
                15.2,
                17.0,
                16.6,
                14.2,
                10.3,
                6.6,
                4.8
            )
        );
        
        $chart->tooltip->formatter = new HighchartJsExpr(
            "function() { return '<b>'+ this.series.name +'</b><br/>'+ this.x +': '+ this.y +'°C';}");
        
/*
        return new ViewModel([
            "myChart" => $chart->render("sct_container"),
            "myJs"    => $chart->printScripts(true)
        ]);
*/
        return new JsonModel([
            'status' => 'SUCCESS',
            'message'=>'Here is your data',
            'data' => [
                'full_name' => 'John Doe',
                'address' => '51 Middle st.'
            ]
        ]);
    }

    public function autoLoadStarshipsAction()
    {
        $arrStarships = array();

        // Get Starships from API
        $arr = $this->curlCallSwApi();

        // Store starships in array
        $arrStarships = array_merge($arrStarships, $arr->results);

        // Call Api while there is still datas to fetch.
        while(!is_null($arr->next))
        {
            $arr = $this->curlCallSwApi($arr->next);
            $arrStarships = array_merge($arrStarships,$arr->results);
        }
     
        // Build return array
        $arrItemStarship = array();

        foreach($arrStarships as $item)
        {
            $starShip = new Starship();
            $starShip->initFromJson($item);

            $highway = new Highway();
            $highway->canGo();
            // Insert starships to database
            $this->swTable->saveStarship($starShip);

            $arrItemStarship[] = $starShip->getDataArray(true);
        }

        return new JsonModel([
            "status" => "SUCCESS",
            "message" => "SW Starships",
            "data" => $arrItemStarship
        ]);
    }

    public function getAction()
    {
        $lat1 = 48.0819;
        $long1 = 0.4154;
        $lat2 = 48.0826;
        $long2 = 0.4166;

        var_dump(Calculatrice::getDistanceM($lat1, $long1, $lat2, $long2));
        /*
        $queryUrl = "http://overpass-api.de/api/interpreter?data=";
        $queryHeader = "[out:json];";
        $queryContent = "way(47.984393,0.236012,47.984946,0.238951)[highway];(._;>;);";
        $queryEnd = "out;";
        
        $fullQuery = $queryUrl . $queryHeader . $queryContent . $queryEnd;
        $rawData = $this->curlCallSwApi($fullQuery, true);
var_dump($rawData);
        return new JsonModel([
            "status" => "SUCCESS",
            "message" => "Call Overpass",
            "data" => $rawData
        ]);*/
    }

    private function curlCallSwApi($url = null, $brut = false)
    {
        // Initialisation
        $ch = curl_init();
        
        if (!$ch)
            throw new \Exception('failed to initialize');

        // Config URL
        curl_setopt($ch, CURLOPT_URL, is_null($url) ? self::SW_API_URL : $url);
        
        // Pour éviter de dump la réponse directement dans la page.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Si problème de certificat.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Récupération de l'appel curl
        $result = curl_exec($ch);
        if (!$result)
            throw new \Exception(curl_error($ch), curl_errno($ch));

        if(!$brut) {
            // Décodage du résultat.
            $decodedResult = json_decode($result);
        }
        else {
            $decodedResult = $result;
        }
        // Fermeture de la session curl
        curl_close($ch);

        return $decodedResult;
    }
}
