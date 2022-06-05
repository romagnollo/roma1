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



class Mirasvit_SearchAutocomplete_Helper_Validator extends Mirasvit_MstCore_Helper_Validator_Abstract
{
    public function testTablesExists()
    {
        $result = self::SUCCESS;
        $title = 'Search Autocomplete: Conflicts with similar extensions';
        $description = array();

        $modules = array_keys((array) Mage::getConfig()->getNode('modules')->children());

        foreach ($modules as $module) {
            if (stripos($module, 'autocomplete') !== false && $module != 'Mirasvit_SearchAutocomplete') {
                $result = self::FAILED;
                $description[] = "Another Search Autocomplete extension '$module' installed, please remove it.";
            }

            if ($module == 'Smartwave_CatalogCategorySearch') {
                $result = self::FAILED;
                $description[] = "Another Search Autocomplete extension '$module' installed, please remove it.";
            }

            if ($module == 'Webdziner_Ajaxsearch') {
                $result = self::FAILED;
                $description[] = "Another Search Autocomplete extension '$module' installed, please remove it.";
            }
            if ($module == 'Mageplace_Hideprice') {
                if (!in_array('searchautocomplete/result',Mage::helper('hideprice')->getProcessingBlocks())){
                    $title = 'Search Autocomplete: Conflict with Hideprice';
                    $result = self::FAILED;
                    $description[] = "Please add 'searchautocomplete/result;' to Hide price block types in Configuration -> Hide Price -> block detection section.";
                }
            }
        }

        return array($result, $title, $description);
    }

    public function testMirasvitMstCoreCrc()
    {
        $modules = array('SearchAutocomplete');

        return Mage::helper('mstcore/validator_crc')->testMirasvitCrc($modules);
    }
}
