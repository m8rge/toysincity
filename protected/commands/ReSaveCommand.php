<?php

class ReSaveCommand extends CConsoleCommand
{
	public function actionIndex()
	{
		$off = 0;
		while ($items = Item::model()->findAll(array('limit'=>300, 'offset'=>$off))) {
			foreach ($items as $item) {
				$item->save();
			}
			$off+=300;
		}
	}
}
