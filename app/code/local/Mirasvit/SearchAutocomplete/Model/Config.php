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



class Mirasvit_SearchAutocomplete_Model_Config
{
    const XML_PATH_THEME = 'searchautocomplete/styles/theme';

    const CSS_AMAZON = 'css/mirasvit/searchautocomplete/amazon.css';
    const CSS_DEFAULT = 'css/mirasvit/searchautocomplete/default.css';
    const CSS_RWD = 'css/mirasvit/searchautocomplete/rwd.css';
    const CSS_CUSTOM = 'css/mirasvit/searchautocomplete/custom.css';

    const TPL_AMAZON = 'mst_searchautocomplete/amazon.phtml';
    const TPL_DEFAULT = 'mst_searchautocomplete/default.phtml';

    public function getTheme()
    {
        return Mage::getStoreConfig(self::XML_PATH_THEME, Mage::app()->getStore()->getId());
    }

    public function isProfilerActive()
    {
        return Mage::getStoreConfig('searchautocomplete/general/profiling');
    }
}
