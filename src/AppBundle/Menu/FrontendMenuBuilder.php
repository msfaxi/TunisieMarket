<?php

namespace AppBundle\Menu;

use Sylius\Bundle\WebBundle\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Sylius\Bundle\WebBundle\Menu\FrontendMenuBuilder as BaseFrontendMenuBuilder;
/**
 * Frontend menu builder.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class FrontendMenuBuilder extends BaseFrontendMenuBuilder
{
    public function createSocialMenu()
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'nav nav-pills pull-right',
            ],
        ]);

        $menu->addChild('github', [
            'uri' => 'https://github.com/tunisieMarket',
            'linkAttributes' => ['title' => $this->translate('sylius.frontend.menu.social.github')],
            'labelAttributes' => ['icon' => 'icon-github-sign icon-large', 'iconOnly' => true],
        ]);
        $menu->addChild('twitter', [
            'uri' => 'https://twitter.com/tunisieMarket',
            'linkAttributes' => ['title' => $this->translate('sylius.frontend.menu.social.twitter')],
            'labelAttributes' => ['icon' => 'icon-twitter-sign icon-large', 'iconOnly' => true],
        ]);
        $menu->addChild('facebook', [
            'uri' => 'http://facebook.com/tunisieMarket',
            'linkAttributes' => ['title' => $this->translate('sylius.frontend.menu.social.facebook')],
            'labelAttributes' => ['icon' => 'icon-facebook-sign icon-large', 'iconOnly' => true],
        ]);

        $this->eventDispatcher->dispatch(MenuBuilderEvent::FRONTEND_SOCIAL, new MenuBuilderEvent($this->factory, $menu));

        return $menu;
    }
}
