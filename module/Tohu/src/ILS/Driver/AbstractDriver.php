<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2017 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\ILS\Driver;

use \Tohu\ILS\Driver\Exception\MethodNotImplementedException;

abstract class AbstractDriver implements DriverInterface
{
    public function init(array $config = []): void
    {
        throw new MethodNotImplementedException("Method init not implemented");
    }

    public function checkout(string $patron, string $itemBarcode, string $location = ''): array
    {
        throw new MethodNotImplementedException("Method checkout not implemented");
    }

    public function checkin(string $patron, string $itemBarcode): array
    {
        throw new MethodNotImplementedException("Method checkin not implemented");
    }

    public function getPatron(string $patronBarcode): array
    {
        throw new MethodNotImplementedException("Method getPatron not implemented");
    }
}
