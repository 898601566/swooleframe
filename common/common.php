<?php


/**
 *打印
 * @param $msg
 * @param int $red
 */
function the_print($msg, $red = 1)
{
    if (is_array($msg)) {
        echo "<pre>";
        var_export($msg);
        echo "</pre>";
    } else {
        $msg = $msg == FALSE ? 'false' : str_replace(';<br>', ";<br>", $msg);
        $red = $red ? 'red' : 'black';
        echo "<p style='font-size: 16px; color: {$red}'><b>{$msg}</b></p>";
    }
}

function halt_plus($source = '')
{
    $args_num = func_num_args();
    $args = func_get_args();
    for ($i = 0; $i < $args_num - 1; $i++) {
        dump($args[$i]);
    }
    return halt($args[$i]);
}


function dump($source = '')
{
    echo "<pre>";
    var_dump($source);
    echo '<br>';
    return;
}

function halt($source = '')
{
    echo "<pre>";
    var_dump($source);
    exit;
    return;
}


/**
 * array_merge但是保留key(当索引是数字是 array_merge会重新设置索引)
 * @param array $source_arr1 数组1
 * @param string $source_arr2 数组2
 * @return array 目标数组
 */
function array_merge_plus(array $source_arr1)
{
    $args_num = func_num_args();
    $args_arr = func_get_args();
    for ($i = 1; $i < $args_num; $i++) {
        foreach ($args_arr[$i] as $key => $value) {
            $source_arr1[$key] = $value;
        }
    }
    return $source_arr1;
}

/**
 * 使用一个字符串分割另一个字符串(两次),注意'\n'和"\n"是不同的
 * @param $subject
 * @param $delimiter1
 * @param $delimiter2
 * @param $clear 是否跳过空白字符串,默认跳过
 * @return array
 */
function explode_double($subject, $delimiter1, $delimiter2, $clear = 1)
{
    $subject = explode($delimiter1, $subject);
    $content = [];
    foreach ($subject as $key => $value) {
        if ($clear || (!empty($value) && FALSE == preg_match('/^\s*$/', $value))) {
            $content[] = explode($delimiter2, $value);
        }
    }
    return $content;
}

//css
function htmltag($tag, $style, $str)
{
    return '<' . $tag . ' style="' . $style . '">' . $str . '</' . $tag . '>';
}

function start_pre()
{
    ob_start();
    echo "<pre>";

}

function end_pre($print = 1)
{
    echo "</pre>";
    $html_content = ob_get_clean();
    $print == 1 ? the_print($html_content) : "";
    return $html_content;
}



