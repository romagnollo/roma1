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



class Mirasvit_SearchIndex_Block_Index_Template extends Mage_Core_Block_Template
{
    protected $_collection = null;
    protected $_isVisible = true;

    public function getIsVisible()
    {
        return $this->_isVisible;
    }

    public function setIsVisible($flag)
    {
        $this->_isVisible = $flag;

        return $this;
    }

    public function getIndex()
    {
        return Mage::helper('searchindex/index')->getIndex($this->getIndexCode());
    }

    public function _toHtml()
    {
        if ($this->getIndex() && $this->getIndex()->getIsActive()) {
            return parent::_toHtml();
        }
    }

    public function getCollection()
    {
        $exceptions = array(
            'mage_catalog_attribute',
            'infortis_brands_item'
        );

        if ($this->_collection == null) {
            $this->_collection = $this->getIndex()
                ->getCollection();

            if (!in_array($this->getIndexCode(), $exceptions)) {
                $this->_collection->getSelect()->order('relevance desc');
            }
        }

        return $this->_collection;
    }

    /**
     * Return pager html for current collection.
     *
     * @return html
     */
    public function getPager()
    {
        $pager = $this->getChild('pager');
        if (!$pager->getCollection()) {
            $pager->setCollection($this->getCollection());
        }

        return $pager->toHtml();
    }
}
