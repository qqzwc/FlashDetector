<?php

/*
 * iTXTech FlashDetector
 *
 * Copyright (C) 2018-2019 iTX Technologies
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

//ChipGenius Flash List Generator

require_once "env.php";

use iTXTech\FlashDetector\FlashDetector;
use iTXTech\FlashDetector\Property\Classification;
use iTXTech\SimpleFramework\Util\StringUtil;

$data = "";

foreach(FlashDetector::getFdb()->getIddb()->getFlashIds() as $id){
	if(count($id->getPartNumbers()) > 0){
		$pn = explode(" ", $id->getPartNumbers()[0])[1];
		$info = FlashDetector::detect($pn, true);
		if(StringUtil::endsWith($info->getCellLevel() ?? "", "LC")){
			if($id->getPageSize() == Classification::UNKNOWN_PROP){
				$data .= $id->getFlashId() . "," . $info->getCellLevel() . ",";
			}elseif($id->getPageSize() < 1){
				$data .= $id->getFlashId() . "," . $info->getCellLevel() . "-" . $id->getPageSize() * 1024 . ",";
			}else{
				$data .= $id->getFlashId() . "," . $info->getCellLevel() . "-" . $id->getPageSize() . "K,";
			}
			foreach($id->getPartNumbers() as $pn){
				$pn = explode(" ", $pn)[1];
				$info = FlashDetector::detect($pn);
				if($info->getClassification() != null and
					$info->getClassification()->getCe() != Classification::UNKNOWN_PROP){
					$data .= "[" . $info->getClassification()->getCe() . "CE]" . $pn . "#";
				}else{
					$data .= $pn . "#";
				}
			}
			$data = substr($data, 0, strlen($data) - 1) . "\r\n";
		}
	}
}

file_put_contents("CGFlashList.csv", $data);