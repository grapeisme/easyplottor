<?php
/*
 * Remark：
 *      set数据时，需要通过post传入数据，数据格式：time\tnumber\n...
 *
 */

require_once 'config.php';
require_once 'myutil.php';


function fmtTimePoint($tm, $num) {
    $common_tm = "";    // "2009-10-21 16:00:10"

    $arr = explode(" ", $tm);
    if(count($arr) > 1) {
        $days = $arr[0];
        $secs = $arr[1];

        // days
        if(preg_match("/^\d{4}-\d{2}-\d{2}/i", $days)) { // 2009-10-21
            //$days = $days;
        }
        else if(preg_match("/^(\d{4})(\d{2})(\d{2})/i", $days, $m)) {    // 20091021
            $days = sprintf("%s-%s-%s", $m[1], $m[2], $m[3]);
        }
        else if(preg_match("/^(\d{4})年(\d{2})月(\d{2})日/i", $days, $m)) {    // 2009年10月21日
            $days = sprintf("%s-%s-%s", $m[1], $m[2], $m[3]);
        }
        else {
            return false;
        }

        // seconds
        if(preg_match("/^\d{2}:\d{2}:\d{2}/i", $secs)) { // 16:00:10
            //$secs = $secs;
        }
        else if(preg_match("/^(\d{2})时(\d{2})分(\d{2})秒/i", $secs, $m)) {    // 16时00分10秒
            $secs = sprintf("%s:%s:%s", $m[1], $m[2], $m[3]);
        }
        else {
            return false;
        }

        $common_tm = $days . " " . $secs;
    }
    else {
        if(preg_match("/^(\d{10})/i", $tm, $m)) {    // 1452477977
            $common_tm = date("Y-m-d H:i:s", $m[1]);
        }
        else if(preg_match("/(\d{4})年(\d{2})月(\d{2})日(\d{2}:\d{2}:\d{2})/i", $tm, $m)) {    // 2009年10月21日16:00:10
            $common_tm = sprintf("%s-%s-%s %s", $m[1], $m[2], $m[3], $m[4], $m[5]);
        }
        else if(preg_match("/(\d{4})年(\d{2})月(\d{2})日(\d{2})时(\d{2})分(\d{2})秒/i", $tm, $m)) {    // 2009年10月21日16时00分10秒
            $common_tm = sprintf("%s-%s-%s %s:%s:%s", $m[1], $m[2], $m[3], $m[4], $m[5], $m[6]);
        }
        else if(preg_match("/^(\d{2})时(\d{2})分(\d{2})秒/i", $tm, $m)) {    // 16时00分10秒
            $common_tm = sprintf("%s %s:%s:%s", date("Y-m-d"), $m[1], $m[2], $m[3]);
        }
        else if(preg_match("/^(\d{2}:\d{2}:\d{2})/i", $tm, $m)) {    // 16:00:10
            $common_tm = sprintf("%s %s", date("Y-m-d"), $m[1]);
        }
        else if(preg_match("/^(\d{4})年(\d{2})月(\d{2})日/i", $tm, $m)) {    // 2009年10月21日
            $common_tm = sprintf("%s-%s-%s 00:00:00", $m[1], $m[2], $m[3]);
        }
        else if(preg_match("/^(\d{4})(\d{2})(\d{2})/i", $tm, $m)) {    // 20091021
            $common_tm = sprintf("%s-%s-%s 00:00:00", $m[1], $m[2], $m[3]);
        }
        else if(preg_match("/^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/i", $tm, $m)) {    // 20091021
            $common_tm = sprintf("%s-%s-%s %s:%s:%s", $m[1], $m[2], $m[3], $m[4], $m[5], $m[6]);
        }
        else {
            return false;
        }
    }

    $_tm = strtotime($common_tm);
    $_num = intval($num);

    return array($_tm, $_num);
}

function getWebAddr($key) {
    $url = $_SERVER['REQUEST_URI'];
    $url = dirname(dirname($url)) . '/index.php?key=' . $key;
    $url = "http://" . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . $url;
    return $url;
}

/*
function main_db() {
    global $g_conf;

    $G_RAW_POST = file_get_contents("php://input");		// 获取post原始字符串

    $ret = array(
        'code' => 0,
        'value' => '',
        );

    $db = initRedis2($g_conf['redis']);
    if ($db === false) {
        $ret['code'] = 1;
        $ret['value'] = "connect db failed";
        return $ret;
    }

    $key = genKey();
    if (!$G_RAW_POST) {
        $ret['code'] = 1;
        $ret['value'] = "miss post data";
        return $ret;
    }

    $_v = array(
        'time' => date("Y-m-d H:i:s", time()),
        'points' => array(),
    );
    if(isset($_GET['name'])) {
        $_v['name'] = $_GET['name'];
    }

    $arr = explode("\n", $G_RAW_POST);
    $index = 0;
    foreach($arr as $line) {
        $line = trim($line);
        if(!$line)
            continue;
        $_sp = explode("\t", $line);
        if(count($_sp) > 1)  {
            $_point = fmtTimePoint($_sp[0], $_sp[1]);
            if(!$_point) {
                $ret['code'] = 2;
                $ret['value'] = "contain invalide point:" . $line;
                return $ret;
            }
            $_v['points'][] = $_point;
        }
        else {
            $_v['points'][] = array($index, intval($_sp[0]));
        }

        $index++;
    }
    
    $j_v = json_encode($_v);

    if(false === $db->set($key, $j_v)) {
        $ret['code'] = 2;
        $ret['value'] = "save db fail";
        return $ret;
    }

    $db->expire($key, 86400 * $g_conf['def_expire']);
    $ret['value'] = getWebAddr($key);
    return $ret;
}
 */

function main() {
    global $g_conf;

    $G_RAW_POST = file_get_contents("php://input");		// 获取post原始字符串

    $ret = array(
        'code' => 0,
        'value' => '',
        );

    $key = genKey();
    if (!$G_RAW_POST) {
        $ret['code'] = 1;
        $ret['value'] = "miss post data";
        return $ret;
    }

    $_v = array(
        'time' => date("Y-m-d H:i:s", time()),
        'points' => array(),
    );
    if(isset($_GET['name'])) {
        $_v['name'] = $_GET['name'];
    }

    $arr = explode("\n", $G_RAW_POST);
    $index = 0;
    foreach($arr as $line) {
        $line = trim($line);
        if(!$line)
            continue;
        $_sp = explode("\t", $line);
        if(count($_sp) > 1)  {
            $_point = fmtTimePoint($_sp[0], $_sp[1]);
            if(!$_point) {
                $ret['code'] = 2;
                $ret['value'] = "contain invalide point:" . $line;
                return $ret;
            }
            $_v['points'][] = $_point;
        }
        else {
            $_v['points'][] = array($index, intval($_sp[0]));
        }

        $index++;
    }
    
    $j_v = json_encode($_v);

    if (!set_local($key, $j_v)) {
        $ret['code'] = 2;
        $ret['value'] = "save fail";
        return $ret;
    }

    $ret['value'] = getWebAddr($key);
    return $ret;
}

// main work
$_r = main();
if($_r['code'] === 0) {
    //printf("Successfully plot (keep data available during %d days):\n%s\n", $g_conf['def_expire'], $_r['value']);
    printf("Successfully plot:\n%s\n", $_r['value']);
} else {
    printf("Error: %s\n", $_r['value']);
}
exit(0);

?>
