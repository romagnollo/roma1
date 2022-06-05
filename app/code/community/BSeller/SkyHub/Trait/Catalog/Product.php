<?php
/**
 * BSeller Platform | B2W - Companhia Digital
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  BSeller
 * @package   BSeller_SkyHub
 *
 * @copyright Copyright (c) 2018 B2W Digital - BSeller Platform. (http://www.bseller.com.br)
 *
 * Access https://ajuda.skyhub.com.br/hc/pt-br/requests/new for questions and other requests.
 */

trait BSeller_SkyHub_Trait_Catalog_Product
{

    /**
     * @param BSeller_SkyHub_Model_Catalog_Product             $product
     * @param string|Mage_Eav_Model_Entity_Attribute $attribute
     * @return array|bool|mixed|string
     */
    protected function productAttributeRawValue(Mage_Catalog_Model_Product $product, $attribute)
    {
        if ($attribute instanceof Mage_Eav_Model_Entity_Attribute) {
            $attribute = $attribute->getAttributeCode();
        }
    
        $data = $product->getData($attribute);

        if (empty($data)) {
            try {
                $data = $this->getCatalogProductResource()
                    ->getAttributeRawValue($product->getId(), $attribute, Mage::app()->getStore());
                return $data;
            } catch (Exception $e) {}
        }

        return $data;
    }

    /**
     * @return BSeller_SkyHub_Model_Resource_Catalog_Product
     */
    protected function getCatalogProductResource()
    {
        /** @var BSeller_SkyHub_Model_Resource_Catalog_Product $resource */
        $resource = Mage::getResourceModel('bseller_skyhub/catalog_product');
        return $resource;
    }

    /**
     * @param BSeller_SkyHub_Model_Catalog_Product             $product
     * @param Mage_Eav_Model_Entity_Attribute|string $attribute
     *
     * @return float|null
     */
    protected function extractProductPrice(Mage_Catalog_Model_Product $product, $attribute = null)
    {
        if ($attribute instanceof Mage_Eav_Model_Entity_Attribute) {
            $attribute = $attribute->getAttributeCode();
        }
        
        if (empty($attribute)) {
            $attribute = 'price';
        }
        
        $price = $this->productAttributeRawValue($product, $attribute);
        
        if (!empty($price)) {
            return $price;
        }
        
        return null;
    }
    
    
    /**
     * @param BSeller_SkyHub_Model_Catalog_Product $product
     * @param null|string                $attributeCode
     * @param null|float                 $comparedPrice
     *
     * @return float
     */
    protected function extractProductSpecialPrice(
        Mage_Catalog_Model_Product $product,
        $attributeCode = null,
        $comparedPrice = null
    )
    {
        if (empty($attributeCode)) {
            $attributeCode = 'special_price';
        }
    
        $specialPrice   = $this->productAttributeRawValue($product, $attributeCode);
        $fromDate       = $this->extractProductSpecialFromDate($product);
        $toDate         = $this->extractProductSpecialToDate($product);

        return $this->getSpecialPrice($specialPrice, $comparedPrice, $fromDate, $toDate);
    }
    
    
    /**
     * @param BSeller_SkyHub_Model_Catalog_Product $product
     *
     * @return string
     */
    protected function extractProductSpecialFromDate(Mage_Catalog_Model_Product $product, $attributeCode = null)
    {
        if (empty($attributeCode)) {
            $attributeCode = 'special_from_date';
        }
    
        return (string) $this->productAttributeRawValue($product, $attributeCode);
    }
    
    
    /**
     * @param BSeller_SkyHub_Model_Catalog_Product $product
     *
     * @return string
     */
    protected function extractProductSpecialToDate(Mage_Catalog_Model_Product $product, $attributeCode = null)
    {
        if (empty($attributeCode)) {
            $attributeCode = 'special_to_date';
        }

        $result = $this->productAttributeRawValue($product, $attributeCode);

        if (is_string($result)) {
            return (string)$result;
        }
        return '';
    }
    
    
    /**
     * @param float       $specialPrice
     * @param float       $price
     * @param string|null $fromDate
     * @param string|null $toDate
     *
     * @return bool
     */
    protected function getSpecialPrice($specialPrice, $price = null, $fromDate = null, $toDate = null)
    {
        $specialPrice = $specialPrice;

        if (empty($specialPrice)) {
            return '';
        }

        if (!is_null($price) && (((float) $price) < $specialPrice)) {
            return '';
        }
        
        if (!empty($fromDate) && (strtotime(now()) < strtotime($fromDate))) {
            return '';
        }
        
        if (!empty($toDate) && (strtotime(now()) > strtotime($toDate))) {
            return '';
        }
        
        return (float) $specialPrice;
    }
    
    
    /**
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     *
     * @return bool
     */
    protected function validateProductAttribute(Mage_Eav_Model_Entity_Attribute $attribute)
    {
        if (!$attribute) {
            return false;
        }
        
        if (!$attribute->getAttributeId()) {
            return false;
        }
        
        if (!$attribute->getAttributeCode()) {
            return false;
        }
        
        return true;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     *
     * @return bool
     */
    protected function hasAllowedVisibility(Mage_Catalog_Model_Product $product)
    {
        $productVisibilities = $this->getSkyHubModuleConfigAsArray('integration_product_visibility', 'general');
        if (in_array($product->getVisibility(), $productVisibilities)) {
            return true;
        }
        return false;
    }

    /**
     * Get collection of product categories with extra attributes
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function getProductCategories(Mage_Catalog_Model_Product $product)
    {
        /** @var Mage_Catalog_Model_Resource_Category_Collection $collection */
        $collection = Mage::getResourceModel('catalog/category_collection');
        $collection->joinField('product_id',
            'catalog/category_product',
            'product_id',
            'category_id = entity_id',
            null)
            ->addFieldToFilter('product_id', (int)$product->getId());

        /** @var Mage_Core_Model_Config_Element $attributes */
        $attributes = Mage::getConfig()->getNode('adminhtml/category/collection/attributes');
        if ($attributes) {
            $attributes = $attributes->asArray();
            $attributes = array_keys($attributes);
        }
        $collection->addAttributeToSelect($attributes);

        return $collection;
    }
}
