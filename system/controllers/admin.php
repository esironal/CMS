<?

function admin_construct(){
	load_model('admin');
	load_helper('form');
	load_helper('admin');
	
	//check if user is admin
	admin_check();
}

function admin_index(){
	$data['name'] = get_session('user_name');
	
	$data['dash']['Users']['Total'] = model_exec('admin', 'get_user_count');
	$data['dash']['Users']['Readers'] = model_exec('admin', 'get_user_count', array(USER));
	$data['dash']['Users']['Moderators'] = model_exec('admin', 'get_user_count', array(MOD));
	$data['dash']['Users']['Administrators'] = model_exec('admin', 'get_user_count', array(ADMIN));
	
	$data['dash']['Articles']['Total'] = model_exec('admin', 'get_article_count');
	$data['dash']['Articles']['Published'] = model_exec('admin', 'get_article_count', array(0));
	$data['dash']['Articles']['UnPublished'] = model_exec('admin', 'get_article_count', array(1));
	$data['dash']['Articles']['Revisions'] = model_exec('admin', 'get_rev_count');
	$data['dash']['Articles']['Categories'] = model_exec('admin', 'get_category_count');
	$data['dash']['Articles']['Tags'] = model_exec('admin', 'get_tag_count');
	
	load_view('main', $data, false, true);
}

function admin_published($articleId = false){
	$data['name'] = get_session('user_name');
	
	if($articleId !== false)
	{
		$data['articles'] = model_exec('admin', 'unpublish_article', array($articleId));
		$data['notice'] = "Article Unpublished";
	}
	
	$data['articles'] = model_exec('admin', 'get_published_articles');
		
	foreach($data['articles'] as $key => $art){
		$data['articles'][$key]['revision'] = model_exec('admin', 'get_last_revision', array($art['id']));
		$data['articles'][$key]['category'] = model_exec('admin', 'get_category', array($data['articles'][$key]['revision']['category_id']));
		$data['articles'][$key]['user'] = model_exec('user', 'get_details', array($data['articles'][$key]['revision']['user_id']));
	}
	
	load_view('articles_published', $data, false, true);
}

function admin_unpublished(){
	load_model('user');
	$data['name'] = get_session('user_name');
	$data['articles'] = model_exec('admin', 'get_unpublished_articles');
	foreach($data['articles'] as $key => $art){
		$data['articles'][$key]['revision'] = model_exec('admin', 'get_last_revision', array($art['id']));
		$data['articles'][$key]['category'] = model_exec('admin', 'get_category', array($data['articles'][$key]['revision']['category_id']));
		$data['articles'][$key]['user'] = model_exec('user', 'get_details', array($data['articles'][$key]['revision']['user_id']));
	}
	load_view('articles_unpublished', $data, false, true);
}

function admin_users(){
	$data['name'] = get_session('user_name');
	
	if(form_submitted()){
		$newuser['id'] = $_POST['id'];
		$newuser['user'] = array();
		foreach($_POST as $key => $input){
			//skip unused values
			if($key == "submit_check" || $key == "submit" || $key == "id") continue;
			$newuser['user'][$key] = $input;
		}
		model_exec('admin', 'update_user', $newuser);
		$data['save_id'] = $_POST['id'];
	}
	
	$data['users'] = model_exec('admin', 'get_all_users');
	load_view('users', $data, false, true);
}

function admin_settings(){	
	$data['name'] = get_session('user_name');
		
	if(form_submitted()){
		$newsettings['settings'] = array();
		foreach($_POST as $key => $input){
			//skip unused values
			if($key == "submit_check" || $key == "submit") continue;
			$newsettings['settings'][$key] = $input;
		}
		model_exec('admin', 'save_all_settings', $newsettings);
		
		$data['notice'] = "Settings Saved";
	}
	
	$data['settings'] = model_exec('admin', 'get_all_settings');
	load_view('settings', $data, false, true);
}

function admin_categories($category = false){
	$data['name'] = get_session('user_name');
	
	$data['categories'] = model_exec('admin', 'get_all_categories');
	load_view('categories', $data, false, true);
}

?>
