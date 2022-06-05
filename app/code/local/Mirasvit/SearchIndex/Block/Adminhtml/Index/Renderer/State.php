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



class Mirasvit_SearchIndex_Block_Adminhtml_Index_Renderer_State extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function render(Varien_Object $row)
    {
        $status = $row->getStatus();
        $isActive = $row->getIsActive();

        $label = Mage::helper('searchindex')->__('Disabled');
        $class = 'grid-severity-major';

        if ($isActive) {
            if ($status == 1) {
                $class = 'grid-severity-notice';
                $label = Mage::helper('searchindex')->__('Ready');
            } else {
                $class = 'grid-severity-critical';
                $label = Mage::helper('searchindex')->__('Reindex Required');
            }
        }

        return $formatString = "<span class='$class'><span>$label</span></span>";
    }
}
