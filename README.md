Thinkphp Response插件
=====

### 安装

```
composer require cdyun/thinkphp-response
```

### 例子

响应：

```PHP
use Cdyun\ThinkphpResponse\ResponseEnforcer;

//获取配置
ResponseEnforcer::getConfig($name = null, $default = null);

//success
ResponseEnforcer::success($msg = '操作成功', $data = null, $header = []);

//error - 错误响应始终不会加密
ResponseEnforcer::error($msg = '操作失败', $data = null,  $header = []);

//abort
ResponseEnforcer::abort($msg = '服务器内部错误', $code = 500);

//paginate
ResponseEnforcer::paginate( $data = [], $totalCount = 0, $msg = '加载完成', $header = []);

//result
ResponseEnforcer::result($result, array $header = [], bool $isEncrypt = false);

```
加密/解密：
```PHP
use Cdyun\ThinkphpResponse\EncryptorEnforcer;

//获取配置
ResponseEnforcer::getConfig($name = null, $default = null);

//RSA解密
EncryptorEnforcer::rsaDecrypt($data);

//AES解密
EncryptorEnforcer::aesDecrypt($data, $key, $iv);

//AES加密
EncryptorEnforcer::aesEncrypt($data, $key, $iv);

```

配置文件config/cdyun.php
```PHP
<?php

return [
    //  响应信息
    'response' => [
        //  返回码
        'code' => [
            //  成功返回码
            'success' => (int)env('code.success_code', 0),
            //  失败返回码
            'error' => (int)env('code.error_code', -1),
        ],
        //  是否开启加密
        'enable' => false,
        //  不需要加密的url，上传URL不需要加密
        'url' => ['/web_api/core/ocr/scan', '/web_api/core/upload/direct'],
        //  RSA私钥
        'rsa_private' => '',
    ],
];
```