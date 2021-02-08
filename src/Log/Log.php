<?php

namespace Log;

class Log
{
    public function interpolate($message, array $context = array())
    {
        $replace = array();
        foreach ($context as $key => $val) {

            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }
}
