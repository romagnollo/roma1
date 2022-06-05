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
 * @package   mirasvit/extension_misspell
 * @version   1.2.11
 * @copyright Copyright (C) 2018 Mirasvit (https://mirasvit.com/)
 */



/**
 * @category Mirasvit
 */
class Mirasvit_Misspell_Block_Fallback extends Mirasvit_Misspell_Block_Abstract
{
    public function getOriginalQueryText()
    {
        return Mage::helper('misspell/query')->getFallbackPhase();
    }

    public function getQueryText()
    {
        return Mage::app()->getFrontController()->getRequest()->getParam('q');
    }
}
