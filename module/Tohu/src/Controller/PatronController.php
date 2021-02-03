<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2016 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\Controller;

use Laminas\View\Model\ViewModel;
use Tohu\Controller\AbstractBase;
use Tohu\ILS\DriverInterface;

class PatronController extends AbstractBase
{
    public function infoAction()
    {
        $driver = $this->getILS();
        $patronBarcode = $this->params()->fromPost('library-card');
        $action = $this->params()->fromPost('action');
        $itemBarcode = $this->params()->fromPost('item-barcode');

        $action_result = [];
        $error = null;
        if ($action == "checkout") {
            $action_result['checkout'] = $driver->checkout($patronBarcode, $itemBarcode);
        } elseif ($action == "checkin") {
            $action_result['checkin'] = $driver->checkin($patronBarcode, $itemBarcode);
        } else {
            $error = "Invalid action"; //FIXME This is untranslatable
        }

        $patron = $driver->getPatron($patronBarcode);

        return new ViewModel([
            'patron' => $patron,
            'mode' => $this->mode,
            'error' => $error,
            'result' => $action_result,
        ]);
    }
}
