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



class Mirasvit_SearchIndex_Adminhtml_Searchindex_ReportController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        Mage::register(Mirasvit_SearchIndex_Block_Adminhtml_Report::SEARCHINDEX_REPORT, true);
        $this->loadLayout()
            ->_setActiveMenu('search')
            ->_addBreadcrumb(Mage::helper('searchindex')->__('Search Terms'), Mage::helper('searchindex')->__('Search Terms'));

        return $this;
    }

    public function indexAction()
    {
        $this->_title(Mage::helper('searchindex')->__('Search Terms'));
        $this->_initAction()
            ->_setActiveMenu('search');

        $this->renderLayout();
    }

    public function viewAction()
    {
        $model = $this->_getModel();

        if ($model->getId()) {
            $this->_title(Mage::helper('searchindex')->__('View search results for "%s"', $model->getData('query_text')));
            $this->_initAction()->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('searchindex')->__('The search query does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    protected function _getModel()
    {
        $model = Mage::getModel('catalogsearch/query');

        if ($id = $this->getRequest()->getParam('id')) {
            $model->load($id);
        }

        Mage::register('current_model', $model);

        return $model;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('search/searchindex_report');
    }

    public function exportCsvAction()
    {
        $fileName   = 'search_terms.csv';
        $content    = $this->getLayout()->createBlock('searchindex/adminhtml_report_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'search_terms.xml';
        $content    = $this->getLayout()->createBlock('searchindex/adminhtml_report_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
    }
}
