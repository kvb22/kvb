<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$genders = array('m','f');

$arResult['persons'] = array();

foreach($genders as $v){

    $strSql = "SELECT Person.*, `Group`.title as group_name FROM Person LEFT JOIN `Group` ON `Person`.group_id = `Group`.id WHERE gender='".$v."' ORDER BY id DESC LIMIT 3";

    $res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

    while($res_arr = $res->Fetch()){
        $arResult['persons'][$res_arr['id']] = $res_arr;
    }

}

$this->IncludeComponentTemplate($componentPage);