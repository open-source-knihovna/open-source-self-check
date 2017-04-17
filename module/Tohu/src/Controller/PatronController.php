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
        return new ViewModel();
    }
}
