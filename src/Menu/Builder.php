<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class to build some menus for navigation.
 */
class Builder implements ContainerAwareInterface {
    use ContainerAwareTrait;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage) {
        $this->factory = $factory;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
    }

    private function hasRole($role) {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }

    /**
    * Build a menu for entities.
    *
    * @return ItemInterface
    */
    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);
        $menu->setAttribute('dropdown', true);

        $browse = $menu->addChild('Browse', [
            'uri' => '#',
            'label' => 'Media',
        ]);
        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

                    $browse->addChild('Book', [
                'route' => 'book_index',
            ]);
                    $browse->addChild('Person', [
                'route' => 'person_index',
            ]);
        
        if($this->hasRole('ROLE_CONTENT_ADMIN')) {
            $browse->addChild('content_divider', [
                'label' => '',
            ]);
            $browse['content_divider']->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $browse->addChild('Content Admin', [
                'uri' => '#',
            ]);
        }

        if($this->hasRole('ROLE_ADMIN')) {
            $browse->addChild('admin_divider', [
                'label' => '',
            ]);
            $browse['admin_divider']->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $browse->addChild('Admin', [
                'uri' => '#',
            ]);
        }

        return $menu;
    }

    /**
    * Build a menu for the footer.
    *
    * @return ItemInterface
    */
    public function footerMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav',
        ]);
        $menu->addChild('Home', [
            'route' => 'homepage',
        ]);

        $menu->addChild('Privacy', [
            'route' => 'privacy',
        ]);

        return $menu;
    }

}
