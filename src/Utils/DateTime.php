<?php

namespace App\Utils;

use DateTime as GenuineDateTime;

class DateTime extends GenuineDateTime
{
    const FORMAT = 'Y-m-d\TH:i:sP';

    public function __toString()
    {
        return $this->format(self::FORMAT);
    }
}