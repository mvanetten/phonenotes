<?php


function actionlink($name,$controller,$action,$parameter = null)
{
	if (!is_null($parameter))
	{
		return sprintf ("<a href='%s?page=%s&%s&%s'>%s</a>", $adminurl,$controller,$action,$parameter,$name);	
	}
	return sprintf ("<a href='%s?page=%s&%s'>%s</a>", $adminurl,$controller,$action,$name);
}
	

?>