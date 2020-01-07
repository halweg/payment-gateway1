<?php

namespace Halweg\SzPaymentGateway\Gateways\Wechat;

use Halweg\SzPaymentGateway\Exceptions\GatewayException;
use Halweg\SzPaymentGateway\Exceptions\InvalidArgumentException;

class RedpackGateway extends Wechat
{
    protected $gateway_transfer = 'mmpaymkttransfers/sendredpack';
    
    public function pay(array $config_biz = [])
    {
        if (is_null($this->user_config->get('app_id'))) {
            throw new InvalidArgumentException('Missing Config -- [app_id]');
        }
        unset($this->config['sign_type']);
        unset($this->config['trade_type']);
        unset($this->config['notify_url']);
        unset($this->config['app_id']);
        unset($this->config['appid']);

        $this->config = array_merge($this->config, $config_biz);

        $this->config['sign'] = $this->getSign($this->config);

        $data = $this->fromXml($this->post(
            $this->endpoint.$this->gateway_transfer,
            $this->toXml($this->config),
            [
                'cert'    => $this->user_config->get('cert_client', ''),
                'ssl_key' => $this->user_config->get('cert_key', ''),
            ]
        ));

        if (!isset($data['return_code']) || $data['return_code'] !== 'SUCCESS' || $data['result_code'] !== 'SUCCESS') {
            $error = 'getResult error:'.$data['return_msg'];
            $error .= isset($data['err_code_des']) ? ' - '.$data['err_code_des'] : '';
        }

        if (isset($error)) {
            throw new GatewayException(
                $error,
                20000,
                $data);
        }

        return $data;
    }
    
    protected function getTradeType()
    {
        return '';
    }
}
