<?php
/**
 * @copyright Copyright (c) 2017 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\ILS\Driver;

class Demo extends AbstractDriver
{
    public function init(array $config = [])
    {
        return;
    }

    public function checkout($patron, $itemBarcode)
    {
        return [
            'status' => true,
            'item' => [
                'type' => 'book',
                'callnumber' => '321.45',
                'location' => 'Main library',
                'title' => 'The best book ever',
            ],
            'patron' => $this->getPatron($patron),
            'dueDate' => date('c', time() + 1000 * 60 *  60 * 24 * 30), // now plus 30 days
        ];
    }

    public function checkin($patron, $itemBarcode)
    {
        return [
            'item' => [
                'type' => 'book',
                'callnumber' => '321.45',
                'location' => 'Main library',
                'title' => 'The best book ever',
            ],
            'status' => false,
        ];
    }

    public function getPatron($patronBarcode)
    {
        return [
            'barcode' => "100020003000",
            'name' => "John Doe",
            'email' => "john.doe@example.com",
            'overdues' => 3,
            'holds' => 4,
            'checkouts' => 5,
            'fines' => 20,
            'blocked' => false,
        ];
    }
}
