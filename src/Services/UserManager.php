<?php
/**
 * Created by PhpStorm.
 * User: luisriego
 * Date: 08/05/2018
 * Time: 14:40
 */

namespace App\Services;


use App\Entity\User;
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

}