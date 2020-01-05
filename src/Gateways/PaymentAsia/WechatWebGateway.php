<?php
namespace Halweg\SzPaymentGateway\Gateways\PaymentAsia;

class WechatWebGateway extends PaymentAsia
{
    protected function getNetWork()
    {
        return 'Wechat';
    }
    
    public function pay(array $config_biz = [])
    {
        parent::pay($config_biz);
        
        return $this->buildPayHtml();
    }
}