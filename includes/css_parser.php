<?php

/**
 * Template holder and replacer class
 *
 * Tempalte is html or tpl file, have values to be replaced. In this
 * example, we are replaced class="test" with style="values of test css class"
 *
 * @name class template holder
 * @package css_parser
 *
 */


class htmlHolder {
	/**
	 * Template file holder
	 *
	 * @var mixed
	 */
	var $pagex;
	var $cssparser;

	/**
	 * Constructor - put file into array
	 *
	 * @param string $filename name of file
	 * @return array file
	 */
	function htmlHolder($filename) {
		$this->pagex = $filename;
		if (file_exists($filename)) {
			$pax = file($filename);
			if ($pax != '') $this->pagex = join('', $pax);
		}
		$this->cssparser = new cssParser();
	}


	/**
	 * Replace css class
	 *
	 * @param array $tags - css keys and blocks
	 */
	function replaceCSS ($tags = array()) {
		foreach ($tags as $tag => $data) {
    	       //$data = (file_exists($data)) ? $this->parse($data) : $data;
    	       $this->pagex = str_replace($tag, $data, $this->pagex );
			   // regex to replace class[space]=, class[space]=[space]. and class =[space]
			   // with class= nospaces
    	       $this->pagex = preg_replace("/class\s+=\s+|class\s+=|class=\s+/", "class=", $this->pagex);

    	       // regex to replace quote marks and spaces properly
    	       $this->pagex = preg_replace("/class=\"\s+(.*?)\\s+\"/i", "class=\"\\1\"", $this->pagex);
    	       $this->pagex = preg_replace("/class=\"\\s+(.*?)\"/i", "class=\"\\1\"", $this->pagex);
    	       $this->pagex = preg_replace("/class=\"(.*?)\\s+\"/i", "class=\"\\1\"", $this->pagex);
    	       $this->pagex = preg_replace("/class=\"(.*?)\"/i", "class=\"\\1\"", $this->pagex);

    	       // regex to replace semi quote marks and spaces properly
    	       $this->pagex = preg_replace("/class=\'\s+(.*?)\s+\'/i", "class=\"\\1\"", $this->pagex);
    	       $this->pagex = preg_replace("/class=\'\s+(.*?)\'/i", "class=\"\\1\"", $this->pagex);
    	       $this->pagex = preg_replace("/class=\'(.*?)\s+\'/i", "class=\"\\1\"", $this->pagex);
    	       $this->pagex = preg_replace("/class=\'(.*?)\'/i", "class=\"\\1\"", $this->pagex);

			   // regex to replace without quote marks and spaces properly
    	       $this->pagex = preg_replace("/class=\s+(.*?)\s+/i", "class=\"\\1\"", $this->pagex);

//deprecated for the diferent quotation marks (", ' and without that)
    	       //$this->page = str_replace("class", "style", $this->page);
		}
	}

	/**
	 * print result with replaced css files
	 *
	 * or return $this->page; to return results
	 */
	function out() {
		return $this->pagex;
	}
}


/**
 * Css parser class
 *
 * Class can be worked with external css files, or css stylesheet
 * can be hardcoded in the php program used with parseStr function
 * See examples for more details
 *
 * @name class css parser
 * @package css_parser
 *
 */
class cssParser {
	/**
	 * Css file holder
	 *
	 * @var string
	 */
	var $css;

	/**
	 * Key holder- for css keys
	 *
	 * @deprecated associative array in $codestr_holderde
	 * @see $codestr_holder
	 * @var array
	 */
	var $key_holder = array();

	/**
	 * Holder for css values
	 *
	 * between { and } of css definition
	 *
	 * @var array
	 */
	var $codestr_holder = array();


	/**
	 * Parse Css string
	 *
	 * Ignore comments and do some properly finding of css definitions
	 *
	 * @param string $str
	 */
	function parseStr($str) {
    $this->_clear();
    // Remove comments

    $str = preg_replace("/\/\*(.*)?\*\//Usi", "", $str);
    // Parse this damn csscode

	$parts = explode("}",$str);

    if(count($parts) > 0) {
		foreach($parts as $part) {

        	@list($keystr,$codestr) = explode("{",$part);

        		$keys = explode(",",trim($keystr));

        			if(count($keys) > 0) {
          				foreach($keys as $key) {
            				if(strlen($key) > 0) {
              					$key = str_replace("\n", "", $key);
              					$key = str_replace("\\", "", $key);
              					$key = strtolower($key);

								if (strpos($key, ".") !== false) {
									$key_arr = explode(".", $key);

									$s_key_arr = "class=\"".end($key_arr)."\"";
									$s_key_arr_snd = "class='".end($key_arr)."'";
									$s_key_arr_thd = "class=".end($key_arr);

									$this->codestr_holder[$s_key_arr] = &$this->codestr_holder[$s_key_arr];
									$this->codestr_holder[$s_key_arr] .= "style=\"".$this->_filterLineBreaks($codestr)."\"";

									$this->codestr_holder[$s_key_arr_snd] = &$this->codestr_holder[$s_key_arr_snd];
									$this->codestr_holder[$s_key_arr_snd] .= "style=\"".$this->_filterLineBreaks($codestr)."\"";

									$this->codestr_holder[$s_key_arr_thd] =&$this->codestr_holder[$s_key_arr_thd];
									$this->codestr_holder[$s_key_arr_thd] .= "style=\"".$this->_filterLineBreaks($codestr)."\"";

								}elseif (strpos($key, "*") !== false) {
									$key_arr = preg_split('/\[|\]/', $key);

									$this->codestr_holder[end($key_arr)] = &$this->codestr_holder[end($key_arr)];
									$this->codestr_holder[end($key_arr)] .= "style=\"".$this->_filterLineBreaks($codestr)."\"";
								}elseif (strpos($key, ">") !== false) {

									$key_arr_snd = explode(">", $key);
									foreach ($key_arr_snd as $_key_arr_snd) {
										$key_arr = explode(" ", $_key_arr_snd);
									}

									if (strpos(end($key_arr), "[") !== false) {
									//empty
									}else {

									//$s_key_arr = "<".end($key_arr);
									//$this->codestr_holder[$s_key_arr] = &$this->codestr_holder[$s_key_arr];
									//$this->codestr_holder[$s_key_arr] .= substr_replace($s_key_arr, " style=\"".$this->_filterLineBreaks($codestr)."\"", strlen($s_key_arr), 0);
									}
								}else {
								$s_key = "<".$key;
								$this->codestr_holder[$s_key] = &$this->codestr_holder[$s_key];
								$this->codestr_holder[$s_key] .= substr_replace($s_key, " style=\"".$this->_filterLineBreaks($codestr)."\"", strlen($s_key), 0);
								}
            			}
          			}
        		}
      	}
    }
	//return (count($this->css) > 0);
	}

	/**
	 * Parse an external css file
	 *
	 * @param string $filename
	 * @return string contents of a file
	 * @see ParseStr
	 */
	function parseFile($filename) {
    	$this->_clear();
    	if(file_exists($filename)) {
      		return $this->parseStr(file_get_contents($filename));
   		}else {
	      	return false;
    	}
  	}

  	/**
  	 * Clear $this->css variable
  	 *
  	 */
	function _clear() {
		unset($this->css);
	}

	/**
	 * Filter css file valus
	 *
	 * to be properly replaced it
	 *
	 * @param string $html
	 * @return string clear strung without 2+spaces, tabs, new lines, etc
	 */
	function _filterLineBreaks($html) {

		$html = preg_replace('/[[:space:]]+/', ' ', $html);
    	$html = preg_replace('/(^[[:space:]])|([[:space:]]$)/', '', $html);
    	return $html;
	}

	/**
	 * Replace space with no space
	 *
	 * @param string $html
	 * @return string
	 */
	function _filterSpace($html) {
		$html = preg_replace('/[[:space:]]/', '', $html);
		return $html;
	}

	/**
	 * Get section of css by key
	 *
	 * Key is the css class name definition .test {some css}
	 * Block is between { and } of css file or string
	 *
	 * @param string $key
	 * @return string $block - between { } find by $key
	 */
	function getSection($key) {
    	$key = strtolower($key);

    	list($tag, $subtag) = explode(":",$key);
    	list($tag, $class) = explode(".",$tag);
    	list($tag, $id) = explode("#",$tag);


    	$block = '';

    	foreach($this->css as $_tag => $value) {

     		list($_tag, $_subtag) = explode(":",$_tag);
     		list($_tag, $_class) = explode(".",$_tag);
     		list($_tag, $_id) = explode("#",$_tag);

      		$tagmatch = (strcmp($tag, $_tag) == 0) | (strlen($_tag) == 0);
      		$subtagmatch = (strcmp($subtag, $_subtag) == 0) | (strlen($_subtag) == 0);
      		$classmatch = (strcmp($class, $_class) == 0) | (strlen($_class) == 0);
      		$idmatch = (strcmp($id, $_id) == 0);

      		if($tagmatch & $subtagmatch & $classmatch & $idmatch) {
        		$temp = $_tag;
        			if((strlen($temp) > 0) & (strlen($_class) > 0)) {
          				$temp .= ".".$_class;
        			}elseif(strlen($temp) == 0) {
          				$temp = ".".$_class;
        			}

        		if((strlen($temp) > 0) & (strlen($_subtag) > 0)) {
          			$temp .= ":".$_subtag;
        		}elseif(strlen($temp) == 0) {
          			$temp = ":".$_subtag;
        		}
				// block for replaced css class
				while (list($property, $value) = each($this->css[$temp])) {
					$block.= ($property.": ".$value."; ");
				}

      		}
    	}
    	return $block;
	}

}
?>