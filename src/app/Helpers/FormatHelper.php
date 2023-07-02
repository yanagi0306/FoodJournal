<?php

namespace App\Helpers;

class FormatHelper
{
    public static function formatStringForHtml(string $str): string
    {
        $str = str_replace("\n", '<br>', $str);
        $str = str_replace(' ', '&nbsp;', $str);
        return $str;
    }
}
