<?php

/**
 * This file is part of the AnoGoogleMapsBundle
 *
 * (c) anonymation <contact@anonymation.com>
 *
 */

namespace Ano\Bundle\GoogleMapsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ano\Bundle\GoogleMapsBundle\Service\GeocodeAPIQuery;
use Ano\Bundle\GoogleMapsBundle\Model\GeocodeAPIResult;

/**
 * Validates against Google Maps API whether a value is a valid address or not
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class AddressValidator extends ConstraintValidator
{
    public function isValid($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return true;
        }

        $geocode = new GeocodeAPIQuery(array(
            'address' => $value,
            'sensor'  => 'false',
        ));

        $result = $geocode->getResult();
        if (!$result->isSuccess()) {
            switch($result->getStatus()) {
                case GeocodeAPIResult::STATUS_ZERO_RESULTS:
                    $this->setMessage($constraint->invalidAddressMessage);
                break;

                case GeocodeAPIResult::STATUS_NOT_SPECIFIC_ENOUGH:
                    $this->setMessage($constraint->notSpecificEnoughMessage);
                break;

                default:
                    $this->setMessage($constraint->invalidAddressMessage);
            }

            return false;
        }
        else if ($result->getItemCount() > 1) {
            $this->setMessage($constraint->notSpecificEnoughMessage);

            return false;
        }

        return true;
    }
}