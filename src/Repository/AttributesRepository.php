<?php

namespace App\Repository;

use App\Entity\Attributes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Attributes>
 */
class AttributesRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, private AttributeGroupsRepository $attributeGroupsRepository)
    {
        parent::__construct($registry, Attributes::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function saveAttributeData(array $attribute)
    {
        $attributeData = $this->findOneBy(['code' => $attribute['code']]);
        $attributeInstance = !empty($attributeData) ? $attributeData : new Attributes;        
        $label = !empty($attribute['label']) ? $attribute['label'] : $attribute['code'];
        
        $attributeGroup = $this->attributeGroupsRepository->findOneBy(['code' => $attribute['group']]);
        $groupLabel = !empty($attributeGroup) ? $attributeGroup->getLabel() : $attribute['group'];

        $attributeInstance->setCode($attribute['code']);
        $attributeInstance->setLabel($label);
        $attributeInstance->setAttributeGroupCode($attribute['group']);
        $attributeInstance->setAttributeGroupLabel($groupLabel);
        $attributeInstance->setType($attribute['type']);



        $this->entityManager->persist($attributeInstance);
        $this->entityManager->flush();       
        
    }

    public function getAttributeCodeByAttributeGroup(array $data)
    {
        return $this->findOneBy(['label' => $data['attribute'], 'attributeGroupLabel' => $data['group']]);
    }
    //    /**
    //     * @return Attributes[] Returns an array of Attributes objects
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

    //    public function findOneBySomeField($value): ?Attributes
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
