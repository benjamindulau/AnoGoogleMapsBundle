<?php

/**
 * This file is part of the AnoGoogleMapsBundle
 *
 * (c) anonymation <contact@anonymation.com>
 *
 */

namespace Ano\Bundle\GoogleMapsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Address extends Constraint
{
    public $invalidAddressMessage = 'This value is not a valid address or the address doesn\'t exist';
    public $notSpecificEnoughMessage = 'This value is not specific enough, please fill a full address';
}
