<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$strSql = "SELECT Person.gender, COUNT(Person.id) as cnt FROM Person GROUP BY Person.gender";

$res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

$arResult['persons'] = array();

while($res_arr = $res->Fetch()){
    $arResult['persons'][$res_arr['gender']] = $res_arr;
}

$this->IncludeComponentTemplate($componentPage);