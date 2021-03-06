<?php
	class rex_redactor {
		
		public static function insertProfile ($name, $description = '', $urltype, $redactorButtons = '', $redactorPlugins = '') {
			$sql = rex_sql::factory();
			$sql->setTable(rex::getTablePrefix().'redactor_profiles');
			$sql->setValue('name', $name);
			$sql->setValue('description', $description);
			$sql->setValue('urltype', $urltype);
			$sql->setValue('redactor_buttons', $redactorButtons);
			$sql->setValue('redactor_plugins', $redactorPlugins);
			
			try {
				$sql->insert();
				return $sql->getLastId();
			} catch (rex_sql_exception $e) {
				return $e->getMessage();
			}
		}
		
		public static function profileExists ($name) {
			$sql = rex_sql::factory();
			$profile = $sql->setQuery("SELECT `name` FROM `".rex::getTablePrefix()."redactor_profiles` WHERE `name` = ".$sql->escape($name)."")->getArray();
			unset($sql);
			
			if (!empty($profile)) {
				return true;
			} else {
				return false;
			}
		}
	}
?>