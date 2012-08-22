<?php

class QaController extends Controller
{
	public function actionIndex()
	{
		$newPost = new Post();
		if(isset($_POST['Post']) && !isset($_POST['Post']['email']))
		{
			$newPost->attributes=$_POST['Post'];
			$newPost->save();
		}

		$posts = Post::model()->rootEntries();
		$criteria = $posts->dbCriteria;
		$pager = new CPagination($posts->count());
		$posts->dbCriteria = $criteria;
		$pager->applyLimit($posts->dbCriteria);

		$this->render('index', array(
			'posts' => $posts->findAll(),
			'pager' => $pager,
			'newPost' => $newPost,
		));
	}
}
