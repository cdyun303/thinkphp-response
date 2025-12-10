<?php
/**
 * ResponseEnforcer.php
 * @author cdyun(121625706@qq.com)
 * @date 2025/11/2 15:08
 */

declare(strict_types=1);

namespace Cdyun\ThinkphpResponse;

use think\exception\HttpResponseException;
use think\Response;

class ResponseEnforcer
{
    /**
     * 构造成功响应数据并返回
     * @param array|string $msg - 提示信息，若为数组则视为 data 数据
     * @param mixed|null $data - 需要返回的数据
     * @param array $header - 发送的Header信息
     * @return void
     * @author cdyun(121625706@qq.com)
     */
    public static function success(array|string $msg = '操作成功', mixed $data = null, array $header = []): void
    {
        $result = [];
        $result['code'] = self::getCode('success');
        $result['time'] = time();

        if (is_array($msg)) {
            $result['message'] = '操作成功';
            $result['data'] = $msg;
        } else {
            $result['message'] = $msg;
            if ($data !== null) {
                $result['data'] = $data;
            }
        }

        $isEncrypt = self::getConfig('enable');
        self::result($result, $header, $isEncrypt);
    }

    /**
     * 获取状态码
     * @param string $type
     * @return int
     * @author cdyun(121625706@qq.com)
     */
    public static function getCode(string $type = 'success'): int
    {
        return $type == 'success' ? self::getConfig('code.success', 0) : self::getConfig('code.error', -1);
    }

    /**
     * 获取配置config
     * @param string|null $name - 名称
     * @param $default - 默认值
     * @return mixed
     * @author cdyun(121625706@qq.com)
     */
    public static function getConfig(?string $name = null, $default = null): mixed
    {
        if (!is_null($name)) {
            return config('cdyun.response.' . $name, $default);
        }
        return config('cdyun.response');
    }

    /**
     * 响应结果
     * @param $result
     * @param array $header - 发送的Header信息
     * @param bool $isEncrypt - 是否需要加密
     * @author cdyun(121625706@qq.com)
     */
    public static function result($result, array $header = [], bool $isEncrypt = false): void
    {
        if ($isEncrypt && !empty($result['data'])) {
            try {
                $aesKey = request()->aes_key;
                $aesIv = request()->aes_iv;
                if (empty($aesKey) || empty($aesIv)) {
                    throw new \Exception('aes_key or aes_iv is empty');
                }
                $result['encrypt_data'] = EncryptorEnforcer::aesEncrypt($result['data'], $aesKey, $aesIv);
            } catch (\Exception $e) {
                self::error($e->getMessage());
            }
            unset($result['data']);
        }
        $response = Response::create($result, 'json')->header($header);
        throw new HttpResponseException($response);
    }

    /**
     * 构建和返回一个错误响应，允许通过参数自定义错误消息、错误代码和附加数据
     * @param array|string $msg - 错误消息，可以是字符串或数组如果提供数组，将被视为错误数据
     * @param mixed|null $data - 附加数据，用于提供额外的错误信息，默认为null
     * @param array $header - 发送的Header信息
     * @return void
     * @author cdyun(121625706@qq.com)
     */
    public static function error(array|string $msg = '操作失败', mixed $data = null,  array $header = []): void
    {
        $result = [];
        $result['code'] = self::getCode('error');
        $result['time'] = time();

        if (is_array($msg)) {
            $result['message'] = '操作失败';
            $result['data'] = $msg;
        } else {
            $result['message'] = $msg;
            if ($data !== null) {
                $result['data'] = $data;
            }
        }
        self::result($result, $header, false);
    }

    /**
     * 抛出异常并终止程序执行
     * @param string $msg - 错误消息
     * @param int $code - 错误代码
     * @return void
     * @author cdyun(121625706@qq.com)
     */
    public static function abort(string $msg = '服务器内部错误', int $code = 500): void
    {
        $response = Response::create($msg, 'json', $code);
        throw new HttpResponseException($response);
    }

    /**
     * 构造分页响应数据
     * @param array $data - 分页数据
     * @param int $totalCount - 总条目数
     * @param string $msg - 响应消息
     * @param array $header - 发送的Header信息
     * @return void
     * @author cdyun(121625706@qq.com)
     */
    public static function paginate(array $data = [], int $totalCount = 0, string $msg = '加载完成',array $header = []): void
    {
        // 校验 totalCount 合法性
        if (!is_int($totalCount) || $totalCount < 0) {
            $totalCount = 0;
        }

        $result = [
            'code' => self::getCode('success'),
            'message' => $msg,
            'data' => $data,
            'count' => $totalCount,
        ];

        $isEncrypt = self::getConfig('enable');
        self::result($result, $header, $isEncrypt);
    }
}