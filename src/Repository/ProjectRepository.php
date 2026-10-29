<?php

namespace App\Repository;

use App\Entity\Project;
use App\Enum\ProjectStatus;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

   /**
    * @return Project[] Returns an array of Project objects
    */
    public function findByStatus(ProjectStatus $status): array
    {
        return $this->createQueryBuilder('p')
                ->andWhere('p.status = :status')
                ->setParameter('status', $status)
                ->getQuery()
                ->getResult()
        ;
    }

   public function findByUserAndStatus(User $user, ProjectStatus $status): array
   {
       return $this->createQueryBuilder('p')
            ->innerJoin('p.users', 'u')
            ->andWhere('u = :user')
            ->andWhere('p.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult()
       ;
   }
}
