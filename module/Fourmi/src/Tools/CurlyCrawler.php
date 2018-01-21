<?php

namespace Fourmi\Tools;

use Application\Model\HcObj;

class CurlyCrawler {
    public static function init($url)
    {
        // Initialisation
        $ch = curl_init();

        if (!$ch)
            throw new \Exception('failed to initialize');

        // Config URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // Pour éviter de dump la réponse directement dans la page.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Si problème de certificat.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        return $ch;
    }

    public static function call($ch)
    {
        // Récupération de l'appel curl
        $result = curl_exec($ch);
        if (!$result)
            throw new \Exception(curl_error($ch), curl_errno($ch));

        // Décodage du résultat.
        $decodedResult = json_decode($result);

        // Fermeture de la session curl
        curl_close($ch);

        return $decodedResult;
    }
}