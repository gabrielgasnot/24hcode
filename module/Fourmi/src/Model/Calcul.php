<?php

namespace Fourmi\Model;

class Calcul {
    static function deg2rad($value) {
        return pi()*$value/180;
    }
       
    static function getDistanceM($lat1, $lng1, $lat2, $lng2) {
        $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
        $rlo1 = self::deg2rad($lng1);    // CONVERSION
        $rla1 = self::deg2rad($lat1);
        $rlo2 = self::deg2rad($lng2);
        $rla2 = self::deg2rad($lat2);
        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return ($earth_radius * $d);
    }

    static function duree($date1, $date2) {
        $dateTime = new \DateTime( $date1 );
        $dateTime2 = new \DateTime( $date2 );

        return (float)((float)$dateTime2->getTimestamp() -  (float)$dateTime->getTimestamp());
    }
}