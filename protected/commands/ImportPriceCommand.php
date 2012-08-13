<?php

class ImportPriceCommand extends CConsoleCommand
{
	public function actionIndex($fileName)
	{
		$f = fopen($fileName, 'r');
		while ($str = fgetcsv($f)) {
			if (!empty($str[2])) {
				$item = Item::model()->findByAttributes(array('article'=>$str[2]));
				if (empty($item)) {
					$vendor = Vendor::model()->findByAttributes(array('name'=>$str[1]));
					if (empty($vendor)) {
						$vendor = new Vendor();
						$vendor->name = $str[1];
						if (!$vendor->save())
							throw new CException('Can\'t save Vendor: '.print_r($vendor->errors, true));
					}

					$item = new Item();
					$item->article = $str[2];
					$item->vendorId = $vendor->id;
					$item->name = $str[3];
					$item->photo = array();
				}
				$item->scenario = 'import';
				$item->price = str_replace(',', '', $str[11]);
				if (!$item->save())
					throw new CException('Can\'t save Item: '.print_r($item->errors, true));
			}
		}
		fclose($f);
	}
}
