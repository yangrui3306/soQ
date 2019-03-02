<?php
namespace App\Api;
use App\Common\Match;

$a=new Match();
$num=$a->levenshtein("abc","abd");
echo 1;