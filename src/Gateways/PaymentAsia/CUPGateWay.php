<?php
namespace Halweg\SzPaymentGateway\Gateways\PaymentAsia;

class CUPGateWay extends PaymentAsia
{
    protected function getNetWork()
    {
        return 'CUP';
    }
    
    public function pay(array $config_biz = [])
    {
        parent::pay($config_biz);
        
        return $this->buildPayHtml();
    }
    
}