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


class Mirasvit_SearchIndex_Model_Index_Blackbird_Contentmanager_Content_Indexer extends Mirasvit_SearchIndex_Model_Indexer_Abstract
{
    protected function _getSearchableEntities($storeId, $entityIds, $lastEntityId, $limit = 100)
    {
        $searchableCtIds = $this->getSearchableCtIds();

        $collection = Mage::getModel('contentmanager/content')->getCollection();
        $collection->addStoreFilter($storeId)
            ->addFieldToFilter('status', 1);

        if ($entityIds) {
            $collection->addFieldToFilter('entity_id', array('in' => $entityIds));
        }

        $collection->getSelect()->where('e.entity_id > ?', $lastEntityId)
            ->limit($limit)
            ->order('e.entity_id');

        $collection->addAttributeToFilter('ct_id', array('in' => $searchableCtIds));
        $collection->addAttributeToSelect('*');

        return $collection;
    }

    public function getSearchableCtIds()
    {
        $collection = Mage::getModel('contentmanager/contenttype')
            ->getCollection()
            ->addFieldToFilter('search_enabled', 1);

        return $collection->getAllIds();
    }
}