<?
// article model

function model_article_getCategories()
{
	$result = db_query("SELECT `name` FROM `article_categories`");
	$num_rows = mysql_num_rows($result);
	if($num_rows){
		$categories = array();
		while($row = mysql_fetch_array($result))
		{
			array_push($categories, $row['name']);
		}
		return $categories;
	}else
		return false;
}
?>
