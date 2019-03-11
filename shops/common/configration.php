<?php
$site_name = "My shopping Site";
$charset = "utf-8";
$database_host = "localhost";
$database_user = "superwf1";
$database_password = "Jmok@2012";
$database_set_string = "SET NAMES utf8";
$database_name = "superwf1_pizza_db";
$database_connect_limit = 2;
$loglist_limit = 10;

$config_absolute_path = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']);
$site_root = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/";
$base_url = "http://" . $_SERVER['HTTP_HOST'] . '/';
$shop_url = $base_url . $shopname;
?>