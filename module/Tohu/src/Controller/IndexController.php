<?php
/**
 * @copyright Copyright (c) 2016 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends AbstractBase
{
    public function indexAction()
    {
        return new ViewModel([ 'mode' => $this->mode ]);
    }
}
