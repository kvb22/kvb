<?

Class person extends CModule
{
var $MODULE_ID = "person";
var $MODULE_VERSION;
var $MODULE_VERSION_DATE;
var $MODULE_NAME;
var $MODULE_DESCRIPTION;
var $MODULE_CSS;

function person()
{
$arModuleVersion = array();

$path = str_replace("\\", "/", __FILE__);
$path = substr($path, 0, strlen($path) - strlen("/index.php"));
include($path."/version.php");

if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
{
$this->MODULE_VERSION = $arModuleVersion["VERSION"];
$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
}

$this->MODULE_NAME = "Person";
$this->MODULE_DESCRIPTION = "Модуль для управления таблицей Person";
}

function InstallFiles($arParams = array())
{
CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/person/install/components",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
return true;
}

function UnInstallFiles()
{
echo "!".DeleteDirFilesEx("/bitrix/components/person")."!";
return true;
}

function DoInstall()
{
global $DOCUMENT_ROOT, $APPLICATION;
$this->InstallFiles();
RegisterModule("person");
$APPLICATION->IncludeAdminFile("Установка модуля Person", $DOCUMENT_ROOT."/bitrix/modules/person/install/step.php");
}

function DoUninstall()
{
global $DOCUMENT_ROOT, $APPLICATION;
$this->UnInstallFiles();
UnRegisterModule("person");
$APPLICATION->IncludeAdminFile("Деинсталляция модуля Person", $DOCUMENT_ROOT."/bitrix/modules/person/install/unstep.php");
}
}
?>