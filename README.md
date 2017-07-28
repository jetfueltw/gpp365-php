## Introduction

gpp365 payment gateway php package.

## Installation

Add jetfueltw/gpp365-php as a require dependency in your composer.json file:

```
composer require jetfueltw/gpp365-php
```

## Usage

### Digital Payment

#### Order

```
$payment = new DigitalPayment($merchant, $md5Key[, $apiBaseUrl]);
$result = $payment->order('1234567890', Provider::WECHAT, 100, $userId, Device::WEB, $ip, $notifyUrl);
$result:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'amount' => '100.00'                                
        'curType' => 'CNY'                                   
        'merchant' => '1234567890123'                        
        'ordernumber' => '12345678901234567890'                 
        'payType' => '1'                                     
        'qrcode' => 'weixin://wxpay/bizpayurl?pr=XXXXXXX'  
        'sign' => '946d59ef1f4e0ecbe22f36a630fb9008'     
        'tradeNo' => '1234567890'
    ]
]
```