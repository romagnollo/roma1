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
 * @category Mirasvit
 */
class Mirasvit_SearchAutocomplete_Model_System_Config_Source_Index
{
    public function toOptionArray()
    {
        $indexes = Mage::helper('searchautocomplete')->getIndexes();

        $values = array();
        $values['-'] = array(
            'value' => '',
            'label' => '',
        );
        foreach ($indexes as $code => $label) {
            if ($code == 'catalog') {
                continue;
            }
            $values[$code] = array(
                'value' => $code,
                'label' => $label,
            );
        }

        return $values;
    }
}
