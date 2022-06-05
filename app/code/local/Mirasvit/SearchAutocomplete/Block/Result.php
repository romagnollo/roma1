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
 * @package   mirasvit/extension_searchautocomplete
 * @version   1.2.14
 * @copyright Copyright (C) 2018 Mirasvit (https://mirasvit.com/)
 */



/**
 * Class for rendering search results.
 * Main task - render child blocks of all included indexes, restrict number of rendered elements.
 *
 * @category Mirasvit
 */
class Mirasvit_SearchAutocomplete_Block_Result extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @var Array of search index collections
     */
    protected $_collections = array();

    /**
     * @var Array of search indexes
     */
    protected $_indexes = array();

    /**
     * {@inheritdoc}
     */
    public function _prepareLayout()
    {
        $this->setTemplate('mst_searchautocomplete/autocomplete/result.phtml');

        return parent::_prepareLayout();
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->_prepareIndexes();
    }

    /**
     * Prepare collection for each index, calculate and set size of each collection
     * @return void
     */
    protected function _prepareIndexes()
    {
        // Mage::dispatchEvent('searchautocomplete_prepare_collection');

        if (!Mage::getStoreConfig('searchautocomplete/general/indexes')) {
            $this->_indexes = Mage::helper('searchautocomplete')->getIndexes(true);
        } else {
            $this->_indexes = Mage::helper('searchautocomplete')->getIndexes(false);
        }

        $maxCount = Mage::getStoreConfig('searchautocomplete/general/max_results');
        $perIndex = ceil($maxCount / count($this->_indexes));
        $tmpIndexes = $this->_indexes;
        $sizes = array();
        $additional = 0;
        foreach ($this->_indexes as $index => $label) {
            $st = microtime(true);
            $size = $this->getCollection($index)->getSize();

            if ($size >= $perIndex) {
                $sizes[$index] = $perIndex;
            } else {
                $additional += ($perIndex - $size);
                $sizes[$index] = $size;
                unset($tmpIndexes[$index]);
            }

            if ($size == 0) {
                unset($this->_indexes[$index]);
            }

            if ($this->getIndexFilter() && $index != $this->getIndexFilter()) {
                unset($this->_indexes[$index]);
            }
        }

        /* 
         *  Add additional size only to those indexes,
         *  whose size is greater than the size allocated for each index ($perIndex)
         */
        $additional = $tmpIndexes ? ceil($additional / count($tmpIndexes)) : 0;
        foreach (array_intersect($this->_indexes, $tmpIndexes) as $index => $label) {
            $sizes[$index] += $additional;
        }

        foreach ($sizes as $index => $size) {
            $this->getCollection($index)->setPageSize($size);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexes()
    {
        return $this->_indexes;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection($index)
    {
        $exceptions = array(
            'mage_catalog_attribute',
            'infortis_brands_item'
        );
        
        if (!isset($this->_collections[$index])) {
            if (Mage::helper('core')->isModuleEnabled('Mirasvit_SearchIndex')) {
                $model = Mage::helper('searchindex/index')->getIndex($index);
                $collection = $model->getCollection();
            } elseif (Mage::helper('core')->isModuleEnabled('Enterprise_Search')) {
                $collection = Mage::getSingleton('enterprise_search/search_layer')->getProductCollection();
            } else {
                $collection = Mage::getSingleton('catalogsearch/layer')->getProductCollection();
            }

            if (!in_array($index, $exceptions) && !Mage::helper('core')->isModuleEnabled('Enterprise_Search') 
                && Mage::helper('core')->isModuleEnabled('Mirasvit_SearchIndex')) {
                $collection->getSelect()->order('relevance desc');
            }

            if ($index == 'mage_catalog_product' && $this->getCategoryId()) {
                $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
                $collection->addCategoryFilter($category);
            }

            $this->_collections[$index] = $collection;
        }

        return $this->_collections[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function getItemHtml($index, $item)
    {
        $blockHtml = Mage::app()->getLayout()->createBlock('searchautocomplete/result')
            ->setTemplate('mst_searchautocomplete/autocomplete/index/'.str_replace('_', '/', $index).'.phtml')
            ->setItem($item)->toHtml();

        if ($index == 'mage_catalog_product' && Mage::helper('core')->isModuleEnabled('Mageplace_Hideprice')) {
            Mage::getSingleton('mageplace_hideprice/observer')->replaceForProduct($blockHtml,$item);
        }

        return $blockHtml;
    }

    /**
     * Provide product for configurable products to show
     * in-stock product image
     * @param object $product - product entity
     *
     * @return object $product - in-stock product entity  
     */
    public function getInStockProductForImage($product)
    {
        if ($product->getTypeId() == "configurable") {
            $products = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);
            foreach ($products as $p) {
                $associatedProduct = Mage::getModel('catalog/product')->load($p->getId());
                if (Mage::getModel('cataloginventory/stock_item')->loadByProduct($associatedProduct)->getIsInStock()) {
                    if ($associatedProduct->getThumbnail() != null &&
                        $associatedProduct->getThumbnail() !== 'no_selection') {

                        $product = $associatedProduct;
                        break;
                    }
                }
            }
        }

        return $product;
    }

    /**
     * Provide description to template
     * @param object $product - product entity
     *
     * @return string 
     */
    public function getProductShortDescription($product)
    {
        $shortDescription = $product->getShortDescription();
        if (Mage::helper('mstcore')->isModuleInstalled('Mirasvit_Seo') &&
            method_exists(Mage::helper('seo'), 'getCurrentSeoShortDescriptionForSearch')
        ) {
            if ($seoShortDescription = Mage::helper('seo')->getCurrentSeoShortDescriptionForSearch($product)) {
                $shortDescription = $seoShortDescription;
            }
        }

        return $shortDescription;
    }

    /**
     * Build full category path.
     *
     * @param int $categoryId - ID of found category
     *
     * @return false|array
     */
    public function getFullPath($categoryId)
    {
        $result = array();
        $id = $categoryId;
        $rootId = Mage::app()->getStore()->getRootCategoryId();

        do {
            $parent = Mage::getModel('catalog/category')->load($id)->getParentCategory();
            $id = $parent->getId();

            if (!$parent->getId()) {
                break;
            }

            if (!$parent->getIsActive() && $parent->getId() != $rootId) {
                return false;
            }

            if ($parent->getId() != $rootId) {
                $result[] = $parent;
            }
        } while ($parent->getId() != $rootId);

        $result = array_reverse($result);

        return $result;
    }
}
