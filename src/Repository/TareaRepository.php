<?php

namespace App\Repository;

use App\Entity\Tarea;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tarea|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tarea|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tarea[]    findAll()
 * @method Tarea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TareaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tarea::class);
    }

    public function findByUserCurrent(User $user): array{
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT t.titulo, t.descripcion, t.prioridad, t.id
             FROM App:Tarea t
             JOIN t.userasing user
             WHERE t.userasing = :id AND t.estado = true
             ORDER BY t.prioridad DESC
             '
        )->setParameter('id',$user);
        return $query->getResult();
    }

    public function tareasSinFinalizar():array{
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT t.titulo, t.descripcion, t.prioridad, userasing.nombrre
            FROM App:Tarea t
            JOIN t.userasing userasing
            WHERE t.estado = true
            ORDER BY t.prioridad DESC
                    ');
        return $query->getResult();
    }
}
