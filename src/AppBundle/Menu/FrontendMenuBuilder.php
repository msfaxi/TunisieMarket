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

    /**
     * Builds frontend main menu.
     *
     * @return ItemInterface
     */
    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'nav nav-pills',
            ],
        ]);
        $menu->setCurrentUri($this->request->getRequestUri());

       /* if ($this->cartProvider->hasCart()) {
            $cart = $this->cartProvider->getCart();
            $cartTotals = ['items' => $cart->getTotalQuantity(), 'total' => $cart->getTotal()];
        } else {
            $cartTotals = ['items' => 0, 'total' => 0];
        }

        $menu->addChild('cart', [
            'route' => 'sylius_cart_summary',
            'linkAttributes' => ['title' => $this->translate('sylius.frontend.menu.main.cart', [
                '%items%' => $cartTotals['items'],
                '%total%' => $this->currencyHelper->convertAndFormatAmount($cartTotals['total']),
            ])],
            'labelAttributes' => ['icon' => 'icon-shopping-cart icon-large'],
        ])->setLabel($this->translate('sylius.frontend.menu.main.cart', [
            '%items%' => $cartTotals['items'],
            '%total%' => $this->currencyHelper->convertAndFormatAmount($cartTotals['total']),
        ]));*/

        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $route = $this->request === null ? '' : $this->request->get('_route');

            if (1 === preg_match('/^(sylius_account)/', $route)) {
                $menu->addChild('shop', [
                    'route' => 'sylius_homepage',
                    'linkAttributes' => ['title' => $this->translate('sylius.frontend.menu.account.shop')],
                    'labelAttributes' => ['icon' => 'icon-th icon-large', 'iconOnly' => false],
                ])->setLabel($this->translate('sylius.frontend.menu.account.shop'));
            } else {
                $menu->addChild('account', [
                    'route' => 'sylius_account_profile_show',
                    'linkAttributes' => ['title' => $this->translate('sylius.frontend.menu.main.account')],
                    'labelAttributes' => ['icon' => 'icon-user icon-large', 'iconOnly' => false],
                ])->setLabel($this->translate('sylius.frontend.menu.main.account'));
            }

            $menu->addChild('logout', [
                'route' => 'sylius_user_security_logout',
                'linkAttributes' => ['title' => $this->translate('sylius.frontend.menu.main.logout')],
                'labelAttributes' => ['icon' => 'icon-off icon-large', 'iconOnly' => false],
            ])->setLabel($this->translate('sylius.frontend.menu.main.logout'));
        } else {
            $menu->addChild('login', [
                'route' => 'sylius_user_security_login',
                'linkAttributes' => ['title' => $this->translate('sylius.frontend.menu.main.login')],
                'labelAttributes' => ['icon' => 'icon-lock icon-large', 'iconOnly' => false],
            ])->setLabel($this->translate('sylius.frontend.menu.main.login'));
            $menu->addChild('register', [
                'route' => 'sylius_user_registration',
                'linkAttributes' => ['title' => $this->translate('sylius.frontend.menu.main.register')],
                'labelAttributes' => ['icon' => 'icon-user icon-large', 'iconOnly' => false],
            ])->setLabel($this->translate('sylius.frontend.menu.main.register'));
        }

        if ($this->authorizationChecker->isGranted('ROLE_ADMINISTRATION_ACCESS') || $this->authorizationChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
            $routeParams = [
                'route' => 'sylius_backend_dashboard',
                'linkAttributes' => ['title' => $this->translate('sylius.frontend.menu.main.administration')],
                'labelAttributes' => ['icon' => 'icon-briefcase icon-large', 'iconOnly' => false],
            ];

            if ($this->authorizationChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
                $routeParams = array_merge($routeParams, [
                    'route' => 'sylius_switch_user_return',
                    'routeParameters' => [
                        'username' => $this->tokenStorage->getToken()->getUsername(),
                        '_switch_user' => '_exit',
                    ],
                ]);
            }

            $menu->addChild('administration', $routeParams)->setLabel($this->translate('sylius.frontend.menu.main.administration'));
        }

        $this->eventDispatcher->dispatch(MenuBuilderEvent::FRONTEND_MAIN, new MenuBuilderEvent($this->factory, $menu));

        return $menu;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
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
