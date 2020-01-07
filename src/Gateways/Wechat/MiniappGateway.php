<?php

namespace Halweg\SzPaymentGateway\Gateways\Wechat;

use Halweg\SzPaymentGateway\Exceptions\InvalidArgumentException;

class MiniappGateway extends Wechat
{
    protected function getTradeType()
    {
        return 'JSAPI';
    }
    
    public function pay(array $config_biz = [])
    {
        if (is_null($this->user_config->get('miniapp_id'))) {
            throw new InvalidArgumentException('Missing Config -- [miniapp_id]');
        }

        $this->config['appid'] = $this->user_config->get('miniapp_id');

        $payRequest = [
            'appId'     => $this->user_config->get('miniapp_id'),
            'timeStamp' => strval(time()),
            'nonceStr'  => $this->createNonceStr(),
            'package'   => 'prepay_id='.$this->preOrder($config_biz)['prepay_id'],
            'signType'  => 'MD5',
        ];
        $payRequest['paySign'] = $this->getSign($payRequest);

        return $payRequest;
    }
}
