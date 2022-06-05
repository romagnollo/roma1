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



class Mirasvit_SearchIndex_Model_Index_Azebiz_Support_Kbarticle_Index extends Mirasvit_SearchIndex_Model_Index
{
    public function getBaseGroup()
    {
        return 'MageBuzz';
    }

    public function getBaseTitle()
    {
        return 'Mageticket / Knowledge Base';
    }

    public function canUse()
    {
        return Mage::getConfig()->getModuleConfig('Azebiz_Support')->is('active', 'true');
    }

    public function getPrimaryKey()
    {
        return 'kb_article_id';
    }

    public function getAvailableAttributes()
    {
        $result = array(
            'title' => Mage::helper('searchindex')->__('Title'),
            'kb_article_content' => Mage::helper('searchindex')->__('Content'),
        );

        return $result;
    }

    public function getCollection()
    {
        $collection = Mage::getModel('support/kbarticle')->getCollection();
        $collection->addFieldToFilter('status', 1);

        $this->joinMatched($collection, 'main_table.kb_article_id');

        return $collection;
    }
}
