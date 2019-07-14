<?php
declare(strict_types = 1);

namespace Carrier;

class CarrierFrance
{
    public const ITEMS = [
        'MR' => [
            'code' => 'MR',
            'title' => 'Mondial Relay',
            'description' => 'Allows to drop and pick up a shipment at so-called drop-off point, thus being less convenient, but cheaper for larger packages',
        ],
        'LP' => [
            'code' => 'LP',
            'title' => 'La Poste',
            'description' => 'Provides usual courier delivery services'
        ],
    ];
}
