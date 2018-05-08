<?php
/**
 * Created by PhpStorm.
 * User: luisriego
 * Date: 07/05/2018
 * Time: 15:28
 */

namespace AppBundle\EventListener;


use AppBundle\Services\UserManager;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class AsignarPrivilegiosPrimerUsuarioSubscriber implements EventSubscriberInterface
{
    private $router;
    private $userManager;

    public function __construct(RouterInterface $router, UserManager $userManager)
    {
        $this->router = $router;
        $this->userManager = $userManager;
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        dump($event->getForm()->getData());die();
        $esPrimerUsuario = $this->userManager->primerUsuario();

        if ($esPrimerUsuario) {
            $url = $this->router->generate('homepage');
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        ];
    }

}