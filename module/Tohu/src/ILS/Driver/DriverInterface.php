<?php
/**
 * @copyright Copyright (c) 2016 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\ILS;

interface DriverInterface
{

public function checkout($patron, $item_barcode);
public function checkin($patron, $item_barcode);
public function getPatron($patron_barcode);

}
