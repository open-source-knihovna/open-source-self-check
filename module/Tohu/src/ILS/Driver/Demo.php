<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2017 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\ILS\Driver;

class Demo extends AbstractDriver
{
    public function init(array $config = []): void
    {
    }

    public function checkout(string $patron, string $itemBarcode, string $location = ''): array
    {
        return [
            'status' => true,
            'item' => [
                'type' => 'book',
                'callnumber' => '321.45',
                'location' => 'Main library',
                'title' => 'The best book ever',
                'barcode' => $itemBarcode,
            ],
            'patron' => $this->getPatron($patron),
            'dueDate' => date('c', time() + 1000 * 60 *  60 * 24 * 30), // now plus 30 days
        ];
    }

    public function checkin(string $patron, string $itemBarcode): array
    {
        return [
            'item' => [
                'type' => 'book',
                'callnumber' => '321.45',
                'location' => 'Main library',
                'title' => 'The best book ever',
                'barcode' => $itemBarcode,
            ],
            'status' => false,
        ];
    }

    public function getPatron(string $patronBarcode): array
    {
        $checkouts = [
            [
                'title' => 'I am the only one',
                'item_location' => 'Item location',
                'checkout_location' => 'Checkout location',
                'barcode' => '123456',
                'due_date' => '2025-02-11',
                'circulation_status' => 'Checked out',
            ],
            [
                'title' => 'I am the only two',
                'item_location' => 'Item location',
                'checkout_location' => 'Checkout location',
                'barcode' => '654321',
                'due_date' => '2025-03-12',
                'circulation_status' => 'Checked out',
            ],
        ];
        return [
            'barcode' => $patronBarcode,
            'name' => "John Doe",
            'email' => "john.doe@example.com",
            'overdues' => 3,
            'holds' => 4,
            'checkouts' => $checkouts,
            'fines' => 20,
            'blocked' => false,
        ];
    }
}
