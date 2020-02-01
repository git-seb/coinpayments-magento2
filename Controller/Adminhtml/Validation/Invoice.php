<?php

namespace Coinpayments\CoinPayments\Controller\Adminhtml\Validation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

/**
 * Class Invoice
 * @package Coinpayments\CoinPayments\Controller\Adminhtml\Validation
 */
class Invoice extends Validation
{

    public function execute()
    {
        $params = $this->getRequest()->getParams();

        $response = [];
        if (!empty($params['client_id']) && $this->helper->getConfig('validated') != $params['client_id']) {
            $clientId = $params['client_id'];
            $invoice = $this->invoiceModel->createSimple($clientId);
            if ($invoice) {
                $this->helper->setConfig('validated', $params['client_id']);
                $response = [
                    'success' => $invoice
                ];
            } else {
                $response = [
                    'success' => false,
                    'errorText' => sprintf('Failed to create validation invoice!'),
                ];
            }
        } elseif ($this->helper->getConfig('validated') == $params['client_id']) {
            $response = [
                'success' => $params['client_id']
            ];
        } else {
            $response = [
                'success' => false,
                'errorText' => sprintf('Enter Coinpayments.NET credentials!'),
            ];
        }


        $result = $this->jsonResultFactory->create();
        $result->setData($response);
        return $result;
    }
}