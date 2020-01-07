PHP PaymentAsia Library

category Library
author Halweg halweg@126.com
copyright 2019-2020 Halweg - halweg.cn BLOG
license http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)

https://github.com/halweg/szjy-payment-gateway

PayGateWay
1、PaymentAsia
    (1)pay_method: 
        1.alipay
        2.wechat
        3.cup

How to use?
    1.if you want Alipay, u can:
        
        //$pay_config means the config of the gateway, like token,mid,sercet...
        $pay = new Pay($pay_config); 
       
        //$biz_config, like $order_id, $amout, $currency...
        $pay->driver('PaymentAsia')->gateway('AlipayHKWeb')->pay($biz_config);
        
    2.verify
         
         $pay = new Pay($pay_config);
         $si = $pay->driver('PaymentAsia')->gateway()->verify($data);    
         
    3.find
        $pay = new Pay($pay_config);
        $si = $pay->driver('PaymentAsia')->gateway('AlipayHKWeb')->find('2039303848XTEST[your unique id]');   