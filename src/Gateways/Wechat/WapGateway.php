<?php

namespace Halweg\SzPaymentGateway\Gateways\Wechat;

use Halweg\SzPaymentGateway\Exceptions\InvalidArgumentException;

class WapGateway extends Wechat
{
    
    protected function getTradeType()
    {
        return 'MWEB';
    }

    
    public function pay(array $config_biz = [])
    {
        if (is_null($this->user_config->get('app_id'))) {
            throw new InvalidArgumentException('Missing Config -- [app_id]');
        }

        $data = $this->preOrder($config_biz);

        return is_null($this->user_config->get('return_url')) ? $data['mweb_url'] : $data['mweb_url'].
                        '&redirect_url='.urlencode($this->user_config->get('return_url'));
    }
}
