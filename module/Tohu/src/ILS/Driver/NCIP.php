<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2017 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\ILS\Driver;

use Scriptotek\Ncip\NcipConnector;
use Scriptotek\Ncip\NcipClient;

class NCIP extends AbstractDriver
{
    protected ?string $ncipUrl;

    protected string $userAgent = "Tohu version " . \Tohu\Module::VERSION;

    protected ?string $agencyId;

    /**
     * @var \Scriptotek\Ncip\NcipClient
     */
    protected $ncip;

    public function init(array $config = []): void
    {
        $this->ncipUrl = $config["ils"]["server"] ?? null;
        $this->agencyId = $config["ils"]["agencyId"] ?? null;
        $conn = new NcipConnector($this->ncipUrl, $this->userAgent, $this->agencyId);
        $this->ncip = new NcipClient($conn);
    }
}
