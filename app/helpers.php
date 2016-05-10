<?php

function getFixture($name)
{
    $path = base_path('/fixture/'.$name.'.json');
    $data = file_get_contents($path);
    return json_decode($data);
}
