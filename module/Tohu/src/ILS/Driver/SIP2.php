<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2017 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\ILS\Driver;

use \SIP2\Connection as Sip2connection;
use \Tohu\ILS\Driver\Exception\ConfigException;

class SIP2 extends AbstractDriver
{
    /*
     * @var SIP2 connection
     */
    protected Sip2connection $connection;

    /*
     * @var library code
     */
    protected string $library;

    /*
     * @var Date format for display
     */
    protected string $date_format;

    public function init(array $ilsConfig = []): void
    {
        $this->date_format = "j. n. Y";
        $this->library = $ilsConfig["library"] ?? null;
        $this->connection = new Sip2connection(
            $ilsConfig["server"] ?? null,
            $ilsConfig["port"] ?? null,
            $ilsConfig["login"] ?? '',
            $ilsConfig["password"] ?? ''
        );
    }

    public function getPatron(string $patronBarcode): array
    {
        $patron = $this->connection->getPatronInfo($patronBarcode, true);
        $checkouts = [];
        foreach ($patron['checkouts'] as $checkout) {
            $checkouts[] = [
                'title' => $checkout->variable['AJ'][0] ?? '',
                'item_location' => $checkout->variable['AQ'][0] ?? '',
                'checkout_location' => $checkout->variable['BG'][0] ?? '',
                'barcode' => $checkout->variable['AB'][0] ?? '',
                'due_date' => isset($checkout->variable['AH'][0]) ?
                    $this->dateFromSip($checkout->variable['AH'][0]) : '',
                'circulation_status' => isset($checkout->fixed['CirculationStatus']) ?
                    $this->getCirculationStatus($checkout->fixed['CirculationStatus']) : '',
            ];
        }
        return [
            'barcode' => $patron['patron']->variable['AA'][0] ?? '',
            'name' => $patron['patron']->variable['AE'][0] ?? '',
            'email' => $patron['patron']->variable['BE'][0] ?? '',
            'overdues' => $patron['patron']->fixed['OverdueCount'] ?? 0,
            'holds' => $patron['patron']->fixed['HoldCount'] ?? 0,
            'fines' => $patron['patron']->variable['BV'][0] ?? 0,
            'blocked' => (strpos($patron['patron']->fixed['PatronStatus'], 'Y') === false) ? false : true,
            'checkouts' => $checkouts,
        ];
    }

    public function checkout(string $patron, string $barcode, string $location = ''): array
    {
        $library = empty($location) ?? $this->library;
        if (empty($library)) {
            throw new ConfigException("Can't do checkout without library set");
        }

        $checkout = $this->connection->doCheckout($barcode, $library);
        $item = $this->connection->getItemInfo($barcode);
        return [
            'title' => $item->variable['AJ'][0],
            'dueDate' => !empty($checkout->variable['AH']) ? $this->dateFromSip($checkout->variable['AH'][0]) : null,
            'status' => $checkout->fixed['Ok'] == "1",
            'raw' => $checkout,
        ];
    }

    public function checkin(string $patron, string $barcode): array
    {
        $checkin = $this->connection->doCheckin($barcode);
        $status = isset($checkin->variable['AA'][0]) && $checkin->fixed['Ok'] === "1"  && $checkin->fixed['Alert'] ==="N";
        return [
            'title' => $checkin->variable['AJ'][0],
            'status' => $status,
        ];
    }

    protected function dateFromSip(string $sip_date): string
    {
        $date = \DateTime::createFromFormat("Ymd", substr($sip_date, 0, 8));
        if ($date === false) {
            return $sip_date;
        }
        return $date->format($this->date_format);
    }

    protected function getCirculationStatus(int $status_number): string
    {
        switch ($status_number) {
            case 1: return "other";
            case 2: return "on order";
            case 3: return "available ";
            case 4: return "charged";
            case 5: return "charged; not to be recalled until earliest recall date";
            case 6: return "in process";
            case 7: return "recalled";
            case 8: return "waiting on hold shelf";
            case 9: return "waiting to be re-shelved";
            case 10: return "in transit between library locations";
            case 11: return "claimed returned";
            case 12: return "lost";
            case 13: return "missing";
            default: return "unknown";
        }
    }
}
