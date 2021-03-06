<?php
	class FormHandler {
		private $fields	= array();
		private $values	= array();
		private $errors	= array();

		private $method;
		private $action;
		private $submitTitle;

		private $validators = array();

		public function __construct ($method = 'post', $action = '', $submitTitle = false, $validators = array()) {
			$this->method		= $method;
			$this->action		= $action;
			$this->submitTitle	= $submitTitle;

			$commonValidators = array(
				'name'			=> '/^\S.*$/',											// name (atleast one character)
				'message'		=> '/^\S.*$/s',											// message (atleast one character)
				'dimensions'	=> '/^\d+x\d+$/',										// dimensions (DIGITxDIGIT)
				'price'			=> '/^\d+$/',											// price (atleast one digit)
				'url'			=> '/^http:\/\/?(www)?[^ ]+\.[^ ]{2,5}$/',				// url
				'email'			=> '/^.+?@.+?\..{2,4}$/',								// email
				'sql_table_name'=> '/^[a-z_]+$/'
			);

			$commonValidators['title'] = $commonValidators['author'] = $commonValidators['name'];
			$commonValidators['comment'] = $commonValidators['description'] = $commonValidators['content'] = $commonValidators['message'];
			$commonValidators['website'] = $commonValidators['url'];

			$this->validators = array_merge($commonValidators, $validators);
		}

		public function addValuesArray ($newValues) {
			$this->values = array_merge($this->values, $newValues);
		}

		public function addField ($field) {
			$this->fields[] = array(
				'title'		=> isset($field['title']) ? $field['title'] : ucwords($field['name']), 
				'name'		=> $field['name'], 
				'value'		=> isset($this->values[$field['name']]) ? $this->values[$field['name']] : (isset($field['value']) ? $field['value'] : false), 
				'type'		=> isset($field['type']) ? $field['type'] : 'text', 
				'required'	=> isset($field['required']) ? true : false,
				'options'	=> isset($field['options']) ? $field['options'] : false, 
				'checked'	=> isset($field['checked']) ? true : false, 
				'validation'=> isset($field['validation']) ? $field['validation'] : $field['name']
			);
		}

		public function validate ($checkSpam = false, $row = false) {
			$row = $row ? $row : $_POST;

			if ($checkSpam and ($karma = SpamChecker::getKarma($row)) < 1) {
				$this->errors['__spam']			= 'The data in the form appears to be spam. Please remove overflow of links and spammy words.';
				$this->errors['__spam_info']	= SpamChecker::getInfo();
				$this->errors['__spam_karma']	= $karma;
			}

			foreach ($this->fields as $field) {
				if ($field['required'] and (!isset($row[$field['name']]) or empty($row[$field['name']]))) {
					$this->errors[$field['name']] = 'Must not be empty';
				}
				elseif (($field['required'] or !empty($row[$field['name']])) and isset($this->validators[$field['validation']]) and !preg_match($this->validators[$field['validation']], $row[$field['name']])) {
					$this->errors[$field['name']] = 'Must be valid (' . $this->validators[$field['validation']] . ')';
				}
			}

			if (count($this->errors)) {
				return false;
			}

			return true;
		}

		public function asHTML ($submitTitle = false) {
			$html	= '';
			$fields	= $this->fields;

			if (isset($this->errors['__spam'])) {
				$html .= "<p><strong>The data in the form appears to be spam. Please try with less URLs and/or spammy words. Here's what the SpamChecker says:</strong></p>";
				$html .= "<ul>";

				foreach ($this->errors['__spam_info'] as $spamInfo) {
					$html .= "<li>$spamInfo</li>";
				}

				$html .= "</ul>";
			}

			$html .= "<form method=\"{$this->method}\" action=\"{$this->action}\">\n\t";

			# Add all fields
			foreach ($fields as $field) {
				if ($field['type'] != 'hidden') {
					$html .= "<p>\n\t\t<label>\n\t\t\t";

					if ($field['required']) {
						$html .= '<abbr title="Required Field">*</abbr> ';
					}

					if ($field['type'] != 'checkbox') {
						$html .= "{$field['title']}<br/>\n\t\t\t";
					}

					switch ($field['type']) {
						case 'textarea' : 
							$html .= "<textarea rows=\"10\" cols=\"60\" name=\"{$field['name']}\">{$field['value']}</textarea>";
							break;
						case 'checkbox' : 
							$html .= "<input type=\"checkbox\" name=\"{$field['name']}\"";
							$html .= (isset($field['checked']) and $field['checked']) ? ' checked="checked"' : '';
							$html .= "/> {$field['title']}";
							break;
						case 'select' : 
							$html .= "<select name=\"{$field['name']}\">";

							foreach ($field['options'] as $k => $v) {
								$html .= "\n\t\t\t\t<option value=\"$k\"";
								$html .= ($k == $field['value']) ? ' selected="selected"' : '';
								$html .= ">$v</option>";
							}
							$html .= "\n\t\t\t</select>";

							break;
						default : 
							$html .= "<input type=\"text\" name=\"{$field['name']}\" value=\"{$field['value']}\"/>";
							break;
					}

					if (isset($this->errors[$field['name']])) {
						$html .= "\n\t\t\t<br/>\n\t\t\t<strong>{$this->errors[$field['name']]}</strong>";
					}

					$html .= "\n\t\t</label>\n\t</p>\n\n\t";
				}
			}

			# Add submit-button and hidden fields
			$html .= "<p>";

			foreach ($this->fields as $field) {
				if ($field['type'] == 'hidden') {
					$html .= "\n\t\t<input type=\"hidden\" name=\"{$field['name']}\" value=\"{$field['value']}\"/>";
				}
			}

			$html .= "\n\t\t<input type=\"submit\"";
			$html .= $this->submitTitle ? " value=\"{$this->submitTitle}\"" : '';
			$html .= '/>';
			$html .= "\n\t</p>\n\n</form>";

			return $html;
		}

		public function getErrors () {
			return $this->errors;
		}
	}
?>
