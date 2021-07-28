<?php

namespace fastswoole;

class Config
{
    static $file = [];

    /**
     * @param $name
     * @return array|mixed
     */
    public function load($name)
    {
        $ret = [];
        if (!empty($name)) {
            $file_path = explode(".", $name);
            if (!empty($file_path)) {
                $real_path = sprintf('%s%s%s%s', APP_PATH, 'config/', $file_path[0], '.php');
                if (is_file($real_path)) {
                    if (empty(static::$file[$real_path])) {
                        static::$file[$real_path] = require_once($real_path);
                    }
                    $ret = static::$file[$real_path];
                    unset($file_path[0]);
                    foreach ($file_path as $key => $value) {
                        if (isset($ret[$value])) {
                            $ret = $ret[$value];
                        }
                    }
                }
            }
        }
        return $ret;
    }
}
