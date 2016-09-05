<?php 


/**
 * PHP SqlTrace
 * ============================================================================
 * * 版权所有 
 * 博客地址: http://gaozhongyi.sinaapp.com/；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。您也可以加入QQ群119286361做
 * 深层次的探讨
 * 注意：打开页面后，请勿频繁手动刷新页面。否则会产品额外sql记录。
 * ============================================================================
 * $Author: gaozhongyi 
 * $QQ : 673973036  $qq群：119286361
 * $Date 2015/12/19 $end
*/


require './lib/mysql.class.php';
require './common/function.php';
require './lib/Action.class.php';

define('__ROOT__', dirname($_SERVER['SCRIPT_FILENAME']));

define('TRACE_PATH', __ROOT__.'/runtime/trace.log');

$controller = getC();
$method = getM();


include('./controller/'.$controller.'.class.'.'php');

$class = new $controller;

$class->$method();;
//添加注释


