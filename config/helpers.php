<?php

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

if (!function_exists('__admin_sortable')) {
    /**
     * Echo sortable url
     *
     * @param $field
     *
     * @return string
     */
    function __admin_sortable($field)
    {
        echo '<a href="' . \Request::fullUrlWithQuery(['sort' => $field, 'is_desc' => !request('is_desc', false)]) . '"class="ic-ca">';
        if (request('sort') == $field && request('is_desc') == 1) {
            echo '<span class="dropup"><span class="caret"></span></span>';
        } else {
            echo '<span class="caret"></span>';
        }
        echo '</a>';
    }
}
if (!function_exists('generate_slug_url')) {
    function generate_slug_url($name, $randomLength = 5)
    {
        return Str::slug($name) . '-' . Str::random($randomLength);
    }
}
if (!function_exists('__a')) {
    function __a($key, $attrs = [])
    {
        return __('admin.' . $key, $attrs);
    }
}
if (!function_exists('__f')) {
    function __f($key, $attrs = [])
    {
        if (Lang::has('front.' . $key)) {
            return __('front.' . $key, $attrs);
        } else {
            return $key;
        }
    }
}
if (!function_exists('getThumb')) {
    function getThumb($filePath, $suffix = 320)
    {
        $pathInfo = pathinfo($filePath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '-' . $suffix . '.' . $pathInfo['extension'];
    }
}

if (!function_exists('getThumbFeature')) {
    function getThumbFeature($filePath)
    {
        $sizes = FEATURE_SIZES;
        $results = [];
        foreach ($sizes as $size) {
            $results[] = getThumb($filePath, $size) . ' ' . $size . "w";
        }
        return implode(',', $results);
    }
}
if (!function_exists('getThumbImage')) {
    function getThumbImage($filePath)
    {
        $sizes = THUMB_SIZES;
        $results = [];
        foreach ($sizes as $size) {
            $results[] = getThumb($filePath, $size) . ' ' . $size . "w";
        }
        return implode(', ', $results);
    }
}

if (!function_exists('route_lang')) {
    /**
     * Generate the URL to a named route.
     *
     * @param array|string $name
     * @param mixed $parameters
     * @param string $lang
     * @param bool $absolute
     * @return string
     */
    function route_lang($name, $parameters = [], $lang = null, $absolute = true)
    {
        $langSegment = request()->segment(1);
        $prefix = '';
        if ($lang) {
            $prefix = $lang;
        } else {
            if ($langSegment == LANG_VI || $langSegment == LANG_EN) {
                if ($langSegment != DEFAULT_LANG) {
                    $prefix = $langSegment;
                }
            }
        }
        return route($prefix . $name, $parameters, $absolute);
    }
}

if (!function_exists('convertYoutube')) {

    function convertYoutube($string)
    {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "<iframe src=\"//www.youtube.com/embed/$2\" allowfullscreen></iframe>",
            $string
        );
    }
}
