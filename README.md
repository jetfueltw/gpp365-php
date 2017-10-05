## 介紹

gpp365 聚合支付 PHP 版本封裝。

## 安裝

使用 Composer 安裝。

```
composer require jetfueltw/gpp365-php
```

## 使用方法

### 掃碼支付下單

使用微信支付、QQ錢包、支付寶掃碼支付，下單後返回支付網址，請自行轉為 QR Code。

```
$merchantId = '123XXXXXXXX86'; // 商家號
$secretKey = 'XXXXXXXXXX'; // md5 密鑰
$tradeNo = '20170101235959XXX'; // 商家產生的唯一訂單號
$channel = Channel::WECHAT; // 第三方支付，支援微信支付、QQ錢包、支付寶
$amount = 100.00; // 下單支付金額
$clientIp = 'XXX.XXX.XXX.XXX'; // 消費者端 IP 位址
$notifyUrl = 'https://XXX.XXX.XXX'; // 交易完成後通知接口
```
```
$payment = new DigitalPayment($merchantId, $secretKey);
$result = $digitalPayment->order($tradeNo, $channel, $amount, $clientIp, $notifyUrl);
```
```
Result:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'merchant' => '123XXXXXXXX86' // 應用編號
        'tradeNo' => '20170101235959XXX' // 商家訂單號
        'ordernumber' => '123XXXXXXXXXXXXXX890' // 金流平台訂單號
        'payType' => '1' // 交易類型 1.WECHAT、2.ALIPAY、4.QQ
        'qrcode' => 'weixin://wxpay/bizpayurl?pr=XXXXXXX' // 二維碼網址
        'amount' => '100.00' // 交易金額
        'curType' => 'CNY' // 交易幣別
        'sign' => '946XXXXXXXXXXXXXXXXXXXXXXXXXX008' // 簽名
    ]
]
```

### 掃碼支付交易成功通知

消費者支付成功後，平台會發出 HTTP POST 請求到你下單時填的 $notifyUrl。

```
Post Data:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'merchant' => '123XXXXXXXX86' // 應用編號
        'tradeNo' => '20170101235959XXX' // 商家訂單號
        'ordernumber' => '123XXXXXXXXXXXXXX890' // 金流平台訂單號
        'payType' => '1' // 交易類型 1.WECHAT、2.ALIPAY、4.QQ
        'amount' => '100.00' // 交易金額
        'curType' => 'CNY' // 交易幣別
        'status' => '1' // 訂單狀態 0.處理中、1.交易成功、2.交易失敗
        'tradeTime' => '2017-08-01 09:00:00' // 交易時間
        'sign' => '946XXXXXXXXXXXXXXXXXXXXXXXXXX008' // 簽名
    ]
]
```

### 掃碼支付訂單查詢

使用商家訂單號查詢單筆訂單狀態。

```
$merchantId = '123XXXXXXXX86'; // 商家號
$secretKey = 'XXXXXXXXXX'; // md5 密鑰
$tradeNo = '20170101235959XXX'; // 商家產生的唯一訂單號
$channel = Channel::WECHAT; // 第三方支付，支援微信支付、QQ錢包、支付寶
```
```
$tradeQuery = new TradeQuery($merchantId, $secretKey);
$result = $tradeQuery->find($tradeNo, $channel);
```
```
Result:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'merchant' => '123XXXXXXXX86' // 應用編號
        'tradeNo' => '20170101235959XXX' // 交易編號
        'ordernumber' => '123XXXXXXXXXXXXXX890' // 金流平台訂單號
        'payType' => '1' // 交易類型 1.WECHAT、2.ALIPAY、4.QQ
        'amount' => '100.00' // 交易金額
        'curType' => 'CNY' // 交易幣別
        'status' => '1' // 訂單狀態 0.處理中、1.交易成功、2.交易失敗
        'tradeTime' => '2017-08-01 09:00:00' // 交易時間
        'sign' => '946XXXXXXXXXXXXXXXXXXXXXXXXXX008' // 簽名
    ]
]
```

### 網銀支付下單

使用網路銀行支付，下單後返回跳轉頁面，請 render 到客戶端。

```
$merchantId = '123XXXXXXXX86'; // 商家號
$secretKey = 'XXXXXXXXXX'; // md5 密鑰
$tradeNo = '20170101235959XXX'; // 商家產生的唯一訂單號
$bank = Bank::ABOC; // 銀行編號
$amount = 100.00; // 下單支付金額
$clientIp = 'XXX.XXX.XXX.XXX'; // 消費者端 IP 位址
$notifyUrl = 'https://XXX.XXX.XXX'; // 交易完成後通知接口
```
```
$payment = new BankPayment($merchantId, $secretKey);
$result = $digitalPayment->order($tradeNo, $bank, $amount, $clientIp, $notifyUrl);
```
```
Result:
跳轉用的 HTML，請 render 到客戶端
```

### 網銀支付交易成功通知

消費者支付成功後，平台會發出 HTTP POST 請求到你下單時填的 $notifyUrl。

```
Post Data:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'merchant' => '123XXXXXXXX86' // 應用編號
        'tradeNo' => '20170101235959XXX' // 商家訂單號
        'ordernumber' => '123XXXXXXXXXXXXXX890' // 金流平台訂單號
        'payType' => '3' // 交易類型，固定值 3
        'amount' => '100.00' // 交易金額
        'curType' => 'CNY' // 交易幣別
        'status' => '1' // 訂單狀態 0.處理中、1.交易成功、2.交易失敗
        'tradeTime' => '2017-08-01 09:00:00' // 交易時間
        'sign' => '946XXXXXXXXXXXXXXXXXXXXXXXXXX008' // 簽名
    ]
]
```

### 網銀支付訂單查詢

使用商家訂單號查詢單筆訂單狀態。

```
$merchantId = '123XXXXXXXX86'; // 商家號
$secretKey = 'XXXXXXXXXX'; // md5 密鑰
$tradeNo = '20170101235959XXX'; // 商家產生的唯一訂單號
```
```
$tradeQuery = new TradeQuery($merchantId, $secretKey);
$result = $tradeQuery->find($tradeNo);
```
```
Result:
[
    'code' => '0000'
    'message' => '操作成功'
    'data' => [
        'merchant' => '123XXXXXXXX86' // 應用編號
        'tradeNo' => '20170101235959XXX' // 交易編號
        'ordernumber' => '123XXXXXXXXXXXXXX890' // 金流平台訂單號
        'payType' => '3' // 交易類型，固定值 3
        'amount' => '100.00' // 交易金額
        'curType' => 'CNY' // 交易幣別
        'status' => '1' // 訂單狀態 0.處理中、1.交易成功、2.交易失敗
        'tradeTime' => '2017-08-01 09:00:00' // 交易時間
        'sign' => '946XXXXXXXXXXXXXXXXXXXXXXXXXX008' // 簽名
    ]
]
```
