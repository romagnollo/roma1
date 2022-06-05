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



/**
 * @category Mirasvit
 */
class Mirasvit_SearchSphinx_Model_System_Config_Source_SearchEngine
{
    public function toOptionArray()
    {
        $options = array(
            array(
                'value' => Mirasvit_SearchSphinx_Model_Config::ENGINE_FULLTEXT,
                'label' => Mage::helper('searchsphinx')->__('Built-in Sphinx Search Engine'),
            ),
        );

        $options[] = array(
            'value' => Mirasvit_SearchSphinx_Model_Config::ENGINE_SPHINX,
            'label' => Mage::helper('searchsphinx')->__('External Sphinx Search Engine'),
        );

        $options[] = array(
            'value' => Mirasvit_SearchSphinx_Model_Config::ENGINE_SPHINX_EXTERNAL,
            'label' => Mage::helper('searchsphinx')->__('External Sphinx Search Engine (another server)'),
        );

        return $options;
    }
}
