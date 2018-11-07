<?php
/**
 * @copyright Copyright (c) 2016 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\Controller;

use Zend\View\Model\ViewModel;
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
        $patron = $driver->getPatron($patronBarcode);

        $action_result = null;
        $error = null;
        if ($action == "checkout") {
            $action_result = $this->processCheckout($patronBarcode, $itemBarcode);
        } elseif ($action == "checkin") {
            $action_result = $this->processCheckin($patronBarcode, $itemBarcode);
        } else {
            $error = "Invalid action"; //FIXME This is untranslatable
        }

        return new ViewModel([
            'patron' => $patron,
            'mode' => $this->mode,
            'error' => $error,
            'result' => $action_result,
        ]);
    }

    protected function processCheckin()
    {
        return true;
    }


    protected function processCheckout()
    {
        return true;
    }
}
