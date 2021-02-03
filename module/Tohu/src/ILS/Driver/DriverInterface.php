<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2016 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\ILS\Driver;

interface DriverInterface
{
    public function init(array $config);
    public function checkout($patron, $itemBarcode);
    public function checkin($patron, $itemBarcode);
    public function getPatron($patronBarcode);
}
