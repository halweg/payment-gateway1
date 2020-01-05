<?php
namespace Halweg\SzPaymentGateway\Gateways\PaymentAsia;

use App\Services\Pay\Contracts\GatewayInterface;

use App\Services\Pay\Exceptions\GatewayException;
use App\Services\Pay\Exceptions\InvalidArgumentException;

use App\Services\Pay\Support\Config;
use App\Services\Pay\Traits\HasHttpRequest;

abstract class PaymentAsia implements GatewayInterface
{
    use HasHttpRequest;
    
    protected $gateway = 'https://payment.pa-sys.com/app/page/';
    
    protected $query_gateway = 'https://gateway.pa-sys.com/';
    
    protected $config;
    
    protected $user_config;
    
    public function __construct(array $config)
    {
        $this->user_config = new Config($config);
        
        if (is_null($this->user_config->get('merchant_token'))) {
            throw new InvalidArgumentException('Missing Config -- [Merchant Token]');
        }
    
        if (is_null($this->user_config->get('return_url'))) {
            throw new InvalidArgumentException('Missing Config -- [Return Url]');
        }
        
        $this->gateway = $this->gateway.$this->user_config->get('merchant_token');
        $this->query_gateway = $this->query_gateway .$this->user_config->get('merchant_token')."/payment/query";
        
        $this->config = [
            'return_url'  => $this->user_config->get('return_url', ''),
            'network'     => $this->getNetWork(),
        ];
    }
    
    public function pay(array $config_biz)
    {
        $this->config = array_merge($config_biz, $this->config);
    
        $this->config['sign'] = $this->getSign($this->config);
        
    }
    
    //退款接口
    public function refund($config_biz, $refund_amount = null)
    {
        //paymentAsia 未提供
    }
    
    //关闭订单接口
    public function close($config_biz)
    {
       //paymentAsia 未提供
    }
    
    //订单查询接口
    public function find($out_trade_no = '')
    {
        $fields = array(
            'merchant_reference' => $out_trade_no,
        );
        $fields['sign'] = $this->getSign($fields);
       
        $data = json_decode($this->post($this->query_gateway, $fields), true);
        return $data;
    }
    
    public function verify($data, $sign = null, $sync = false)
    {
        $secret = $this->user_config->get('secret');
        $sign = $data['sign'];
        unset($data['sign']);
        ksort($data);
        return $sign == hash('SHA512', http_build_query($data) . $secret);
    }
    
    abstract protected function getNetWork();
    
    protected function buildPayHtml()
    {
        $sHtml = "<form id='payment-asia' name='payment-asia' action='".$this->gateway."' method='POST'>";
        foreach ($this->config as $key => $val) {
            $sHtml .= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        $sHtml .= "<input type='submit' value='ok' style='display:none;'></form>";
        $sHtml .= "<script>document.forms['payment-asia'].submit();</script>";
        
        return $sHtml;
    }
   
    protected function getSign($fields)
    {
        if (is_null($this->user_config->get('secret'))) {
            throw new InvalidArgumentException('Missing Config -- [secret]');
        }
       
        ksort($fields);
        
        return hash('SHA512', http_build_query($fields) . $this->user_config->get('secret'));
    }
    
}
