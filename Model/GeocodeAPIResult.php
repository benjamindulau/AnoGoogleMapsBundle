<?php

/**
 * This file is part of the AnoGoogleMapsBundle
 *
 * (c) anonymation <contact@anonymation.com>
 *
 */

namespace Ano\Bundle\GoogleMapsBundle\Model;


class GeocodeAPIResult extends APIResultAbstract
{
    const STATUS_OK = 'OK';
    const STATUS_ZERO_RESULTS = 'ZERO_RESULTS';
    const STATUS_OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';
    const STATUS_REQUEST_DENIED = 'REQUEST_DENIED';
    const STATUS_INVALID_REQUEST = 'INVALID_REQUEST';
    const STATUS_INVALID_RESPONSE = 'INVALID_RESPONSE';
    const STATUS_NOT_SPECIFIC_ENOUGH = 'NOT_SPECIFIC_ENOUGH';

    /* @var GeocodeAddress */
    protected $address;

    /* @var GeocodeGeometry */
    protected $geometry;
    

    public function __construct(GeocodeAddress $address = null, GeocodeGeometry $geometry = null)
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