<?php
/**
 * Created by PhpStorm.
 * User: luisriego
 * Date: 08/05/2018
 * Time: 15:43
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\User;
use AppBundle\Services\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserRegister
{
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager)
    {

        $this->userManager = $userManager;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntity();
        $retorno = '';


        // Solo pasa de este if si es una entidad 'User'
        if (!$em instanceof User) {
            return;
        }

//        $args->getObject()->addRole('ROLE_USER');
//        $args->getObject()->addRole('ROLE_TECNICO');
        if ($this->userManager->primerUsuario()) {
            $args->getObject()->addRole('ROLE_ADMIN');
//            dump('ante de guardar', $args->getObject());die();
        }


    }

    private function _primerUsuario($em)
    {
        $usuarios = $em->getRepository('AppBundle:User')->findAll();
        if (empty($usuarios)) {
            return true;
        } else {
            return false;
        }
    }

}