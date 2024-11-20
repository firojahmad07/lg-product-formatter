<?php

namespace App\Repository;

use App\Entity\AttributeGroups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AttributeGroups>
 */
class AttributeGroupsRepository extends ServiceEntityRepository
{
    private $entityManager;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttributeGroups::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function saveAttributeGroupData(array $attributeGroup)
    {

        $group = $this->findOneBy(['code' => $attributeGroup['code']]);
        $groupInstance = !empty($group) ? $group : new AttributeGroups;

        $groupInstance->setCode($attributeGroup['code']);
        $groupInstance->setLabel($attributeGroup['label']);
        if (isset($attributeGroup['attributes'])) {
            $groupInstance->setAttributes(explode(',', $attributeGroup['attributes']));
            
        }

        $this->entityManager->persist($groupInstance);
        $this->entityManager->flush();       
        
    }
    //    /**
    //     * @return AttributeGroups[] Returns an array of AttributeGroups objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AttributeGroups
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
