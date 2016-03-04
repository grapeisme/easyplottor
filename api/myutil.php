<?php
require_once 'config.php';
date_default_timezone_set('Asia/Shanghai');

function genKey() {
    $key =  md5(time() . '@' . rand(0,1000). '@' . $_SERVER["REMOTE_ADDR"]);
    $date_tm = date("Ymd");
    return $date_tm . $key;
}

function parseDir($key) {
    $date_len = 8;  // 20160118
    $date_tm = substr($key, 0, $date_len);

    return $date_tm;
}

function set_local($key, $info) {
    global $g_conf;

    $date_tm = parseDir($key);
    $file_dir = $g_conf['data_dir'] . '/' . $date_tm;
    $file_name = $file_dir . '/' . $key;

    if(!is_dir($file_dir)) {
        if(!mkdir($file_dir, 0777, true)) {
            return false;
        }
        chmod($file_dir, 0777);     // 在mkdir中设定权限未生效，受系统权限限制，而chmod不会
    }
    file_put_contents($file_name, $info);
    chmod($file_name, 0777);

    return true;
}

function get_local($key) {
    global $g_conf;

    $date_tm = parseDir($key);
    $file_dir = $g_conf['data_dir'] . '/' . $date_tm;
    $file_name = $file_dir . '/' . $key;

    if(!is_file($file_name)) 
        return false;

    return file_get_contents($file_name);
}

?>
