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



class Mirasvit_SearchAutocomplete_Block_Layout extends Mage_Core_Block_Template
{
    /**
     * Current theme CSS file base path 
     */
    protected $_css;

    /**
     * Current theme template file base path 
     */
    protected $_template;
    
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->defineTheme();
    }

    /**
     * {@inheritdoc}
     * Set css and template file for search box.
     */
    protected function defineTheme()
    {
        $theme = Mage::getSingleton('searchautocomplete/config')->getTheme();
        switch ($theme) {
            case 'default':
                $this->_css = Mirasvit_SearchAutocomplete_Model_Config::CSS_DEFAULT;
                $this->_template = Mirasvit_SearchAutocomplete_Model_Config::TPL_DEFAULT;
                break;
            case 'rwd':
                $this->_css = Mirasvit_SearchAutocomplete_Model_Config::CSS_RWD;
                $this->_template = Mirasvit_SearchAutocomplete_Model_Config::TPL_DEFAULT;
                break;
            default:
                $this->_css = Mirasvit_SearchAutocomplete_Model_Config::CSS_AMAZON;
                $this->_template = Mirasvit_SearchAutocomplete_Model_Config::TPL_AMAZON;
                break;
        }
        $fileBasePath = Mage::getBaseDir('skin').'/frontend/base/default/'.
            Mirasvit_SearchAutocomplete_Model_Config::CSS_CUSTOM; 
        if (file_exists($fileBasePath)) {
            $this->_css = Mirasvit_SearchAutocomplete_Model_Config::CSS_CUSTOM;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addSearchAutocomplete()
    {
        $this->addSearchCss();
        $this->addForm();

        return $this;
    }

    /**
     * {@inheritdoc}
     * Add css file to layout.
     */
    public function addSearchCss()
    {
        $head = $this->getLayout()->getBlock('head');
        if ($head instanceof Mage_Core_Block_Template) {
            if (Mage::getStoreConfig('dev/css/merge_css_files', Mage::app()->getStore()->getId()) &&
                strpos($this->_css, 'custom.css')) {
                $themeCss = Mage::getSingleton('searchautocomplete/config')->getTheme().'.css';
                $head->addCss(str_replace('custom.css', $themeCss, $this->_css));
            }
            $head->addCss($this->_css);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     * Add searchautocomplete block to layout.
     */
    public function addForm()
    {
        $searchBlock = $this->getLayout()->getBlock('top.search');
        if (!$searchBlock instanceof Mage_Core_Block_Template) {
            $searchParentBlock = $this->getLayout()->getBlock('header');
        } else {
            $searchParentBlock = $searchBlock->getParentBlock();
            if (is_object($searchParentBlock)) {
                $searchParentBlock->unsetChild($searchBlock->getBlockAlias());    
            } else {
                $searchParentBlock = false;
            }
        }

        $searchBlock = $this->getLayout()->createBlock('searchautocomplete/form')
            ->setTemplate($this->_template)
            ->setNameInLayout('top.search')
            ->setAlias('top.search');

        if ($searchParentBlock) {
            $searchParentBlock->setChild('topSearch', $searchBlock);
        }

        // assign for ultimo theme
        $this->getLayout()->setBlock('top.search', $searchBlock);
        $this->getLayout()->getBlock('top.search')->setNameInLayout('top.search');
    }
}
