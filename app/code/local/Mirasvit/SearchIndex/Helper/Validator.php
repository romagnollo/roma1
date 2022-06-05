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


class Mirasvit_SearchIndex_Helper_Validator extends Mirasvit_MstCore_Helper_Validator_Abstract
{
    public function testConflictExtensions()
    {
        $result = self::SUCCESS;
        $title = 'Search Index: Conflicts with another extensions';
        $description = array();
        $troubleshootLink = "https://docs.mirasvit.com/doc/extension_searchsphinx/current/troubleshooting";

        $modules = (array) Mage::getConfig()->getNode('modules')->children();

        $searchMatches = preg_grep('/(Search|search|autocomplete|autosuggest)/', array_keys($modules));
        $searchModules = array_intersect_key($modules, array_flip($searchMatches));

        $thirdPartyMatches = preg_grep('/^((?!Mirasvit|Mage|research|Research).)*$/', array_keys($searchModules));

        $thirdPartyModules = array_intersect_key($searchModules, array_flip($thirdPartyMatches));

        $nonSearchModules = array('MageWorx_CustomOptions','Netzarbeiter_GroupsCatalog2','Mana_Filters');

        foreach ($nonSearchModules as $moduleName) {
            if (Mage::helper('mstcore')->isModuleInstalled($moduleName)) {
                if ($this->validateRewrite('catalogsearch_resource/fulltext_collection',
                    'Mirasvit_SearchIndex_Model_Catalogsearch_Resource_Fulltext_Collection') !== true
                ) {
                    $result = self::FAILED;
                    $description[] = $moduleName ." is installed. Please disable this extension or solve conflict between collection models as described"
                        ." in our <a href='$troubleshootLink'>manual</a> (preferred).";
                }
            }
        }

        if (!empty($thirdPartyModules)) {
            foreach ($thirdPartyModules as $moduleName => $values) {
                $result = self::FAILED;
                $description[] = $moduleName." is installed. If you have problems with your search please disable this extension. "
                    . "Also you can check the conflict solution in our <a href='$troubleshootLink'>manual</a>.";
            }
        }

        return array($result, $title, $description);
    }

    public function testFulltextCollectionRewrite()
    {
        $result = self::SUCCESS;
        $title = 'Search Index: Check Rewrites';
        $description = array();

        $validateRewrite = $this->validateRewrite(
            'catalogsearch_resource/fulltext_collection',
            'Mirasvit_SearchIndex_Model_Catalogsearch_Resource_Fulltext_Collection'
        );

        if ($validateRewrite !== true) {
            $result = self::FAILED;
            $description = $validateRewrite;
        }

        return array($result, $title, $description);
    }

    public function validateRewrite($class, $classNameB)
    {
        $object = Mage::getModel($class);
        if ($object instanceof $classNameB) {
            return true;
        } else {
            return "$class must be $classNameB, current rewrite is " . get_class($object);
        }
    }
}
