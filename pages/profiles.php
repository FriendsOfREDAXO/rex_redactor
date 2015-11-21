<?php
	$func = rex_request('func', 'string');
	
	if ($func == '') {
		$list = rex_list::factory("SELECT `id`, `name`, `description`, `language` FROM `".rex::getTablePrefix()."redactor_profiles` ORDER BY `name` ASC");
		$list->addTableAttribute('class', 'table-striped');
		
		// icon column
		$thIcon = '<a href="'.$list->getUrl(['func' => 'add']).'" title="'.$this->i18n('column_hashtag').' '.rex_i18n::msg('add').'"><i class="rex-icon rex-icon-add-action"></i></a>';
		$tdIcon = '<i class="rex-icon fa-file-text-o"></i>';
		$list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
		$list->setColumnParams($thIcon, ['func' => 'edit', 'id' => '###id###']);
		
		$list->setColumnLabel('name', $this->i18n('profiles_column_name'));
		$list->setColumnLabel('description', $this->i18n('profiles_column_description'));
		$list->setColumnLabel('language', $this->i18n('profiles_column_language'));
		
		// functions column spans 2 data-columns
		$funcs = $this->i18n('profiles_column_functions');
		
		$list->addColumn($funcs, '<i class="rex-icon rex-icon-edit"></i> '.rex_i18n::msg('edit'), -1, ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
		$list->setColumnParams($funcs, ['id' => '###id###', 'func' => 'edit']);
		
		$delete = 'deleteCol';
		$list->addColumn($delete, '<i class="rex-icon rex-icon-delete"></i> '.rex_i18n::msg('delete'), -1, ['', '<td class="rex-table-action">###VALUE###</td>']);
		$list->setColumnParams($delete, ['id' => '###id###', 'func' => 'delete']);
		$list->addLinkAttribute($delete, 'data-confirm', rex_i18n::msg('delete').' ?');
		
		$list->removeColumn('id');
		
		$content = $list->get();
		
		$fragment = new rex_fragment();
		$fragment->setVar('content', $content, false);
		$content = $fragment->parse('core/page/section.php');
		
		echo $content;
	} else if ($func == 'add' || $func == 'edit') {
		$id = rex_request('id', 'int');
		
		if ($func == 'edit') {
			$formLabel = $this->i18n('profiles_formcaption_edit');
		} elseif ($func == 'add') {
			$formLabel = $this->i18n('profiles_formcaption_add');
		}
		
		$form = rex_form::factory(rex::getTablePrefix().'redactor_profiles', '', 'id='.$id);
		
		//Start - add name-field
			$field = $form->addTextField('name');
			$field->setLabel($this->i18n('profiles_label_name'));
		//End - add name-field
		
		//Start - add description-field
			$field = $form->addTextField('description');
			$field->setLabel($this->i18n('profiles_label_description'));
		//End - add description-field
		
		//Start - add language-field
			$field = $form->addSelectField('language');
			$field->setLabel($this->i18n('profiles_label_language'));
			
			$select = $field->getSelect();
			$select->setSize(1);
			$select->addOption('---', 0);
			
			//Start - get all languages from the assets-folder
				$languages = glob(rex_path::addonAssets('rex_redactor', 'langs').'/*.js');
				foreach ($languages as $language) {
					$language = substr($language, strlen(rex_path::addonAssets('rex_redactor', 'langs')) +1, -3);
					$select->addOption($language, $language);
				}
			//End - get all languages from the assets-folder
		//End - add language-field
		
		//Start - add redactor_buttons-field
			$field = $form->addTextAreaField('redactor_buttons');
			$field->setLabel($this->i18n('profiles_label_redactorbuttons'));
			
			$field = $form->addRawField('
				<dl class="rex-form-group form-group">
					<dt>
						&nbsp;
					</dt>
					<dd>
						<p><a href="javascript:void(0);" onclick="$(\'#rex-redactor-help\').toggle(\'fast\');">Zeige/verberge Hilfe</a></p>
						<div id="rex-redactor-help" style="display:none">'.
							'<b>alignment</b><br>'.
							$this->i18n('profiles_buttons_alignment').'<br>'.
							'<br>'.
							'<b>bold</b><br>'.
							$this->i18n('profiles_buttons_bold').'<br>'.
							'<br>'.
							'<b>clips[Snippetname1=Snippettext1|Snippetname2=Snippettext2]</b><br>'.
							$this->i18n('profiles_buttons_clips').'<br>'.
							'<br>'.
							'<b>deleted</b><br>'.
							$this->i18n('profiles_buttons_deleted').'<br>'.
							'<br>'.
							'<b>fontcolor[#ffffff|#000000]</b><br>'.
							$this->i18n('profiles_buttons_fontcolor').'<br>'.
							'<br>'.
							'<b>fontfamily[arial|times]</b><br>'.
							$this->i18n('profiles_buttons_fontfamily').'<br>'.
							'<br>'.
							'<b>fontsize[10|12|14]</b><br>'.
							$this->i18n('profiles_buttons_fontsize').'<br>'.
							'<br>'.
							'<b>formatting[h1|p]</b><br>'.
							$this->i18n('profiles_buttons_formatting').'<br>'.
							'<br>'.
							'<b>fullscreen</b><br>'.
							$this->i18n('profiles_buttons_fullscreen').'<br>'.
							'<br>'.
							'<b>horizontalrule</b><br>'.
							$this->i18n('profiles_buttons_horizontalrule').'<br>'.
							'<br>'.
							'<b>html</b><br>'.
							$this->i18n('profiles_buttons_html').'<br>'.
							'<br>'.
							'<b>italic</b><br>'.
							$this->i18n('profiles_buttons_italic').'<br>'.
							'<br>'.
							'<b>indent</b><br>'.
							$this->i18n('profiles_buttons_indent').'<br>'.
							'<br>'.
							'<b>orderedlist</b><br>'.
							$this->i18n('profiles_buttons_orderedlist').'<br>'.
							'<br>'.
							'<b>outdent</b><br>'.
							$this->i18n('profiles_buttons_outdent').'<br>'.
							'<br>'.
//							'<b>redo</b><br>'.
//							$this->i18n('profiles_buttons_redo').'<br>'.
//							'<br>'.
							'<b>rex_linkmap</b><br>'.
							$this->i18n('profiles_buttons_rex_linkmap').'<br>'.
							'<br>'.
							'<b>rex_mediapool_image</b><br>'.
							$this->i18n('profiles_buttons_rex_mediapool_image').'<br>'.
							'<br>'.
							'<b>rex_mediapool_link</b><br>'.
							$this->i18n('profiles_buttons_rex_mediapool_link').'<br>'.
							'<br>'.
							'<b>table</b><br>'.
							$this->i18n('profiles_buttons_table').'<br>'.
							'<br>'.
							'<b>textdirection</b><br>'.
							$this->i18n('profiles_buttons_textdirection').'<br>'.
							'<br>'.
							'<b>textexpander[Lorem=Lorem ipsum|Duis=Duis aute]</b><br>'.
							$this->i18n('profiles_buttons_textexpander').'<br>'.
							'<br>'.
							'<b>underline</b><br>'.
							$this->i18n('profiles_buttons_underline').'<br>'.
							'<br>'.
//							'<b>undo</b><br>'.
//							$this->i18n('profiles_buttons_undo').'<br>'.
//							'<br>'.
							'<b>unorderedlist</b><br>'.
							$this->i18n('profiles_buttons_unorderedlist').'<br>'.
							'<br>'.
							'<b>video</b><br>'.
							$this->i18n('profiles_buttons_video').'<br>'.
							'
						</div>
					</dd>
				</dl>
			');
		//End - add redactor_buttons-field
		
		if ($func == 'edit') {
			$form->addParam('id', $id);
		}
		
		$content = $form->get();
		
		$fragment = new rex_fragment();
		$fragment->setVar('class', 'edit', false);
		$fragment->setVar('title', $formLabel, false);
		$fragment->setVar('body', $content, false);
		$content = $fragment->parse('core/page/section.php');
		
		echo $content;
	}
?>