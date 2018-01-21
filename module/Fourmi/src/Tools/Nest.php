<?php

namespace Fourmi\Tools;

use Application\Model\HcObj;
use Fourmi\Model\Reine;
use Fourmi\Model\Jwt_TokenTable;
use Fourmi\Model\Jwt_Token;

class Nest extends HcObj {
    const NEST_API_URL_AUTH = "https://f24h2018.herokuapp.com/auth/local";
    const NEST_API_URL_SEED_SEARCH = "https://f24h2018.herokuapp.com/api/seeds/search";
    private $authorization;
    private $token;
    private $table;
    private $cicadaTable;

    public function __construct(Jwt_TokenTable $table, CicadaTable $cicadaTable) {
        $this->table = $table;
        $this->cicadaTable = $cicadaTable;
    }

    /**
     * Init the authorization header
     *
     * @throws \Exception
     */
    private function initNest()
    {
        $ch = CurlyCrawler::init(self::NEST_API_URL_AUTH);

        // 1 ant at first
        $fields = array(
            'email' => 'ant1@yellow.ant',
            'password' => 'Banana'
        );

        // Set Token.
        // Pas de Token DB => invalidate pas fiable.
        $this->token = null; // $this->table->getTokenByEmail($fields['email']);

        // Pas de token en base, on va le chercher via URL.
        if(is_null($this->token)) {
            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');

            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

            //        $queryHeader = "[out:json];";
            //        $queryContent = "way(47.984393,0.236012,47.984946,0.238951)[highway];(._;>;);";
            //        $queryEnd = "out;";

            $result = CurlyCrawler::call($ch);

            if(property_exists($result, "token")) {
                // Create JwtToken
                $this->token = new Jwt_Token(array(
                    "email" => $fields['email'],
                    "token" => $result->token
                ));
            }
            else {
                throw new \Exception("Couldn't retrieve token : " . $result);
            }

            // Store in DB
            $this->table->saveToken($this->token);
        }

        $this->authorization = "Authorization: Bearer " . $this->token->token;
    }

    /**
     * Return json decoded array of seeds
     *
     * @return mixed
     * @throws \Exception
     */
    public function getSeeds()
    {
        if(empty($this->authorization))
        {
            $this->initNest();
        }

        $ch = CurlyCrawler::init(self::NEST_API_URL_SEED_SEARCH);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $this->authorization ));

        $result = CurlyCrawler::call($ch);

        if(is_null($result)) {
            $this->table->deleteToken($this->token->id);
            return "retry";
        }

        return $result;
    }

    public function meetTheQueen(array $seedArray)
    {
        return new Reine($seedArray);
    }

    public function wakeCicada() {
        if(empty($this->authorization))
        {
            $this->initNest();
        }

        return new Cicada($this->authorization,$this->cicadaTable);
    }
}