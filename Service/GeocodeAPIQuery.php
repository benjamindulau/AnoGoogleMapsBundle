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


class GeocodeAPIQuery extends APIQueryAbstract
{
    protected $serviceUri = 'http://maps.googleapis.com/maps/api/geocode/';

    public function __construct(array $parameters = array(), $format = 'json')
    {
        $this->allowedFormats = array('json', 'xml');
        $this->result = new GeocodeAPIResult();
        
        parent::__construct($parameters, $format);
    }

    /**
     * @return Ano\Bundle\GoogleMapsBundle\Model\GeocodeAPIResult
     */
    protected function parseResponse($response)
    {
        switch(mb_strtolower($this->format)) {
            case 'json':
                $this->parseJson($response);
            break;

            case 'xml':
                $this->parseXml($response);
            break;
        }

        return $this->result;
    }

    protected function parseJson($json)
    {
        $response = json_decode($json, true);
        if (!is_array($response)) {
            $this->setResultStatus(GeocodeAPIResult::STATUS_INVALID_RESPONSE, false);
            return;
        }
        
        if (!array_key_exists('results', $response) || !array_key_exists('status', $response)) {
            $this->setResultStatus(GeocodeAPIResult::STATUS_INVALID_RESPONSE, false);
            return;
        }

        $resultCount = count($response['results']);
        if ($resultCount <= 0) {
            $this->setResultStatus(GeocodeAPIResult::STATUS_ZERO_RESULTS, false);
            return;
        }

        if ($resultCount > 1) {
            $this->setResultStatus(GeocodeAPIResult::STATUS_NOT_SPECIFIC_ENOUGH, false);
            return;
        }

        $data = $response['results'][0];
        $status = $response['status'];

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

        return $this->buildResult($arrayData, $status);
    }

    private function parseXml($xml)
    {
        // TODO
    }

    private function buildResult(array $arrayData, $status)
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

        $this->result->setAddress($address);
        $this->result->setGeometry($geometry);
        $this->result->setSuccess(true);

        $this->buildStatus($status);

        return $this->result;
    }

    protected function buildStatus($status)
    {
        switch(mb_strtolower($status)) {
            case 'ok':
                $this->setResultStatus(GeocodeAPIResult::STATUS_OK, true);
            break;

            case 'zero_results':
                $this->setResultStatus(GeocodeAPIResult::STATUS_ZERO_RESULTS, false);
            break;

            case 'over_query_limit':
                $this->setResultStatus(GeocodeAPIResult::STATUS_OVER_QUERY_LIMIT, false);
            break;

            case 'request_denied':
                $this->setResultStatus(GeocodeAPIResult::STATUS_REQUEST_DENIED, false);
            break;

            case 'invalid_request':
                $this->setResultStatus(GeocodeAPIResult::STATUS_INVALID_REQUEST, false);
            break;

            default:
                $this->setResultStatus(GeocodeAPIResult::STATUS_INVALID_RESPONSE, false);
        }
    }

    protected function setResultStatus($status, $success)
    {
        $this->result->setStatus($status);
        $this->result->setSuccess($success);
    }
}