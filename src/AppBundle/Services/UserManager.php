<?php
/**
 * Created by PhpStorm.
 * User: luisriego
 * Date: 08/05/2018
 * Time: 14:40
 */

namespace AppBundle\Services;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Event\FormEvent;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->em = $entityManager;
    }

    public function primerUsuario()
    {
        $usuarios = $this->em->getRepository('AppBundle:User')->findAll();
        if (empty($usuarios)) {
            return true;
        } else {
            return false;
        }
    }

//    public function primerUsuario(FormEvent $event)
//    {
//        $retorno = null;
//        $usuarios = $this->em->getRepository('AppBundle:User')->findAll();
//        if (empty($usuarios)) {
//            $usuario = new User($event->getForm()->getData());
//            dump($event->getForm()->getData(), $usuario);
//            $retorno = 'blz entro en el if';
//        } else {
//            $retorno = 'no entró en el if';
//        }
//
//        return $retorno;
//    }
}