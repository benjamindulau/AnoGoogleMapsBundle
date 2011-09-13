<?php

/**
 * This file is part of the AnoGoogleMapsBundle
 *
 * (c) anonymation <contact@anonymation.com>
 *
 */

namespace Ano\Bundle\GoogleMapsBundle\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


abstract class APIQueryAbstract
{
    protected $serviceUri;
    public $parameters;
    protected $response;
    protected $result;
    protected $format;
    protected $allowedFormats = array();
    protected $dispatcher;

    public function __construct(array $parameters = array(), $format)
    {
        $this->parameters = new ParameterBag($parameters);
        $this->setFormat($format);
    }

    public function setServiceUri($serviceUri)
    {
        $this->serviceUri = $serviceUri;
    }

    public function getServiceUri()
    {
        return $this->serviceUri;
    }

    public function setFormat($format)
    {
        if (!in_array($format, $this->allowedFormats)) {
            throw new \InvalidArgumentException(sprintf('The format "%s" is not accepted', $format));
        }
        
        $this->format = $format;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function getResult()
    {
        if (empty($this->serviceUri)) {
            throw new \InvalidArgumentException('The service URI must be defined!');
        }

        $url = rtrim($this->serviceUri , '/');
        $url.= '/' . $this->getFormat();
        
        $request = new APIHttpRequest($url);
        $request->query->add($this->parameters->all());
        $response = $request->getResponse();

        return $this->parseResponse($response);
    }

    /**
     * @return \Ano\Bundle\GoogleMapsBundle\Model\APIResultAbstract
     */
    abstract protected function parseResponse($response);

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}