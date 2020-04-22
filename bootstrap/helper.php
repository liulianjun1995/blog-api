<?php

use Hashids\Hashids;

if (!function_exists('encode_id')) {

    /**
     * id 加密
     * @param int $id
     * @return string
     */
    function encode_id(int $id)
    {
        $hash = new Hashids('blog', 10);
        return $hash->encode($id);
    }
}

if (!function_exists('decode_id')) {

    /**
     * token 解密
     * @param string $hash_id
     * @return int
     */
    function decode_id(string $hash_id)
    {
        $hash = new Hashids('blog', 10);
        $id = $hash->decode($hash_id);
        if (!empty($id) && isset($id[0])) {
            return (int)$id[0];
        }
        return 0;
    }
}

if (!function_exists('valid_ip')) {

    /**
     * 验证有效ip
     * @return bool
     */
    function valid_ip($ip = null)
    {
        if (!isset($ip)) {
            return false;
        }

        return validate($ip);
    }

    /**
     * 验证ip
     * @param $ip
     * @return bool
     */
    function validate($ip)
    {
        return _is_ip4($ip) || _is_ipv6($ip);
    }

    /**
     * @param $ip
     *
     * @return bool
     */
    function _is_ip4($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * @param $ip
     *
     * @return bool
     */
    function _is_ipv6($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }
}
