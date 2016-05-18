<?php
namespace AppBundle\EventListener;

use Sylius\Bundle\WebBundle\Event\MenuBuilderEvent;

class MenuBuilderListener
{
    public function addBackendMenuItems(MenuBuilderEvent $event)
    {
        $menu = $event->getMenu();

        $menu['sales']->addChild('reports', array(
            'route' => 'acme_reports_index',
            'labelAttributes' => array('icon' => 'glyphicon glyphicon-stats'),
        ))->setLabel('Daily and monthly reports');
    }
}