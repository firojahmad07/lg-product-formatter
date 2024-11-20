<?php

namespace App\Repository;

use App\Entity\AttributeOptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AttributeOptions>
 */
class AttributeOptionsRepository extends ServiceEntityRepository
{
    private $entityManager;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttributeOptions::class);
        $this->entityManager = $this->getEntityManager();
    }
    
    public function saveAttributeOptionData(array $attributeOption)
    {

        $option = $this->findOneBy(['code' => $attributeOption['code'], 'attribute' => $attributeOption['attribute']]);
        $optionInstance = !empty($option) ? $option : new AttributeOptions;
        
        $optionInstance->setCode($attributeOption['code']);
        $label = !empty($attributeOption['label']) ? $attributeOption['label'] : $attributeOption['code'];
        $optionInstance->setLabel($label);
        $optionInstance->setAttribute($attributeOption['attribute']);

        $this->entityManager->persist($optionInstance);
        $this->entityManager->flush();       
        
    }

    public function getSimpleSelectOptions(string $attributeCode, $optionLabel)
    {
        $attributeOption = $this->findOneBy(['attribute' => $attributeCode, 'label' => $optionLabel]);

        return !empty($attributeOption) ? $attributeOption->getCode() : $optionLabel;
    }

    public function getMultiSelectOptions(string $attributeCode, string $optionLabel)
    {
        $seprator = str_contains($optionLabel, ':') ? " / " : " , ";
        $allOptions = explode($seprator, $optionLabel);

        $codes = [];
        foreach($allOptions as $option) {
            $codes[] = $this->getSimpleSelectOptions($attributeCode, $option);
        }

        return implode(',', $codes);
    }

    //    /**
    //     * @return AttributeOptions[] Returns an array of AttributeOptions objects
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

    //    public function findOneBySomeField($value): ?AttributeOptions
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
