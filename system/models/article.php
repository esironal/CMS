<?
// article model

function model_article_getCategories()
{
	$result = db_query("SELECT `id`, `name` FROM `article_categories`");
	$num_rows = mysql_num_rows($result);
	if($num_rows){
		$categories = array();
		while($row = mysql_fetch_array($result))
		{
			array_push($categories, $row);
		}
		return $categories;
	}else
		return false;
}

function model_article_submit($title,$userId, $categoryId, $content, $articleId = false)
{
	if(!$articleId){
		$articleResult = db_query("INSERT INTO  `cmsdb`.`article` (`id` ,`revision_id` ,`published` ,`date`)VALUES (NULL ,  '0',  '0', CURRENT_TIMESTAMP);");
		$articleId = mysql_insert_id();
	}

	if($articleId)
	{
		$result = db_query("INSERT INTO `cmsdb`.`article_revisions` (`id`, `article_id`, `user_id`, `category_id`, `title`, `content`, `date`) VALUES (NULL, '$articleId', '$userId', '$categoryId', '".addslashes($title)."', '".addslashes($content)."', CURRENT_TIMESTAMP);");
		$revisionId = mysql_insert_id();
		if($result)
		{
			return $revisionId;
		}
	}
	//if database entry fails return false
	return false;
}

function model_article_addTag($name, $articleId)
{
	// Check if tag exists in the database.
	$tagResult = db_query("SELECT * FROM `tags` WHERE `name`= '$name'");
	$num_rows = mysql_num_rows($tagResult);
	if($num_rows){
		// If it does, check if article is tagged with this tag.
		$tag = mysql_fetch_array($tagResult);
		$result = db_query("SELECT * FROM `article_tags` WHERE `article_id`='$articleId' && `tag_id`='".$tag['id']."'");
		$num_rows = mysql_num_rows($result);
		if($num_rows)
		{
			// do nothing if article already tagged with this tag
			return true;
		}else{
			// else tag with this tag
			$result = db_query("INSERT INTO `cmsdb`.`article_tags` (`article_id`, `tag_id`) VALUES ('$articleId', '".$tag['id']."');");
			if($result)
			{
				return true;
			}else{
				return false;
			}
		}
	}else{
		// If tag does not exist, add tag to db and then tag article
		$tagResult = db_query("INSERT INTO `cmsdb`.`tags` (`id`, `name`) VALUES (NULL, '$name');");
		$tagId = mysql_insert_id();
		if($tagResult)
		{
			$result = db_query("INSERT INTO `cmsdb`.`article_tags` (`article_id`, `tag_id`) VALUES ('$articleId', '$tagId');");
			echo $result;
			if($result)
			{
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}

function model_article_getDetails($articleId)
{
	$revisionResult = db_query("SELECT MAX(id) as id FROM `article_revisions` WHERE `article_id`='$articleId'");
	$revision = mysql_fetch_array($revisionResult);
	$revisionId = $revision['id'];
	echo $revisionId;
	$result = db_query("SELECT * FROM `article_revisions` WHERE `article_id`='$articleId'  && `id`='$revisionId'");
	echo "SELECT * FROM `article_revisions` WHERE `article_id`='$articleId'  && `id`='$revisionId'";
}
?>
