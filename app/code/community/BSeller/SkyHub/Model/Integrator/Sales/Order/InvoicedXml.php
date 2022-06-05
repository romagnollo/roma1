<?php
/**
 * BSeller Platform | B2W - Companhia Digital
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  BSeller
 * @package   BSeller_SkyHub
 *
 * @copyright Copyright (c) 2018 B2W Digital - BSeller Platform. (http://www.bseller.com.br)
 *
 * Access https://ajuda.skyhub.com.br/hc/pt-br/requests/new for questions and other requests.
 */

class BSeller_SkyHub_Model_Integrator_Sales_Order_InvoicedXml extends BSeller_SkyHub_Model_Integrator_Abstract
{
    /**
     * @return \SkyHub\Api\EntityInterface\Sales\Order
     */
    protected function getEntityInterface()
    {
        return $this->api()->order()->entityInterface();
    }

    /**
     * Send xml to skyhub
     *
     * @param string $orderId
     * @param string $volumeQty
     * @param string $pathFile
     * @param string $fileName
     * @return bool
     */
    public function sendInvoiceXml($orderId, $volumeQty, $pathFile, $fileName)
    {
        Mage::register(
            'bseller_skyhub_response_format',
            BSeller_SkyHub_Model_Service::RESPONSE_MULTPART,
            true
        );
        $datetime = new DateTime();
        $issueDate = $datetime->format('Y-m-d\TH:i:sP');
        $result = $this->getEntityInterface()->invoiceB2wDirect(
            $orderId,
            $volumeQty,
            $issueDate,
            $pathFile,
            $fileName
        );

        if ($result->exception() || $result->invalid()) {
            throw new \Exception($result->message());
        }

        return true;
    }
}
