<?php

namespace Fourmi\Tools;

use Application\Model\HcObj;

class Cicada extends HcObj {
    const CICADA_REPORT_SOURCE = "https://f24h2018.herokuapp.com/api/tracks/otherTeams";
    private $authorization;
    private $table;

    public function __construct($authorization, CicadaTable $table) {
        $this->authorization = $authorization;
        $this->table = $table;
    }

    private function getTracks() {
        $ch = CurlyCrawler::init(self::CICADA_REPORT_SOURCE);
        return CurlyCrawler::call($ch);
    }

    private function storeInMemory($json) {
        foreach($json as $track) {
            $dbTrack = $this->table->getByTrack($track->_id);
            if(is_null($dbTrack)) {
                $memo = new CicadaMemory(array(
                   "track_id" => $track->_id,
                   "done" => false
                ));
                $this->table->saveMemory($memo);
            }
        }
    }

    private function getFromMemory() {
        $tracks = $this->table->fetchAllNotDone();
        if(empty($tracks))
        {
            $json = $this->getTracks();
            $this->storeInMemory($json);
        }

        return $this->table->fetchAllNotDone();
    }

    public function analyseTracks() {
        $tracks = $this->getFromMemory();

        var_dump($tracks);
    }
}
