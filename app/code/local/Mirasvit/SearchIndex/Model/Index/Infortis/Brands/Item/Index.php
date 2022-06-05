<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/extension_searchsphinx
 * @version   2.3.48
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */



class Mirasvit_SearchIndex_Model_Index_Infortis_Brands_Item_Index extends Mirasvit_SearchIndex_Model_Index
{
    public function getBaseGroup()
    {
        return 'Infortis';
    }

    public function getBaseTitle()
    {
        return 'Brands';
    }

    public function getPrimaryKey()
    {
        return 'value';
    }

    public function getAvailableAttributes()
    {
        $result = array(
            'label' => Mage::helper('searchindex')->__('Option Label'),
        );

        return $result;
    }

    public function getCollection()
    {
        $matchedIds = $this->getMatchedIds();

        $attributeCode = 'brand';
        $attribute = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
        $options = $attribute->getSource()->getAllOptions(false);

        $collection = new Varien_Data_Collection();
        foreach ($options as $option) {
            if (isset($matchedIds[$option['value']])) {
                $obj = new Varien_Object();
                $obj->addData($option);
                $collection->addItem($obj);
            }
        }

        return $collection;
    }

    public function getUrl($item)
    {
        $separator = trim(Mage::helper('brands')->getCfg('general/url_key_separator'));
        $formattedBrand = Mage::helper('catalog/product_url')->format($item->getData('label'));

        $urlKey = preg_replace('#[^0-9a-z]+#i', $separator, $formattedBrand);
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, $separator);

        $url = '/'. $urlKey;
        return $url;
    }
}
