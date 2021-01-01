<?php

$str_json = file_get_contents('php://input');

$json_a = json_decode($str_json, true);

$myfile = fopen("json.txt", "a");
fwrite($myfile, $str_json);

$jsDateTS = strtotime($json_a['time-begin']);
if ($jsDateTS !== false)
{
    fwrite($myfile, "\nConverted into PHP time\n");
    $begin_date = date('Y-m-d H:i:s', $jsDateTS);
    $end_date = date('Y-m-d H:i:s', strtotime($json_a['time-end']));

    fwrite($myfile, "\n$begin_date\n");
    fwrite($myfile, "\n$end_date\n");
}
else
{
    fwrite($myfile, "\nConversion failed!\n");
}

$result = json_encode($json_a);
fwrite($myfile, $result);

echo $result;
fclose($myfile);
?>