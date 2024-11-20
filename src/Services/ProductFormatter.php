<?php
namespace App\Services;

use App\Helpers\DataFormatterHelper;
use App\Repository\AttributesRepository;
use App\Repository\AttributeOptionsRepository;

class ProductFormatter
{
    public function __construct(
        private DataFormatterHelper $dataFormatterHelper,
        private AttributesRepository $attributesRepository,
        private AttributeOptionsRepository $attributeOptionsRepository
    ) {

    }
    public function formatProductData(array $product): array
    {
        $data = [];
        foreach($product as $attribute => $value)
        {
            $attributeAndGroupData = $this->dataFormatterHelper::parseStringWithRegex($attribute);
            $attributeData = $this->attributesRepository->getAttributeCodeByAttributeGroup($attributeAndGroupData);        
            $attributeCode = !empty($attributeData) ? $attributeData->getCode() : $attribute;
            $data[$attributeCode] = $this->getAttributeValue($attributeData, $value);
        }

        return $data;
    }

    public function getAttributeValue($attributeData, $value)
    {
        $attributeValue = $value;
        if (!empty($attributeData)) {
            switch ($attributeData->getType()) {
                case 'pim_catalog_simpleselect':
                        if(!is_null($value) || !empty($value)) {
                            $attributeValue = $this->attributeOptionsRepository->getSimpleSelectOptions($attributeData->getCode(), $value);
                        }
                    break;
                case 'pim_catalog_multiselect':
                        if(!is_null($value) || !empty($value)) {
                            $attributeValue = $this->attributeOptionsRepository->getMultiSelectOptions($attributeData->getCode(), $value);
                        }
                    break;
                case 'pim_catalog_text':           
                case 'pim_catalog_number':           
                case 'pim_catalog_date':        
                case 'pim_catalog_textarea':              
                default:
                        $attributeValue = $value;
                    break;
            }
        }

        return $attributeValue;
    }
}