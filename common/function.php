<?php 

function getC(){
	$controller = isset($_GET['c'])?$_GET['c']:'Index';
	return ucfirst($controller."Action");
}

function getM(){
	$method = isset($_GET['m'])?$_GET['m']:'index';
    return  strtolower($method);
}

function p($data){
	echo "<pre>";
	print_r($data);
}


