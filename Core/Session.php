<?php

namespace Core;

class Session
{
    public static function get($key): string|array
    {
        // if(isset($_SESSION['_flash']) && isset($_SESSION['_flash'][$key])){
        //     return $_SESSION['_flash'][$key];
        // }elseif(isset($_SESSION[$key])){
        //     return $_SESSION[$key];
        // }else {
        //     return '';
        // }

        if(isset($_SESSION['_flash'][$key])){
            $flash = $_SESSION['_flash'][$key];
            self::unflash($key);
            return $flash;
        }

        return $_SESSION[$key] ?? '';
    }

    public static function has($key): bool
    {
        return (bool)self::get($key);
    }

    public static function put($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function flash($key, $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function unflash(string $key = null): void
    {
        if($key){
            unset($_SESSION['_flash'][$key]);
        } else {
            unset($_SESSION['_flash']);
        }
    }

    public static function clear(): void
    {
        $_SESSION = [];
    }

    public static function destroy(): void
    {
        static::clear();

        session_destroy();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
}