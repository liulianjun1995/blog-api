<?php

use Hashids\Hashids;
use Illuminate\Support\Facades\Storage;

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

