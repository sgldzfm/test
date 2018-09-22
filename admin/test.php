<?php
$data = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

var_dump(json_decode($data));

echo (json_decode($data,true)['c']);