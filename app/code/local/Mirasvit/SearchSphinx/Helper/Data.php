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
class Mirasvit_SearchSphinx_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Return Search engine object (Sphinx (External), Sphinx Native or Fulltext).
     *
     * @return object
     */
    public function getEngine()
    {
        $uid = Mage::helper('mstcore/debug')->start();

        switch (Mage::getSingleton('searchsphinx/config')->getSearchEngine()) {
            case Mirasvit_SearchSphinx_Model_Config::ENGINE_SPHINX:
                $engine = Mage::getSingleton('searchsphinx/engine_sphinx_native');
            break;

            case Mirasvit_SearchSphinx_Model_Config::ENGINE_SPHINX_EXTERNAL:
                $engine = Mage::getSingleton('searchsphinx/engine_sphinx');
            break;

            default:
                $engine = Mage::getSingleton('searchsphinx/engine_fulltext');
            break;
        }

        Mage::helper('mstcore/debug')->end($uid, $engine);

        return $engine;
    }

    /**
     * Check if word is wildcard exception.
     *
     * @param string $word
     *
     * @return bool
     */
    public function isWildcardException($word)
    {
        $uid = Mage::helper('mstcore/debug')->start();

        $exceptions = Mage::getSingleton('searchsphinx/config')->getWildCardExceptions();

        $result = in_array($word, $exceptions);

        Mage::helper('mstcore/debug')->end($uid, $result);

        return $result;
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
