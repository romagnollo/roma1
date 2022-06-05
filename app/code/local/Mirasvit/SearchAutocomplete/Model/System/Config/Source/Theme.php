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



class Mirasvit_SearchAutocomplete_Model_System_Config_Source_Theme
{
    public function toOptionArray()
    {
        $options = array(
            'amazon' => array(
                'value' => 'amazon',
                'label' => Mage::helper('searchautocomplete')->__('Amazon'),
            ),
            'default' => array(
                'value' => 'default',
                'label' => Mage::helper('searchautocomplete')->__('Default'),
            ),
            'rwd' => array(
                'value' => 'rwd',
                'label' => Mage::helper('searchautocomplete')->__('Rwd'),
            ),
        );

        return $options;
    }
}
