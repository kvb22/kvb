<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$strSql = "SELECT  `Group`.*, COUNT(Person.id) as cnt FROM `Group` LEFT JOIN `Person` ON `Person`.group_id = `Group`.id GROUP BY `Group`.id";

$res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

$arResult['persons'] = array();

while($res_arr = $res->Fetch()){
    $arResult['persons'][$res_arr['id']] = $res_arr;
}

$this->IncludeComponentTemplate($componentPage);