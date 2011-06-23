<?php

/**
 * This file is part of the AnoGoogleMapsBundle
 *
 * (c) anonymation <contact@anonymation.com>
 *
 */

namespace Ano\Bundle\GoogleMapsBundle\Service;

use Ano\Bundle\GoogleMapsBundle\Model\GeocodeAPIResult;
use Ano\Bundle\GoogleMapsBundle\Model\GeocodeAddress;
use Ano\Bundle\GoogleMapsBundle\Model\GeocodeGeometry;

use HttpMessage;

class GeocodeAPIQuery extends APIQueryAbstract
{
    protected $serviceUri = 'http://maps.googleapis.com/maps/api/geocode/';

    public function __construct(array $parameters = array(), $format = 'json')
    {
        $this->allowedFormats = array('json', 'xml');
        parent::__construct($parameters, $format);
    }

    protected function parseResponse($response)
    {
        switch(mb_strtolower($this->format)) {
            case 'json':
                $result = $this->parseJson($response);
            break;

            case 'xml':
                $result = $this->parseXml($response);
            break;
        }

        return $result;
    }

    protected function parseJson($json)
    {
        $data = json_decode($json, true);
        if (is_array($data) && array_key_exists('results', $data)) {
            $data = $data['results'][0];
        }

        $arrayData = array(
            'address' => array(),
            'geometry' => array()
        );

        // parsing address
        foreach($data['address_components'] as $addressPart) {
            if (array_key_exists('types', $addressPart)) {
                foreach($addressPart['types'] as $type) {
                    if ('political' != $type) {
                        $arrayData['address'][$type] = $addressPart;
                    }
                    unset($arrayData['address'][$type]['types']);
                }
            }
        }
        if (array_key_exists('formatted_address', $data)) {
            $arrayData['address']['formatted_address'] = $data['formatted_address'];
        }

        // parsing geometry
        $arrayData['geometry']['latitude'] = $data['geometry']['location']['lat'];
        $arrayData['geometry']['longitude'] = $data['geometry']['location']['lng'];

        return $this->buildResult($arrayData);
    }

    private function parseXml($xml)
    {
        // TODO
    }

    private function buildResult(array $arrayData)
    {
        $address = new GeocodeAddress();
        if (array_key_exists('street_number', $arrayData['address'])) {
            $address->setStreetNumber($arrayData['address']['street_number']['long_name']);
        }
        if (array_key_exists('route', $arrayData['address'])) {
            $address->setStreetName($arrayData['address']['route']['long_name']);
        }
        if (array_key_exists('locality', $arrayData['address'])) {
            $address->setLocality($arrayData['address']['locality']['long_name']);
        }
        if (array_key_exists('administrative_area_level_2', $arrayData['address'])) {
            $address->setRegion($arrayData['address']['administrative_area_level_2']['long_name']);
        }
        if (array_key_exists('administrative_area_level_1', $arrayData['address'])) {
            $address->setDivision($arrayData['address']['administrative_area_level_1']['long_name']);
        }
        if (array_key_exists('country', $arrayData['address'])) {
            $address->setCountry($arrayData['address']['country']['long_name']);
        }
        if (array_key_exists('postal_code', $arrayData['address'])) {
            $address->setZipCode($arrayData['address']['postal_code']['long_name']);
        }
        if (array_key_exists('formatted_address', $arrayData['address'])) {
            $address->setFormattedAddress($arrayData['address']['formatted_address']);
        }

        $geometry = new GeocodeGeometry();
        $geometry->setLatitude($arrayData['geometry']['latitude']);
        $geometry->setLongitude($arrayData['geometry']['longitude']);

        return new GeocodeAPIResult($address, $geometry);
    }
}