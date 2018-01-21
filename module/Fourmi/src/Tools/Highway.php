<?php

namespace Fourmi\Tools;

class Highway {
    const NO_GO_ZONE = "nogo";
    const SLOW = "slow";
    const STOP = "stop";

    const TOLERATED_HIGHWAY = [
        "motorway",
        "trunk",
        "primary",
        "secondary",
        "tertiary",
        "unclassified",
        "residential",
        "service"
    ];

    const STOP_SIGN = [
        "give_way",
        "stop",
        "traffical_signals"
    ];

    const SLOW_ZONE = [
        "mini_roundabout",
        "passing_place",
        
    ];

    public static function generateNotes($json) {
        // Default = no go
        $isTolerated = false;
        $isStop = false;
        $isSlow = false;

        if(property_exists($json, "highway")) {
            $isTolerated = in_array($json->highway, self::TOLERATED_HIGHWAY);
            $isSlow = in_array($json->highway, self::SLOW_ZONE);
            $isStop = in_array($json->highway, self::STOP_SIGN);
        }

        return array(
            self::NO_GO_ZONE => !$isTolerated && !$isSlow && !$isStop,
            self::SLOW => $isSlow,
            self::STOP => $isStop
        );
    }
}