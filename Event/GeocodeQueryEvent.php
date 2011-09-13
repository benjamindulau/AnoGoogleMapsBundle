<?php

namespace Ano\Bundle\GoogleMapsBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Ano\Bundle\GoogleMapsBundle\Service\GeocodeAPIQuery;

class GeocodeQueryEvent extends Event
{
    protected $query;

    
    public function __construct(GeocodeAPIQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @return \Ano\Bundle\GoogleMapsBundle\Service\GeocodeAPIQuery
     */
    public function getQuery()
    {
        return $this->query;
    }
}
