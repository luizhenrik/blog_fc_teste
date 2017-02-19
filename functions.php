<?php
// Automatically load all functions, shortcodes and widgets
$_dirs = array(
    TEMPLATEPATH . '/includes/functions/*.php',
);

if (is_admin())
    $_dirs[] = TEMPLATEPATH . '/admin/*.php';

foreach ($_dirs as $_dir) {
    foreach (glob($_dir) as $_file) {
        require_once $_file;
    }
}
