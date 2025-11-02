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
ResponseEnforcer::success($msg = '操作成功', $data = null, $code = null, $is_encrypt = true);

//error
ResponseEnforcer::error($msg = '操作失败', $data = null, $code = null, $is_encrypt = false);

//abort
ResponseEnforcer::abort($msg = '服务器内部错误', $code = null);

//paginate
ResponseEnforcer::paginate( $data = [], $totalCount = 0, $msg = '加载完成', $code = null, $is_encrypt = true);

//result
ResponseEnforcer::result($result, $is_encrypt = true);

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
