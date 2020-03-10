<?php

use Hashids\Hashids;
use Illuminate\Support\Facades\Storage;

if (!function_exists('encode_id')) {

    /**
     * @param $id
     * @return string
     */
    function encode_id($id)
    {
        $hash = new Hashids('blog', 10);
        return $hash->encode($id);
    }
}

if (!function_exists('decode_id')) {

    /**
     * @param $hash_id
     * @return string
     */
    function decode_id($hash_id)
    {
        $hash = new Hashids('blog', 10);
        $id = $hash->decode($hash_id);
        if (!empty($id) && isset($id[0])){
            return $id[0];
        }
        return '';
    }
}

