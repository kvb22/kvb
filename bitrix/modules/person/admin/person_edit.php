<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/person/include.php");

IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("REGISTERED_USERS");
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$aTabs = array(
	array("DIV" => "edit1", "TAB" => 'Редактирование', "ICON"=>"main_user_edit", "TITLE"=>'Редактирование'),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$ID = intval($ID);		// Id of the edited record
$message = null;
$bVarsFromForm = false;

if($REQUEST_METHOD == "POST" && ($save!="" || $apply!="") && $POST_RIGHT=="W" && check_bitrix_sessid())
{
	$rubric = new CPerson;
	$arFields = Array(
		"group_id"		=> $group_id,
		"first_name"		=> $first_name,
		"last_name"		=> $last_name,
		"gender"	=> $gender,
	);

	if($ID > 0)
	{
		$res = $rubric->Update($ID, $arFields);
	}
	else
	{
		$ID = $rubric->Add($arFields);
		$res = ($ID>0);
	}

	if($res)
	{
		if($apply!="")
			LocalRedirect("/bitrix/admin/person_edit.php?ID=".$ID."&mess=ok&lang=".LANG."&".$tabControl->ActiveTabParam());
		else
			LocalRedirect("/bitrix/admin/person.php?lang=".LANG);
	}
	else
	{
		if($e = $APPLICATION->GetException())
			$message = new CAdminMessage(GetMessage("rub_save_error"), $e);
		$bVarsFromForm = true;
	}

}

//Edit/Add part
ClearVars();

if($ID>0)
{
	$rubric = CPerson::GetByID($ID);
	if(!$rubric->ExtractFields("str_"))
		$ID=0;
}
if($ID>0 && !$message)
	$DAYS_OF_WEEK = explode(",", $str_DAYS_OF_WEEK);
if(!is_array($DAYS_OF_WEEK))
	$DAYS_OF_WEEK = array();

if($bVarsFromForm)
	$DB->InitTableVarsForEdit("person", "", "str_");

$APPLICATION->SetTitle(($ID>0? "Редактирование персоны ".$ID : "Добавление персоны"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$aMenu = array(
	array(
		"TEXT"=>"Список",
		"TITLE"=>"Список",
		"LINK"=>"person.php?lang=".LANG,
		"ICON"=>"btn_list",
	)
);
if($ID>0)
{
	$aMenu[] = array("SEPARATOR"=>"Y");
	$aMenu[] = array(
		"TEXT"=>"Добавить",
		"TITLE"=>"Добавить",
		"LINK"=>"person_edit.php?lang=".LANG,
		"ICON"=>"btn_new",
	);
	$aMenu[] = array(
		"TEXT"=>"Удалить",
		"TITLE"=>"Удалить",
		"LINK"=>"javascript:if(confirm('Действительно удалить?'))window.location='person.php?ID=".$ID."&action=delete&lang=".LANG."&".bitrix_sessid_get()."';",
		"ICON"=>"btn_delete",
	);
	$aMenu[] = array("SEPARATOR"=>"Y");
}
$context = new CAdminContextMenu($aMenu);
$context->Show();
?>

<?
if($_REQUEST["mess"] == "ok" && $ID>0)
	CAdminMessage::ShowMessage(array("MESSAGE"=>"Данные сохранены успешно", "TYPE"=>"OK"));

if($message)
	echo $message->Show();
elseif($rubric->LAST_ERROR!="")
	CAdminMessage::ShowMessage($rubric->LAST_ERROR);
?>

<form method="POST" Action="<?echo $APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="post_form">
<?
$tabControl->Begin();
?>
<?

$tabControl->BeginNextTab();
$arSelectBox = CPerson::GroupSelectBox($str_group_id);
?>
	<tr>
		<td width="40%"><?echo 'Группа'?></td>
		<td width="60%">
            <select name="group_id">
                <?foreach($arSelectBox as $k=>$v):?>
                    <option value="<?=$k?>"<?echo($v['selected'] ? ' selected' : '');?>><?=$v['title']?></option>
                <?endforeach;?>
            </select>
        </td>
	</tr>
	<tr class="adm-detail-required-field">
		<td><?echo "Имя"?></td>
		<td><input type="text" name="first_name" value="<?echo $str_first_name;?>" size="45"last_name></td>
	</tr>
	<tr>
		<td><?echo "Фамилия"?></td>
		<td><input type="text" name="last_name" value="<?echo $str_last_name;?>" size="45"last_name></td>
	</tr>
	<tr>
		<td><?echo "Пол"?></td>
		<td>
            <select name="gender">
                <option value='m'<?echo ($str_gender == 'm' ? ' selected' : '') ?>>М</option>
                <option value='f'<?echo ($str_gender == 'f' ? ' selected' : '') ?>>Ж</option>
            </select>
        </td>
	</tr>
<?
$tabControl->Buttons(
	array(
		"disabled"=>($POST_RIGHT<"W"),
		"back_url"=>"person.php?lang=".LANG,

	)
);
?>
<?echo bitrix_sessid_post();?>
<input type="hidden" name="lang" value="<?=LANG?>">
<?if($ID>0 && !$bCopy):?>
	<input type="hidden" name="ID" value="<?=$ID?>">
<?endif;?>
<?
$tabControl->End();
?>

<?
$tabControl->ShowWarnings("post_form", $message);
?>

<script language="JavaScript">
<!--
	if(document.post_form.AUTO.checked)
		tabControl.EnableTab('edit2');
	else
		tabControl.DisableTab('edit2');
//-->
</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>