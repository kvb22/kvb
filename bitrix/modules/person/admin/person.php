<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/person/include.php");
//require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/subscribe/prolog.php");

IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("subscribe");
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$sTableID = "tbl_person";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);

function CheckFilter()
{
	global $FilterArr, $lAdmin;
	foreach ($FilterArr as $f) global $$f;
	if (strlen(trim($find_last_executed_1))>0 || strlen(trim($find_last_executed_2))>0)
	{
		$date_1_ok = false;
		$date1_stm = MkDateTime(FmtDate($find_last_executed_1,"D.M.Y"),"d.m.Y");
		$date2_stm = MkDateTime(FmtDate($find_last_executed_2,"D.M.Y")." 23:59","d.m.Y H:i");
		if (!$date1_stm && strlen(trim($find_last_executed_1))>0)
			$lAdmin->AddFilterError(GetMessage("rub_wrong_generation_from"));
		else $date_1_ok = true;
		if (!$date2_stm && strlen(trim($find_last_executed_2))>0)
			$lAdmin->AddFilterError(GetMessage("rub_wrong_generation_till"));
		elseif ($date_1_ok && $date2_stm <= $date1_stm && strlen($date2_stm)>0)
			$lAdmin->AddFilterError(GetMessage("rub_wrong_generation_from_till"));
	}
	return count($lAdmin->arFilterErrors)==0;
}

$FilterArr = Array(
	
);

$lAdmin->InitFilter($FilterArr);

if($lAdmin->EditAction() && $POST_RIGHT=="W")
{
	foreach($FIELDS as $ID=>$arFields)
	{
		if(!$lAdmin->IsUpdated($ID))
			continue;
		$DB->StartTransaction();
		$ID = IntVal($ID);
		$cData = new CPerson;
		if(($rsData = $cData->GetByID($ID)) && ($arData = $rsData->Fetch()))
		{
			foreach($arFields as $key=>$value)
				$arData[$key]=$value;
			if(!$cData->Update($ID, $arData))
			{
				$lAdmin->AddGroupError(GetMessage("rub_save_error")." ".$cData->LAST_ERROR, $ID);
				$DB->Rollback();
			}
		}
		else
		{
			$lAdmin->AddGroupError(GetMessage("rub_save_error")." ".GetMessage("rub_no_rubric"), $ID);
			$DB->Rollback();
		}
		$DB->Commit();
	}
}

if(($arID = $lAdmin->GroupAction()) && $POST_RIGHT=="W")
{
	if($_REQUEST['action_target']=='selected')
	{
		$cData = new CPerson;
		$rsData = $cData->GetList(array($by=>$order), $arFilter);
		while($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}

	foreach($arID as $ID)
	{
		if(strlen($ID)<=0)
			continue;
		$ID = IntVal($ID);
		switch($_REQUEST['action'])
		{
		case "delete":
			@set_time_limit(0);
			$DB->StartTransaction();
			if(!CPerson::Delete($ID))
			{
				$DB->Rollback();
				$lAdmin->AddGroupError('При удалении произошла ошибка', $ID);
			}
			$DB->Commit();
			break;
		}

	}
}

$cData = new CPerson;
$rsData = $cData->GetList(array($by=>$order), $arFilter);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("rub_nav")));

$lAdmin->AddHeaders(array(
	array(	"id"		=>"ID",
		"content"	=>"id",
		"sort"		=>"id",
		"align"		=>"right",
		"default"	=>true,
	),
	array(	"id"		=>"first_name",
		"content"	=>'Имя',
		"sort"		=>"first_name",
		"default"	=>true,
	),
	array(	"id"		=>"last_name",
		"content"	=>'Фамилия',
		"sort"		=>"last_name",
		"default"	=>true,
	),
	array(	"id"		=>"group",
		"content"	=>'Группа',
		"sort"		=>"group_id",
		"align"		=>"right",
		"default"	=>true,
	),
	array(	"id"		=>"gender",
		"content"	=>'Пол',
		"sort"		=>"gender",
        "align"		=>"right",
		"default"	=>false,
	)
));

while($arRes = $rsData->NavNext(true, "f_")):
	$row =& $lAdmin->AddRow($f_id, $arRes);

    $row->AddViewField("ID", $f_id);
	$row->AddViewField("first_name", '<a href="person_edit.php?ID='.$f_id.'&amp;lang='.LANG.'">'.$f_first_name.'</a>');
    $row->AddViewField("last_name", '<a href="person_edit.php?ID='.$f_id.'&amp;lang='.LANG.'">'.$f_last_name.'</a>');
    $row->AddViewField("group", $f_group_title);
    $row->AddViewField("gender", $f_gender=="m"?"М":"Ж");

	$arActions = Array();

	$arActions[] = array(
		"ICON"=>"edit",
		"DEFAULT"=>true,
		"TEXT"=>GetMessage("MAIN_ADMIN_LIST_EDIT"),
		"ACTION"=>$lAdmin->ActionRedirect("person_edit.php?ID=".$f_id)
	);
	if ($POST_RIGHT>="W")
		$arActions[] = array(
			"ICON"=>"delete",
			"TEXT"=>GetMessage("MAIN_ADMIN_LIST_DELETE"),
			"ACTION"=>"if(confirm('Действительно удалить?')) ".$lAdmin->ActionDoGroup($f_id, "delete")
		);

	$arActions[] = array("SEPARATOR"=>true);

	if(is_set($arActions[count($arActions)-1], "SEPARATOR"))
		unset($arActions[count($arActions)-1]);
	$row->AddActions($arActions);

endwhile;

$lAdmin->AddFooter(
	array(
		array("title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=>$rsData->SelectedRowsCount()),
		array("counter"=>true, "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"),
	)
);
$lAdmin->AddGroupActionTable(Array(
	"delete"=>GetMessage("MAIN_ADMIN_LIST_DELETE")
	));

$aContext = array(
	array(
		"TEXT"=>GetMessage("MAIN_ADD"),
		"LINK"=>"person_edit.php?lang=".LANG,
		"TITLE"=>GetMessage("POST_ADD_TITLE"),
		"ICON"=>"btn_new",
	),
);
$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("rub_title"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

?>

<?$lAdmin->DisplayList();?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>