<?php

namespace Halweg\SzPaymentGateway\Gateways\Wechat;

use Halweg\SzPaymentGateway\Exceptions\InvalidArgumentException;

class ScanGateway extends Wechat
{
    protected function getTradeType()
    {
        return 'NATIVE';
    }
    
    public function pay(array $config_biz = [])
    {
        if (is_null($this->user_config->get('app_id'))) {
            throw new InvalidArgumentException('Missing Config -- [app_id]');
        }

        return $this->preOrder($config_biz)['code_url'];
    }
}
