<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait AliOss
{
    protected function signatureConfig($dir = '')
    {
        $prefix = '/';

        if ($dir) {
            $prefix = trim($dir, '/') . '/';
        }
        $config =  Storage::disk('oss')->signatureConfig($prefix, $callBackUrl = '', $customData = [], $expire = 30);
        return json_decode($config, true);
    }
}
