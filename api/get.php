<?php
/*
 * Remark：
 *      set数据时，需要通过post传入数据，数据格式：time\tnumber\n...
 *
 */

require_once 'config.php';
require_once 'myutil.php';

/*
function main_db() {
    global $g_conf;

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

    if (!isset($_GET['key'])) {
        $ret['code'] = 1;
        $ret['value'] = "miss `key`";
        return $ret;
    }

    $key = $_GET['key'];
    $j_v = $db->get($key);
    if($j_v === false) {
        $ret['code'] = 2;
        $ret = "get fail";
        return $ret;
    } else if ($j_v === null) {
        $ret['code'] = 3;
        $ret['value'] = "no data";
        return $ret;
    } else {
        $_j = @json_decode($j_v, true);
        if($_j === false) {
            $ret['code'] = 2;
            $ret['value'] = "not valid json data";
        } else {
            $ret['value'] = $_j;
        }
        return $ret;
    }
}
 */

function main() {
    global $g_conf;

    $ret = array(
        'code' => 0,
        'value' => '',
        );

    if (!isset($_GET['key'])) {
        $ret['code'] = 1;
        $ret['value'] = "miss `key`";
        return $ret;
    }

    $key = $_GET['key'];
    $j_v = get_local($key);
    if($j_v === false) {
        $ret['code'] = 2;
        $ret = "get fail";
        return $ret;
    } else if ($j_v === null) {
        $ret['code'] = 3;
        $ret['value'] = "no data";
        return $ret;
    } else {
        $_j = @json_decode($j_v, true);
        if($_j === false) {
            $ret['code'] = 2;
            $ret['value'] = "not valid json data";
        } else {
            $ret['value'] = $_j;
        }
        return $ret;
    }
}

// main work
$_r = main();
echo json_encode($_r);
exit(0);

?>
