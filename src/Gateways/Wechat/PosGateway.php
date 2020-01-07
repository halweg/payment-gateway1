<?php

namespace Halweg\SzPaymentGateway\Gateways\Wechat;

use Halweg\SzPaymentGateway\Exceptions\InvalidArgumentException;

class PosGateway extends Wechat
{
    protected $gateway_order = 'pay/micropay';
    
    protected function getTradeType()
    {
        return 'MICROPAY';
    }
    
    public function pay(array $config_biz = [])
    {
        if (is_null($this->user_config->get('app_id'))) {
            throw new InvalidArgumentException('Missing Config -- [app_id]');
        }

        unset($this->config['trade_type']);
        unset($this->config['notify_url']);

        return $this->preOrder($config_biz);
    }
}
