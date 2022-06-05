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
class Mirasvit_SearchAutocomplete_Helper_Data extends Mage_Core_Helper_Data
{
    protected $_indexes = null;

    public function toSingleRegister($base, $needle)
    {
        for ($i = 0; $i < strlen($base); $i++) {
            if (ctype_lower($base[$i])) {
                $needle{$i}
                = strtolower($needle{$i});
            } else {
                $needle{$i}
                = strtoupper($needle{$i});
            }
        }

        return $needle;
    }

    public function higlight($text, $query)
    {
        $result = $text;

        $query = preg_split("/[,\. ]/", $query);

        foreach ($query as $word) {
            $result = preg_replace("|($word)|Ui", '<strong>$1</strong>', $result);
        }

        return $result;
    }

    public function getIndexes($all = true)
    {
        if ($this->_indexes == null) {
            $this->_indexes = array();

            if (Mage::helper('core')->isModuleEnabled('Mirasvit_SearchIndex')) {
                $indexes = Mage::helper('searchindex/index')->getIndexes();
                uasort($indexes, array($this, 'sortIndexes'));
                foreach ($indexes as $index) {
                    // if multistore mode enabled, we select index only for current store
                    if (!$index->getStoreId() || $index->getStoreId() == Mage::app()->getStore()->getId()) {
                        $this->_indexes[$index->getCode()] = $index->getTitle();
                    }
                }
            } else {
                $this->_indexes['mage_catalog_product'] = '';
            }
        }

        if ($all == false) {
            $displayedIndexes = $this->_indexes;
            $forDisplay = explode(',', Mage::getStoreConfig('searchautocomplete/general/indexes'));
            foreach ($displayedIndexes as $code => $label) {
                if (!in_array($code, $forDisplay)) {
                    unset($displayedIndexes[$code]);
                }
            }

            return $displayedIndexes;
        }

        return $this->_indexes;
    }

    /**
     * Sort indexes by position, in ascending order.
     */
    private function sortIndexes($a, $b)
    {
        if ($a->getData('position') == $b->getData('position')) {
            return 0;
        }

        return ($a->getData('position') > $b->getData('position')) ? 1 : -1;
    }

    public function profile()
    {
        $profile = array();
        foreach (Varien_Profiler::getTimers() as $key => $timer) {
            if ($timer['count'] > 0) {
                $profile[$key] = array(
                    'duration(s)' => round($timer['sum'] / $timer['count'], 4),
                    'duration sum' => round($timer['sum'], 4),
                    'emalloc(bytes)' => number_format(round($timer['emalloc'] / $timer['count'], 4)),
                    'count' => $timer['count'],
                );
            }
        }
        
        return array_filter($profile);
    }
}
