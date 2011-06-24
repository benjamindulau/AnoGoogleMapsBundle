Provides abstraction of Google Maps API WS.

For now, only GeocodeAPI service is implemented.
Other implementations and documentation will come later.

Very fast doc :-)
=================

Service
-------

::

    use Ano\Bundle\GoogleMapsBundle\Service\GeocodeAPIQuery;

    // ...

    $geocode = new GeocodeAPIQuery(array(
        'address' => '13 bis avenue de la Motte Picquet 75007 PARIS',
        'sensor'  => 'false',
    ));

    $result = $geocode->getResult();

    // $address = $result->getAddress();
    // $address->getFormattedAddress();
    // $address->getStreetNumber();
    // $address->getStreetName();
    // $address->getZipCode();
    // ...

    // $geometry = $result->getGeometry();
    // $geometry->getLatitude();
    // $geometry->getLongitude();

Validator
---------

::

    <property name="address">
        <constraint name="Ano\Bundle\GoogleMapsBundle\Validator\Constraints\Address">
            <option name="invalidAddressMessage">address.InvalidAddress</option>
            <option name="notSpecificEnoughMessage">address.NotSpecificEnough</option>
        </constraint>
    </property>

    
    
