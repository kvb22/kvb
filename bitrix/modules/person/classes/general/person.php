<?
IncludeModuleLangFile(__FILE__);

class CPerson
{
	var $LAST_ERROR="";

	//Get list
	public static function GetList($aSort=array(), $aFilter=array())
	{
		global $DB;

		$arFilter = array();

		$arOrder = array();
		foreach($aSort as $key=>$val)
		{
			$ord = (strtoupper($val) <> "ASC"? "DESC": "ASC");
			//$key = strtoupper($key);

			switch($key)
			{
				case "id":
				case "group_id":
				case "first_name":
				case "last_name":
				case "gender":
					$arOrder[] = "R.".$key." ".$ord;
					break;
			}
		}

		if(count($arOrder) == 0)
			$arOrder[] = "R.id DESC";
		$sOrder = "\nORDER BY ".implode(", ",$arOrder);

		if(count($arFilter) == 0)
			$sFilter = "";
		else
			$sFilter = "\nWHERE ".implode("\nAND ", $arFilter);

		$strSql = "
			SELECT
				R.id
				,R.group_id
				,R.first_name
				,R.last_name
				,R.gender
                ,`Group`.title as group_title
			FROM
				Person R
            LEFT JOIN `Group` ON `Group`.id = R.group_id
			".$sFilter.$sOrder;

		return $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
	}

	//Get by ID
	function GetByID($ID)
	{
		global $DB;
		$ID = intval($ID);

		$strSql = "
			SELECT
				R.*
			FROM Person R
			WHERE R.ID = ".$ID."
		";

		return $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
	}

	// delete
	function Delete($ID)
	{
		global $DB;
		$ID = intval($ID);

		$DB->StartTransaction();

        $res = $DB->Query("DELETE FROM Person WHERE ID=".$ID, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		if($res)
			$DB->Commit();
		else
			$DB->Rollback();

		return $res;
	}


	//check fields before writing
	function CheckFields($arFields)
	{
		global $DB;
		$this->LAST_ERROR = "";
		$aMsg = array();

		if(!empty($aMsg))
		{
			$e = new CAdminException($aMsg);
			$GLOBALS["APPLICATION"]->ThrowException($e);
			$this->LAST_ERROR = $e->GetString();
			return false;
		}
		return true;
	}

	//add
	function Add($arFields)
	{
		global $DB;

		if(!$this->CheckFields($arFields))
			return false;

		$ID = $DB->Add("Person", $arFields);

		return $ID;
	}

	//update
	function Update($ID, $arFields)
	{
		global $DB;
		$ID = intval($ID);

		if(!$this->CheckFields($arFields))
			return false;

		$strUpdate = $DB->PrepareUpdate("Person", $arFields);
		if($strUpdate!="")
		{
			$strSql = "UPDATE Person SET ".$strUpdate." WHERE ID=".$ID;
			$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		}
		return true;
	}

    function GroupSelectBox($selected = 0)
    {
        global $DB;
        $strSql = "SELECT * FROM `Group`";
        $res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
        $strRet = '<select name="group_id">';
        while($res_arr = $res->Fetch()){
            $strRet .= '<option value="'.$res_arr['id'].'"'. ($res_arr['id'] == $selected ? ' selected' : '') .'>'.$res_arr['title'].'</option>';
        }
        $strRet .= "</select>";
        return $strRet;
    }

}
?>