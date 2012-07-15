<?php

class RenderHelper
{
	static function processItems($items) {
		/** @var $fs FileSystem */
		$fs = Yii::app()->fs;

		/** @var $item Item */
		foreach($items as $id => $item) {
			$items[$id] = $item->getAttributes();
			$items[$id]['photo'] = json_decode($items[$id]['photo'], true);
			$items[$id]['photoUrl'] = reset($items[$id]['photo']);
			$items[$id]['photoUrl'] = $fs->getFileUrl($items[$id]['photoUrl']);
			$items[$id]['url'] = $item->getUrl();
		}

		return $items;
	}

	static function processItem(Item $item) {
		/** @var $fs FileSystem */
		$fs = Yii::app()->fs;

		$item = $item->getAttributes();
		$item['photo'] = json_decode($item['photo'], true);
		foreach($item['photo'] as $id => $uid) {
			$item['photo'][$id] = $fs->getFileUrl($uid);
		}

		return $item;
	}
}
