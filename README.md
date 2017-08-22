## Introduction

gpp365 payment gateway php package.

## Installation

Add jetfueltw/gpp365-php as a require dependency in your composer.json file:

```
composer require jetfueltw/gpp365-php
```

## Usage

```
$appId = '1234567890123'; // 應用編號，或稱 merchant
$appSecret = 'XXXXXXXXXX'; // 應用密鑰，或稱 md5Key

$digitalPayment = new DigitalPayment($appId, $appSecret[, $baseApiUrl]); // 第三方支付
$bankPayment = new BankPayment($appId, $appSecret[, $baseApiUrl]); // 網銀支付
```

### 第三方支付申請交易

```
$tradeNo = '1234567890'; // 交易編號
$channel = Channel::WECHAT; // 支援 WECHAT、ALIPAY、QQ
$amount = 100; // 交易金額
$userId = 1; // 玩家編號
$device = Device::WEB; // 客戶端裝置 WEB、MOBILE
$ip = '140.112.15.64'; // 客戶端 IP 位置
$notifyUrl = 'https://example.app/webhook'; // 當交易成功時，他會打請求到這個位置，請看第三方支付交易成功通知

$result = $digitalPayment->order($tradeNo, $channel, $amount, $userId, $device, $ip, $notifyUrl);
$result:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'merchant' => '1234567890123' // 應用編號
        'tradeNo' => '1234567890' // 交易編號
        'ordernumber' => '12345678901234567890' // 金流平台訂單號
        'payType' => '1' // 交易類型 1.WECHAT、2.ALIPAY、4.QQ
        'qrcode' => 'weixin://wxpay/bizpayurl?pr=XXXXXXX' // 二維碼網址
        'amount' => '100.00' // 交易金額
        'curType' => 'CNY' // 交易幣別
        'sign' => '946d59ef1f4e0ecbe22f36a630fb9008' // 簽名
    ]
]
```

### 第三方支付交易成功通知

```
$postData:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'merchant' => '1234567890123' // 應用編號
        'tradeNo' => '1234567890' // 交易編號
        'ordernumber' => '12345678901234567890' // 金流平台訂單號
        'payType' => '1' // 交易類型 1.WECHAT、2.ALIPAY、4.QQ
        'amount' => '100.00' // 交易金額
        'curType' => 'CNY' // 交易幣別
        'status' => '1' // 訂單狀態 0.處理中、1.交易成功、2.交易失敗
        'tradeTime' => '2017-08-01 09:00:00' // 交易時間
        'sign' => '946d59ef1f4e0ecbe22f36a630fb9008' // 簽名
    ]
]
```

### 第三方支付確認訂單

```
$tradeNo = '1234567890'; // 交易編號
$channel = Channel::WECHAT; // 支援 WECHAT、ALIPAY、QQ

$result = $digitalPayment->check($tradeNo, $channel);
$result:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'merchant' => '1234567890123' // 應用編號
        'tradeNo' => '1234567890' // 交易編號
        'ordernumber' => '12345678901234567890' // 金流平台訂單號
        'payType' => '1' // 交易類型 1.WECHAT、2.ALIPAY、4.QQ
        'amount' => '100.00' // 交易金額
        'curType' => 'CNY' // 交易幣別
        'status' => '1' // 訂單狀態 0.處理中、1.交易成功、2.交易失敗
        'tradeTime' => '2017-08-01 09:00:00' // 交易時間
        'sign' => '946d59ef1f4e0ecbe22f36a630fb9008' // 簽名
    ]
]
```

### 網銀支付申請交易

```
$tradeNo = '1234567890'; // 交易編號
$bank = Bank::ABOC; // 支援銀行請看網銀支付支援銀行
$amount = 100; // 交易金額
$userId = 1; // 玩家編號
$device = Device::WEB; // 客戶端裝置 WEB、MOBILE
$ip = '140.112.15.64'; // 客戶端 IP 位置
$notifyUrl = 'https://example.app/webhook'; // 當交易成功時，他會打請求到這個位置，請看網銀支付交易成功通知

$result = $bankPayment->order($tradeNo, $bank, $amount, $userId, $device, $ip, $notifyUrl);
$result:
跳轉用的 HTML，請 render 到客戶端
```

### 網銀支付交易成功通知

```
$postData:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'merchant' => '1234567890123' // 應用編號
        'tradeNo' => '1234567890' // 交易編號
        'ordernumber' => '12345678901234567890' // 金流平台訂單號
        'payType' => '3' // 交易類型，固定值 3
        'amount' => '100.00' // 交易金額
        'curType' => 'CNY' // 交易幣別
        'status' => '1' // 訂單狀態 0.處理中、1.交易成功、2.交易失敗
        'tradeTime' => '2017-08-01 09:00:00' // 交易時間
        'sign' => '946d59ef1f4e0ecbe22f36a630fb9008' // 簽名
    ]
]
```

### 網銀支付確認訂單

```
$tradeNo = '1234567890'; // 交易編號

$result = $bankPayment->check($tradeNo);
$result:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'merchant' => '1234567890123' // 應用編號
        'tradeNo' => '1234567890' // 交易編號
        'ordernumber' => '12345678901234567890' // 金流平台訂單號
        'payType' => '3' // 交易類型，固定值 3
        'amount' => '100.00' // 交易金額
        'curType' => 'CNY' // 交易幣別
        'status' => '1' // 訂單狀態 0.處理中、1.交易成功、2.交易失敗
        'tradeTime' => '2017-08-01 09:00:00' // 交易時間
        'sign' => '946d59ef1f4e0ecbe22f36a630fb9008' // 簽名
    ]
]
```

### 網銀支付支援銀行

| 銀行代碼 | 銀行名稱         |
|----------|------------------|
| PCBC     | 中國建設銀行     |
| ABOC     | 中國農業銀行     |
| ICBK     | 中國工商銀行     |
| CMBC     | 招商銀行         |
| SPDB     | 上海浦東發展銀行 |
| EVER     | 光大銀行         |
| GDBK     | 廣東發展銀行     |
| HXBK     | 華夏銀行         |
| PSBC     | 中國郵政儲蓄銀行 |
