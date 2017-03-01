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

    /*
     * @var SIP Config
     */
    protected $config;

    public function init()
    {
        $this->connection = new Sip2Connector();
        $config = $this->getServiceManager()->getConfig();
        $this->config = $config->sip;
        $this->connection->hostname = $this->config->hostname;
        $this->connection->port = $this->config->port;
    }

    public function checkout($patron, $barcode)
    {
        $return = [];
        $this->connection->patron = $patron;
        $connectResponse = $this->connection->connect();
        if (!$connectResponse) {
            throw new \Exception("Can't connect to SIP2 server. Are the hostname and port set properly?");
        }
        $loginResponse = $this->connection->msgLogin($this->config->login, $this->config->password);
        $this->connection->parseLoginResponse($this->connection->get_message($loginResponse));
        $checkoutResponse = $this->connection->msgCheckout($barcode, $this->config->location);
        $checkout = $this->connection->parseCheckoutResponse($this->connection->get_message($checkoutResponse));
        $item = $this->connection->msgItemInformation($barcode);
        $return["status"] = $checkout['fixed']['Ok'] ?? false;
        $return["item"] = [
            "type" => $item['variable']['CR'][0] ?? null,
            "callnumber" => $item['variable']['CS'][0] ?? null,
            "location" => $itet['variable']['AQ'][0] ?? null,
            "title" => $checkout['variable']['AJ'][0] ?? null,
        ];
        $patronResponse = $this->connection->msgPatronInformation('charged'); //get checkout count again
    $patron = $this->connection->parsePatronInfoResponse($this->connection->get_message($patronResponse));
        $return["patron"] = $patron;
        $return["dueDate"] = $checkout['variable']['AH'][0] ?? null;
        $rhis->connection->msgEndPatronSession();
        return $return;
    }
}
