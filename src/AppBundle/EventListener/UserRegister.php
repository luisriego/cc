<?php
/**
 * Created by PhpStorm.
 * User: luisriego
 * Date: 08/05/2018
 * Time: 15:43
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\User;
use AppBundle\Services\Inicialization;
use AppBundle\Services\UserManager;
use function DI\string;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserRegister
{

    private $userManager;
    private $inicialization;

    public function __construct(UserManager $userManager, Inicialization $inicialization)
    {

        $this->userManager = $userManager;
        $this->inicialization = $inicialization;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntity();
        $retorno = '';


        // Solo pasa de este if si es una entidad 'User'
        if (!$em instanceof User) {
            return;
        }

        // Solo pasa el if si es el primer usuario que se registra, por tanto lo consideramos el administrador.
        if ($this->userManager->primerUsuario()) {

            // primero utilizaremos el Email informado para crear el primer Cliente
            $email = $args->getObject()->getEmail();
            // Inicializamos la Aplicación...
            $cliente = $this->inicialization->inicializeApp($email);

            // Cómo es el primer usuario que se registra, le asignamos un role de administrador.
            $args->getObject()->addRole('ROLE_ADMIN');
            // le asignamos también, el cliente creado en la inicialización.
            $args->getObject()->setEmpresa($cliente);

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