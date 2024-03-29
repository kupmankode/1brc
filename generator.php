<?php
$handle = fopen('data/weather_stations.csv', 'r');
// loadd all station names
$stations = [];
if ($handle != false) {
    fgets($handle, 1000);
    fgets($handle, 1000);
    while(($data = fgets($handle, 1000)) != false) {
        $arr = explode(';', $data);
        if (count($arr) == 2) {
            $stations[] = $arr[0];
        }
    }
}
if (empty($stations))
    die('Check station template file');
//get number of rows from command line
$rows = 1000000000;

//set temperature range
$temp_range = [-99, 99];
$time = time();
print date('c', time()).PHP_EOL;
print "Use generator to write ".number_format($rows)." rows".PHP_EOL;
print "Memory usage in MB: ".(memory_get_usage() / (1024 * 1024)).PHP_EOL;

// if less than millions, use array otherwise use generator
if ($rows < 1000000001) {
    $arr = build_weather_data($stations, $temp_range, $rows);
    $str = "";
    foreach ($arr as $ar) {
        $str .= $ar."\n";
    }
    print "Memory usage to process array and store string in MB: ".(memory_get_usage() / (1024 * 1024)).PHP_EOL;
    print date('c', time()).PHP_EOL;
    file_put_contents('data/result.csv', $str);
    
    
}
print "Execute time is: ". (time() - $time) . PHP_EOL;
print date('c', time()).PHP_EOL;
/////////////////////////////////////////////////////
function build_weather_data($stations, $temp_range, $rows) {
    $total = count($stations) - 1;
    for($i=0;$i<$rows;$i++) {
        yield $stations[rand(0,$total)].';'.rand($temp_range[0], $temp_range[1]) + (rand(1,99) / 100);
    }
}