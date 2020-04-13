<?php

namespace App\Enums;

class UserConstants
{
    /**
     * 用户状态 启用
     */
    public const USER_STATUS_ENABLED = 1;
    /**
     * 用户状态 禁用
     */
    public const USER_STATUS_DISABLED = 0;
    /**
     * 用户角色 普通
     */
    public const USER_ROLE_NORMAL = 1;
    /**
     * 用户角色 管理员
     */
    public const USER_ROLE_ADMIN = 99;

    public static function getRole($role): string
    {
        switch ($role) {
            case self::USER_ROLE_NORMAL:
                return 'normal';
            case self::USER_ROLE_ADMIN:
                return 'admin';
        }

        return 'normal';
    }

    public static function getRoleLabel($role): string
    {
        switch ($role) {
            case self::USER_ROLE_NORMAL:
                return '普通用户';
            case self::USER_ROLE_ADMIN:
                return '管理员';
        }

        return '普通用户';
    }

    public static function getStatusLabel($status): string
    {
        switch ($status) {
            case self::USER_STATUS_ENABLED:
                return '启用';
            case self::USER_STATUS_DISABLED:
                return '禁用';
        }

        return '异常';
    }
}
