<?php

/*
 * iTXTech FlashDetector
 *
 * Copyright (C) 2018-2020 iTX Technologies
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

namespace iTXTech\FlashDetector;

use iTXTech\FlashDetector\Processor\DefaultProcessor;
use iTXTech\SimpleFramework\Module\Module;
use iTXTech\SimpleFramework\Module\ModuleInfo;

class Loader extends Module{
	private static $instance;

	public function load(){
		self::$instance = $this;
		FlashDetector::initialize();
		$info = new \ReflectionClass(ModuleInfo::class);
		$prop = $info->getProperty("version");
		$prop->setAccessible(true);
		$prop->setValue($this->getInfo(), FlashDetector::getVersion() . "." . $this->getInfo()->getVersion());

		FlashDetector::registerProcessor(new DefaultProcessor());
	}

	public function unload(){
	}

	public static function getInstance() : ?Loader{
		return self::$instance;
	}
}
