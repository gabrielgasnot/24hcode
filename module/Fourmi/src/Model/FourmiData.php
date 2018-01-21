<?php

namespace Fourmi\Model;

use Application\Model\HcObj;

class FourmiData extends HcObj{
    private $type;
    private $id;
    private $tags = array();
}

class FourmiDataTag extends HcObj
{
    private $highway;
    private $maxspeed;
    private $name;
}