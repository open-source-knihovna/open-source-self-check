<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2016 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\ILS\Driver;

interface DriverInterface
{
    public function init(array $config): void;
    public function checkout(string $patron, string $itemBarcode, string $location = ''): array;
    public function checkin(string $patron, string $itemBarcode): array;
    public function getPatron(string $patronBarcode): array;
}
