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



class Mirasvit_SearchIndex_Model_Index_Mirasvit_Giftr_Registry_Indexer extends Mirasvit_SearchIndex_Model_Indexer_Abstract
{
    protected function _getSearchableEntities($storeId, $entityIds, $lastEntityId, $limit = 100)
    {
        $collection = Mage::getModel('giftr/registry')->getCollection();
        $collection->addFieldToFilter('main_table.is_active', 1);

        if ($entityIds) {
            $collection->addFieldToFilter('registry_id', array('in' => $entityIds));
        }

        $collection->getSelect()->where('main_table.registry_id > ?', $lastEntityId)
            ->limit($limit)
            ->order('main_table.registry_id');

        return $collection;
    }
}
