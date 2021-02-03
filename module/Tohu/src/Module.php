<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2016 Tohu development team
 * @license   https://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Tohu;

use Laminas\Mvc\MvcEvent;

class Module
{
    const VERSION = '0.1dev';

    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event): void
    {
        $app = $event->getParam('application');
        $config = $app->getConfig();
        $viewModel = $app->getMvcEvent()->getViewModel();
        $viewModel->config = $config["tohu"];
        $viewModel->driver = $config[strtolower($config["tohu"]["driver"])];
        $locale = $config["tohu"]["language"] ?? "";
        if (!empty($locale)) {
            $sm = $app->getServiceManager();
            $translator = $sm->get('Laminas\I18n\Translator\TranslatorInterface');
            $translator->setLocale($locale);
        }
    }
}
