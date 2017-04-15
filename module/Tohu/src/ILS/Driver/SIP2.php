<?php
/**
 * @copyright Copyright (c) 2017 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\ILS\Driver;

use \sip2 as Sip2Connector;

class SIP2 extends AbstractDriver
{
    /*
     * @var SIP2 connection
     */
    protected $connection;

    public function init($config = null)
    {
        $this->connection = new Sip2Connector();
        $this->connection->hostname = $config["ils"]["server"] ?? null;
        $this->connection->port = $config["ils"]["port"] ?? null;
    }

    protected function connect($patronBarcode)
    {
        $this->connection->patron = $patron;
        $connectResponse = $this->connection->connect();
        if (!$connectResponse) {
            throw new \Exception("Can't connect to SIP2 server. Are the hostname and port set properly?");
        }
        $loginResponse = $this->connection->msgLogin($this->config->login, $this->config->password);
        /* TODO: some error handling? */
        $this->connection->parseLoginResponse($this->connection->get_message($loginResponse));
    }

    protected function getCurrentPatronInfo()
    {
        $patronResponse = $this->connection->msgPatronInformation('charged'); //get checkout count again
        $patron = $this->connection->parsePatronInfoResponse($this->connection->get_message($patronResponse));
        return [
            'barcode' => $patronBarcode,
            'name' => $patron['variable']['AE'][0] ?? '',
            'email' => $patron['variable']['BE'][0] ?? '',
            'overdues' => $patron['fixed']['OverdueCount'] ?? 0,
            'holds' => $patron['fixed']['HoldCount'] ?? 0,
            'checkouts' => $patron['fixed']['ChargedCount'] ?? 0,
            'fines' => $patron['variable']['BV'][0] ?? 0,
            'blocked' => (strpos($patron['fixed']['PatronStatus'], 'Y') === false) ? false : true,
        ];
    }

    protected function getItemInfo($barcode)
    {
        $itemResponse = $this->connection->msgItemInformation($barcode);
        $item = $this->connection->parseItemInfoResponse($this->connection->get_message($itemResponse));
        return [
            'barcode' => $barcode,
            'id' => $item['variable']['AB'][0] ?? '',
        ];
    }

    public function checkout($patron, $barcode)
    {
        $return = [];

        $this->connect($patron);

        $checkoutResponse = $this->connection->msgCheckout($barcode, $this->config->location);
        $checkout = $this->connection->parseCheckoutResponse($this->connection->get_message($checkoutResponse));
        $item = $this->connection->msgItemInformation($barcode);
        $return["status"] = $checkout['fixed']['Ok'] ?? false;
        /* TODO: use getItemInfo method */
        $return["item"] = [
            "type" => $item['variable']['CR'][0] ?? null,
            "callnumber" => $item['variable']['CS'][0] ?? null,
            "location" => $item['variable']['AQ'][0] ?? null,
            "title" => $checkout['variable']['AJ'][0] ?? null,
        ];
        $return["patron"] = $this->getCurrentPatronInfo();
        $return["dueDate"] = $checkout['variable']['AH'][0] ?? null;
        $this->connection->msgEndPatronSession();
        return $return;
    }

    public function checkin($patron, $barcode)
    {
        $this->connect($patron);

        $checkinResponse = $this->connection->msgCheckin($barcode, '');
        $checkin = $this->connection->parseCheckinResponse($this->connection->get_message($checkinResponse));
        $item = $this->getItemInfo($barcode);
        $this->connection->msgEndPatronSession();
        return [
            'item' => $item,
            'status' => $checkin['fixed']['Ok'] === "Y" ? true : false,
        ];
    }

    public function getPatron($patronBarcode)
    {
        $this->connect($patronBarcode);
        $patron = $this->getCurrentPatronInfo();
        $this->connection->msgEndPatronSession();
        return $patron;
    }
}
