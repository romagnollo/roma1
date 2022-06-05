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
class Mirasvit_Misspell_Model_Observer
{
    public function onPostdispatchCatalogsearchResultIndex($observer)
    {
        $queryHelper = Mage::helper('misspell/query');
        $query = Mage::helper('catalogsearch')->getQuery();

        $messageRoute404 = Mage::getSingleton('core/session')->getData('route404');

        if (!empty($messageRoute404)) {
            Mage::getSingleton('core/session')->unsetData('route404');
            Mage::getSingleton('core/session')->addNotice($messageRoute404);
        }

        // Do not perform spell correction if search term has internal (Magento) synonym
        if ($queryHelper->getCountResult($queryHelper->getCurrentPhase()) == 0 && !$query->getSynonymFor()) {
            $result = $this->doSpellCorrection();

            // if spell correction return false
            if (!$result) {
                $result = $this->doFallbackCorrection();
            }
        }
    }

    public function doSpellCorrection()
    {
        if (!Mage::getStoreConfig('misspell/general/misspell')) {
            return false;
        }
        $queryHelper = Mage::helper('misspell/query');
        $currentPhase = mb_strtolower($queryHelper->getCurrentPhase());

        $suggestedPhase = $queryHelper->suggestMisspellPhase($currentPhase);

        if ($suggestedPhase
            && $suggestedPhase != $queryHelper->getCurrentPhase()
            && $suggestedPhase != $queryHelper->getMisspellPhase()) {

            //do redirect
            if ($queryHelper->getCountResult($suggestedPhase)) {
                $url = $queryHelper->getMisspellUrl($queryHelper->getCurrentPhase(), $suggestedPhase);

                Mage::app()->getFrontController()->getResponse()->setRedirect($url);

                return true;
            }
        }

        return false;
    }

    public function doFallbackCorrection()
    {
        if (!Mage::getStoreConfig('misspell/general/fallback')) {
            return false;
        }

        $queryHelper = Mage::helper('misspell/query');
        $currentPhase = $queryHelper->getCurrentPhase();

        $suggestedPhase = $queryHelper->suggestFallbackPhase($currentPhase);

        if ($suggestedPhase
            && $suggestedPhase != $queryHelper->getCurrentPhase()
            && $suggestedPhase != $queryHelper->getFallbackPhase()
            ) {
            $url = $queryHelper->getFallbackUrl($currentPhase, $suggestedPhase);

            Mage::app()->getFrontController()->getResponse()->setRedirect($url);

            return true;
        }

        return false;
    }

    public function onPrepareCollection()
    {
        // Mage::helper('catalogsearch')->setSuggestQuery();
    }
}
