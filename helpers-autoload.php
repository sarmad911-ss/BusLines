<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 3/17/2019
 * Time: 5:04 PM
 */
$helpersDir= __DIR__."/app/Helpers";
$list = scandir($helpersDir);
$list = array_filter($list,function($path){
   return strpos($path,".php")!==false;
});
foreach ($list as $helper)
    require $helpersDir."/".$helper;