<?php

interface StringHtml {
	public function getString();
	public function getStringToHtml();
	public function concat($str);
	public function substring($start, $length);
	public function replace($seacrh, $replace);
}

class StringFilter implements StringHtml {
	private $string;
	private $regular;

	public function __construct($string, $regular) {
		$this->string = $string;
		$this->regular = $regular;
	}

	/**
	 * Функция добавления в конец строки
	 * @param string
	 * @return string
	 */
	public function concat($str = '') {
		$this->string = $this->string . $str;
		return $this->string;
	}

	/**
	 * Функция возвращает строку с html-тегами
	 * @return string
	 */
	public function getStringToHtml() {
		return $this->string;
	}

	/**
	 * Функция удаления html-тегов из строки
	 * @return string
	 */
	public function getString() {
		return preg_replace($this->regular, "", $this->string);
	}

	/**
	 * Функция возращения подстроки без html-тегов
	 * @param int, int
	 * @return string
	 */
	public function substring($start = 0, $length = 0) {
		return ($length == 0) ? substr($this->getString(), $start) : substr($this->getString(), $start, $length);
	}

	/**
	 * Функция замены подстроки
	 * @param string Строка поиска
	 * @param string Строка замены
	 * @return string
	 */
	public function replace($search, $replace) {
		$str_array = preg_split($this->regular, $this->string, -1, PREG_SPLIT_OFFSET_CAPTURE);
		$strlen = strlen($this->string);
		$count = count($str_array);
		for ($i=0; $i<$count; $i++) {
			//Если элемент пустой (наличие тега)
			if ($str_array[$i][0] == "") {continue;}

			//Берем предыдущий элемент
			$prev_arr = (isset($str_array[$i-1])) ? $str_array[$i-1] : false;
			$prev_pos = 0;
			if ($prev_arr) {
				$prev_pos = $prev_arr[1];
			}

			//Берем текущий элемени
			$cur_arr = $str_array[$i];
			$string = $cur_arr[0];

			//Берем следующий элемент
			$next_arr = (isset($str_array[$i+1])) ? $str_array[$i+1] : false;
			$next_pos = 0;
			if ($next_arr) {
				$next_pos = $next_arr[1] - $prev_pos;
			}

			//Если текущий элемет не удовлетворяет поисковой строке
			if ($string != $search) {
				$tmp_string = $string;
				//Склеиваем строку с последующими элементами
				for ($j=$i; $j<$count; $j++) {
					if (isset($str_array[$j+1])) {
						$tmp_string .= $str_array[$j+1][0];
						//Если строка до $j-элемента совпала, то запоминаем конечную позицию
						if ($tmp_string == $search) {
							$string = $tmp_string;
							$next_pos = $str_array[$j+1][1] - $prev_pos;
						}
					}
				}
			}

			if ($string == $search) {
				$this->string = substr_replace($this->string, $replace, $prev_pos, $next_pos);
				return $this->replace($search, $replace);
			}

		}

		return $this->string;
	}

}
