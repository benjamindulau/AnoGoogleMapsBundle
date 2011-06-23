<?php

namespace Ano\Bundle\GoogleMapsBundle\Model;

class GeocodeGeometry
{
    /* @var float */
    protected $latitude;

    /* @var float */
    protected $longitude;

    
    public function __construct($latitude = 0, $longitude = 0)
    {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = (float)$latitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = (float)$longitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}