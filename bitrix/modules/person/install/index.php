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
<<<<<<< HEAD
CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/person/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/person/install/components",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
return true;
}

function UnInstallFiles()
{
DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/person/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
DeleteDirFilesEx("/bitrix/components/person");
return true;
}

function DoInstall()
{
global $DOCUMENT_ROOT, $APPLICATION;
if( $this->InstallDB() )
{
    $this->InstallFiles();
}
RegisterModule("person");
$APPLICATION->IncludeAdminFile("Установка модуля Person", $DOCUMENT_ROOT."/bitrix/modules/person/install/step.php");
}

function InstallDB()
{
    
    global $DB;
 
     $sql = "
 
DROP TABLE IF EXISTS `Person`;

    ";
    
    $dbRes = $DB->Query($sql); 
 
    $sql = "

CREATE TABLE IF NOT EXISTS `Person` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gender` enum('f','m') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ";
    
    $dbRes = $DB->Query($sql); 
    
    $sql = "ALTER TABLE `Person` ADD PRIMARY KEY (`id`);";
    
    $dbRes = $DB->Query($sql);  
    
    $sql = "ALTER TABLE `Person` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
    
    $dbRes = $DB->Query($sql);       
    
    $sql = "DROP TABLE IF EXISTS `Group`;";
    
    $dbRes = $DB->Query($sql);    
    
    $sql = "

CREATE TABLE IF NOT EXISTS `Group` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ";
    
    $dbRes = $DB->Query($sql);
    
    $sql = "ALTER TABLE `Group` ADD PRIMARY KEY (`id`);";
    
    $dbRes = $DB->Query($sql);  
    
    $sql = "ALTER TABLE `Group` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
    
    $dbRes = $DB->Query($sql); 

    $sql = "

INSERT INTO `Group` (`id`, `title`) VALUES
(1, 'Группа 1'),
(2, 'Группа 2'),
(3, 'Группа 3');    
    
    ";
 
    $dbRes = $DB->Query($sql);
    
    return true;

}

 function UnInstallDB()
 {
    global $DB;     
    $sql = "DROP TABLE IF EXISTS `Group`;";
    $dbRes = $DB->Query($sql);
    $sql = "DROP TABLE IF EXISTS `Person`;";
    $dbRes = $DB->Query($sql);
    return true;    
}

function DoUninstall()
{
global $DOCUMENT_ROOT, $APPLICATION;
$this->UnInstallDB();
$this->UnInstallFiles();
UnRegisterModule("person");
$APPLICATION->IncludeAdminFile("Деинсталляция модуля Person", $DOCUMENT_ROOT."/bitrix/modules/person/install/unstep.php");
}
}
?>