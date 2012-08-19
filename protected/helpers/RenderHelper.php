<?php

class RenderHelper
{
	static function processItems($items) {
		/** @var $item Item */
		foreach($items as $id => $item) {
			$items[$id] = $item->getAttributes();
			$items[$id]['photoUrl'] = $item->getPreviewPhotoUrl();
			$items[$id]['url'] = $item->getUrl();
		}

		return $items;
	}

	static function processIdItems($items) {
		/** @var $item Item */
		foreach($items as $item) {
			$items[$item->id] = $item->getAttributes();
			$items[$item->id]['photoUrl'] = $item->getPreviewPhotoUrl();
			$items[$item->id]['url'] = $item->getUrl();
		}

		return $items;
	}

	static function processItem(Item $item) {
		/** @var $fs FileSystem */
		$fs = Yii::app()->fs;

		$item = $item->getAttributes();
		foreach($item['photo'] as $id => $uid) {
			$item['photo'][$id] = $fs->getFileUrl($uid);
		}

		return $item;
	}
}
