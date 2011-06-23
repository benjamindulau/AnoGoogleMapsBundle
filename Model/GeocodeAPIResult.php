<?php

/**
 * This file is part of the AnoGoogleMapsBundle
 *
 * (c) anonymation <contact@anonymation.com>
 *
 */

namespace Ano\Bundle\GoogleMapsBundle\Model;

use HttpMessage;

class GeocodeAPIResult implements APIResultInterface
{
    /* @var GeocodeAddress */
    protected $address;

    /* @var GeocodeGeometry */
    protected $geometry;
    

    public function __construct(GeocodeAddress $address, GeocodeGeometry $geometry)
    {
        $this->address = $address;
        $this->geometry = $geometry;
    }

    /**
     * @param GeocodeAddress $address
     */
    public function setAddress(GeocodeAddress $address)
    {
        $this->address = $address;
    }

    /**
     * @return GeocodeAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param GeocodeGeometry $geometry
     */
    public function setGeometry(GeocodeGeometry $geometry)
    {
        $this->geometry = $geometry;
    }

    /**
     * @return GeocodeGeometry
     */
    public function getGeometry()
    {
        return $this->geometry;
    }

}