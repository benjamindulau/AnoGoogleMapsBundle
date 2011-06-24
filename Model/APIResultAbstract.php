<?php

/**
 * This file is part of the AnoGoogleMapsBundle
 *
 * (c) anonymation <contact@anonymation.com>
 *
 */

namespace Ano\Bundle\GoogleMapsBundle\Model;

abstract class  APIResultAbstract
{
    /* @var string */
    protected $status;

    /* @var boolean */
    protected $success;


    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = (bool)$success;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

}