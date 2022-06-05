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



class Mirasvit_SearchIndex_Block_Adminhtml_Report_View extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'searchindex';
        $this->_controller = 'adminhtml_report';
        $this->_mode = 'view';

        parent::__construct();

        $this->removeButton('reset')
            ->removeButton('save')
            ->removeButton('delete');
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_model')->getId() > 0) {
            return Mage::helper('searchindex')->__("Search Results for '%s'", $this->escapeHtml(Mage::registry('current_model')->getQueryText()));
        } else {
            return Mage::helper('searchindex')->__('Search Results');
        }
    }
}
