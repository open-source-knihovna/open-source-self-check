<?php
/**
 * @copyright Copyright (c) 2017 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\ILS\Driver;

use \Tohu\ILS\Driver\Exception\MethodNotImplementedException;

abstract class AbstractDriver implements DriverInterface
{
    public function checkout($patron, $item_barcode)
    {
        throw new MethodNotImplementedException("Method checkout not implemented");
    }

    public function checkin($patron, $item_barcode)
    {
        throw new MethodNotImplementedException("Method checkin not implemented");
    }

    public function getPatron($patron_barcode)
    {
        throw new MethodNotImplementedException("Method getPatron not implemented");
    }
}
