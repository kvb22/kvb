<?
IncludeModuleLangFile(__FILE__);

global $DB;
$db_type = strtolower($DB->type);
CModule::AddAutoloadClasses(
	"person",
	array(
		"CPerson" => "classes/general/person.php",
	)
);
?>