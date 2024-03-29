<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    /**
     * @param int $limite
     * @return mixed
     */
    public function findAllUsers($limite = 10)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
                                SELECT u
                                FROM App:User u
                                ORDER BY u.lastLogin
                                ');
        $consulta->setMaxResults($limite);
        return $consulta->getArrayResult();
    }
    
    public function findOneById(User $user)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
                                SELECT u, p, e
                                FROM App:User u
                                JOIN u.profile p
                                JOIN u.endereco e
                                WHERE u.id = :user
                                ORDER BY u.lastLogin
                                ');
        $consulta->setParameter('user', $user->getId());
        return $consulta->getOneOrNullResult();
    }

    public function findOneByIdFull($user)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
                                SELECT u, p, e, c
                                FROM App:User u
                                JOIN u.profile p
                                JOIN u.endereco e
                                JOIN u.empresa c
                                WHERE u.id = :user
                                ');
        $consulta->setParameter('user', $user);
        return $consulta->getOneOrNullResult();
    }

//    /**
//     * @param $usuario
//     * @param DateTime $desde
//     * @param DateTime $hasta
//     * @return array
//     */
//    public function findChamadosByUser($usuario, DateTime $desde = '2016-01-01',DateTime $hasta)
//    {
//        $em = $this->getEntityManager();
//        $consulta = $em->createQuery('
//                                SELECT c
//                                FROM App:Chamado c
//                                WHERE c.nome = :usuario
//                                AND c.data > :desde
//                                AND c.data < :hasta
//                                ');
//        $consulta->setParameter('usuario', $usuario);
//        $consulta->setParameter('desde', $desde);
//        $consulta->setParameter('hasta', $hasta);
//        return $consulta->getResult();
//    }
}