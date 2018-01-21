<?php

namespace Fourmi\Tools;

use Application\Model\HcObj;

class ScoutMaster extends HcObj {
    const ROUTE_API_URL = "http://overpass.openstreetmap.fr/api/interpreter";
    const ROUTE_API_BASE_END = "out;";
    const ROUTE_API_RELATION_END = "(._;<;);out;";

    private static function getNodes(ScoutReport $report)
    {
        $fullQuery = self::ROUTE_API_URL . "?data=[out:json];" . $report->getAllNodes() . self::ROUTE_API_BASE_END;

        $ch = CurlyCrawler::init($fullQuery);

        return CurlyCrawler::call($ch);
    }

    private static function getRelations(ScoutReport $report)
    {
        $fullQuery = self::ROUTE_API_URL;// . $report->getAllNodes() . self::ROUTE_API_RELATION_END;

        $ch = CurlyCrawler::init($fullQuery);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $fields = [
            "data" => "data:[out:json];" .  $report->getAllNodes() . self::ROUTE_API_RELATION_END
        ];

        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        return CurlyCrawler::call($ch);
    }

    private static function checkNodes($jsonNodes, $jsonRelations, ScoutReport $report) {

        if(!empty($jsonRelations)) {
            $arrayHighway = array_filter($jsonRelations->features, function($feature) {
                return property_exists($feature->properties, "highway");
            });

            $arrayWay = array_filter($arrayHighway, function($feature) {
                $arrayId =  explode("/", $feature->id);
                return $arrayId[0] == "way";
            });

            $arrayNode = array_filter($arrayHighway, function($feature) {
                $arrayId =  explode("/", $feature->id);
                return $arrayId[0] == "node";
            });

            // Build Ways
            foreach($arrayWay as $way) {
                $notes = Highway::generateNotes($way->properties);

                if($notes[Highway::NO_GO_ZONE]) {
                    continue;
                }

                $arrayCoordinates = array();
                foreach($way->geometry->coordinates as $coord) {
                    $arrayCoordinates[] = array(
                        "lat" => $coord[1],
                        "long" => $coord[0]
                    );
                }

                $report->ways[] = new ScoutWay(array(
                    "maxSpeed" => property_exists($way->properties, "maxspeed") ? $way->properties->maxspeed : "50",
                    "relationsCoordinates" => $arrayCoordinates
                ));
            }

            // Complete nodes
            foreach($jsonNodes as $jsonNode) {
                $arrId = explode("/", $jsonNode->id);
                $nodeId = $arrId[1];
                $node = $report->nodes[$nodeId];
                $node->coordinates = array(
                    "lat" => $jsonNode->geometry->coordinates[1],
                    "long" => $jsonNode->geometry->coordinates[0]
                );

                foreach($report->ways as $way) {
                    if($way->hasNode($node)) {
                        $node->maxSpeed = $way->maxSpeed;
                    }
                }

                foreach($arrayNode as $relationNode) {
                    if($relationNode->id == $jsonNode->id) {
                        $node->notes = Highway::generateNotes($jsonNode->properties);
                    }
                }
            }
        }
    }

    public static function annotateReports($reports) {
        $arrayReport = array();

        if(is_array($reports)) {
            $arrayReport = $reports;
        }
        else {
            $arrayReport[$reports->sens] = $reports;
        }

        foreach($arrayReport as $sens => $report) {
            if(empty($report)) {
                continue;
            }

            $jsonNodes = self::getNodes($report);
            $jsonRelations = self::getRelations($report);
            self::checkNodes($jsonNodes, $jsonRelations, $report);
        }

        return $arrayReport;
    }
}