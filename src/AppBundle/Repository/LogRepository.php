<?php

namespace AppBundle\Repository;

/**
 * LogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LogRepository extends \Doctrine\ORM\EntityRepository
{
//    /**
//     * @param $id
//     * @return array
//     */
//    public function findLogsById($id)
//    {
//        $em = $this->getEntityManager();
//        $consulta = $em->createQuery('
//                                SELECT l, ch, an, a, c
//                                FROM AppBundle:Log l
//                                JOIN l.chamado ch
//                                JOIN ch.cliente c
//                                JOIN l.anterior an
//                                JOIN l.atual a
//                                WHERE l.chamado = :id
//                                ORDER BY l.data
//                                ');
//        $consulta->setParameter('id', $id);
//        return $consulta->getArrayResult();
//    }

    /**
     * @param $id
     * @return array
     */
    public function findLogsById($id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
                                SELECT l, ch, an, a
                                FROM AppBundle:Log l
                                JOIN l.chamado ch
                                JOIN l.anterior an
                                JOIN l.atual a
                                WHERE l.chamado = :id
                                ORDER BY l.data
                                ');
        $consulta->setParameter('id', $id);
        return $consulta->getArrayResult();
    }
}
