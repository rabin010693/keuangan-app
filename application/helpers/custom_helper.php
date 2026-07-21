<?php
if (!function_exists('uri_segment')) {
    function uri_segment($n, $default = FALSE) {
        $CI =& get_instance();
        return $CI->uri->segment($n, $default);
    }
}