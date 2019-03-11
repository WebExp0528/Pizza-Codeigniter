<?php
session_start();

$database = new database($database_host, $database_user, $database_password, $database_name);
class loginUser extends mosDBTable
{
	var $id = null;
	var $userid = null;
	var $name = null;
	var $passwd = null;
	var $group = null;
	var $permission = null;
	var $action = null;
	
	function loginUser()
	{
		global $database_host, $database_user, $database_password, $database_name, $database_set_string;
		$database = new database($database_host, $database_user, $database_password, $database_name);
		
		$this->mosDBTable('oos_customer', 'id', $database);
	
	}
	
	function userLoginCheck($userid, $userpwd)
	{
		$query = "SELECT * FROM oos_customer WHERE user_id='" . $userid . "'";
		$this->_db->setQuery($query);
		$this->_db->query();
		$user_num = $this->_db->getAffectedRows();
		if ($user_num != 1)
		{
			return -1;
		}
		$this->_db->loadObject($user);
		
		//$passwd = md5($userpwd);
		

		if (strcmp($userpwd, $user->passwd) != 0)
		{
			return 0;
		}
		
		//		if ($user->action != 1)
		//		{
		//			return -2;
		//		}
		//
		//		if ($user->del != 0)
		//		{
		//			return -3;
		//		}
		$_SESSION["USER_LOGIN_ID"] = $user->user_id;
		$_SESSION["USER_ID"] = $user->id;
		$_SESSION["USER_NAME"] = $user->firstname;
		$_SESSION["USER_LOGIN_TIME"] = date("Y-m-d H:i:s");
		$_SESSION["user_passwd"] = $userpwd;
		$today = date("Y-m-d");
		$_SESSION["user_remote_port"] = $_SERVER["REMOTE_PORT"];
		$_SESSION["user_remote_ip"] = $_SERVER["REMOTE_ADDR"];
		return 1;
	}
	function userLogout()
	{
		$_SESSION["USER_LOGIN_ID"] = "";
		$_SESSION["USER_ID"] = "";
		$_SESSION["USER_NAME"] = "";
		$_SESSION["user_passwd"] = "";
		$_SESSION["user_permission"] = "";
		$_SESSION["user_group"] = "";
		$_SESSION["USER_LOGIN_TIME"] = "";
		$_SESSION["user_group_name"] = "";
		$_SESSION["user_lastlogin_time"] = "";
		$_SESSION["user_login_num"] = "";
		$_SESSION["user_todaylogin_num"] = "";
		$_SESSION["user_remote_port"] = "";
		$_SESSION["user_remote_ip"] = "";
		session_id();
		return 1;
	}

}

/////  


class group
{
	
	var $id = null;
	var $parent = null;
	var $name = null;
	var $permission = Array();
}

class loginSession
{
	var $user;
	var $cache;
}

define("_MOS_NOTRIM", 0x0001);
define("_MOS_ALLOWHTML", 0x0002);
define("_MOS_ALLOWRAW", 0x0004);

function mosGetParam(&$arr, $name, $def = null, $mask = 0)
{
	static $noHtmlFilter = null;
	static $safeHtmlFilter = null;
	
	$return = null;
	if (isset($arr[$name]))
	{
		$return = $arr[$name];
		return $return;
	}
	else
	{
		return $def;
	}
}

/**
 * Copy the named array content into the object as properties
 * only existing properties of object are filled. when undefined in hash, properties wont be deleted
 */
function mosBindArrayToObject($array, &$obj, $ignore = '', $prefix = NULL, $checkSlashes = true)
{
	if (!is_array($array) || !is_object($obj))
	{
		return (false);
	}
	
	foreach (get_object_vars($obj) as $k => $v)
	{
		if (substr($k, 0, 1) != '_')
		{ // internal attributes of an object are ignored
			if (strpos($ignore, $k) === false)
			{
				if ($prefix)
				{
					$ak = $prefix . $k;
				}
				else
				{
					$ak = $k;
				}
				if (isset($array[$ak]))
				{
					$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? mosStripslashes($array[$ak]) : $array[$ak];
				}
			}
		}
	}
	
	return true;
}

/**
 * Strip slashes from strings or arrays of strings
 * @param mixed The input string or array
 * @return mixed String or array stripped of slashes
 */
function mosStripslashes(&$value)
{
	$ret = '';
	if (is_string($value))
	{
		$ret = stripslashes($value);
	}
	else
	{
		if (is_array($value))
		{
			$ret = array();
			foreach ($value as $key => $val)
			{
				$ret[$key] = mosStripslashes($val);
			}
		}
		else
		{
			$ret = $value;
		}
	}
	return $ret;
}

function mosTreeRecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1)
{
	if (@$children[$id] && $level <= $maxlevel)
	{
		foreach ($children[$id] as $v)
		{
			$id = $v->id;
			
			if ($type)
			{
				$pre = '<sup>L</sup>&nbsp;';
				$spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			else
			{
				$pre = '- ';
				$spacer = '&nbsp;&nbsp;';
			}
			
			if ($v->parent == 0)
			{
				$txt = $v->name;
			}
			else
			{
				$txt = $pre . $v->name;
			}
			$pt = $v->parent;
			$list[$id] = $v;
			$list[$id]->treename = "$indent$txt";
			$list[$id]->children = count(@$children[$id]);
			$list = mosTreeRecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
		}
	}
	return $list;
}

/**
 * Utility class for all HTML drawing classes
 * @package Joomla
 */
class mosHTML
{
	function makeOption($value, $text = '', $value_name = 'value', $text_name = 'text')
	{
		$obj = new stdClass();
		$obj->$value_name = $value;
		$obj->$text_name = trim($text) ? $text : $value;
		return $obj;
	}
	
	function writableCell($folder)
	{
		
		echo '<tr>';
		echo '<td class="item">' . $folder . '/</td>';
		echo '<td align="left">';
		echo is_writable("../$folder") ? '<b><font color="green">Writeable</font></b>' : '<b><font color="red">Unwriteable</font></b>' . '</td>';
		echo '</tr>';
	}
	
	/**
	 * Generates an HTML select list
	 * @param array An array of objects
	 */
	function selectList(&$arr, $tag_name, $tag_attribs, $key, $text, $selected = NULL)
	{
		reset($arr);
		$html = "\n<select name=\"$tag_name\" id=\"$tag_name\" $tag_attribs>";
		for ($i = 0, $n = count($arr); $i < $n; $i++)
		{
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = (isset($arr[$i]->id) ? @$arr[$i]->id : null);
			
			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array($selected))
			{
				foreach ($selected as $obj)
				{
					$k2 = $obj->$key;
					if ($k == $k2)
					{
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			}
			else
			{
				$extra .= ($k == $selected ? " selected=\"selected\"" : '');
			}
			$html .= "\n\t<option value=\"" . $k . "\"$extra>" . $t . "</option>";
		}
		$html .= "\n</select>\n";
		return $html;
	}
	
	/**
	 * Writes a select list of integers
	 */
	function integerSelectList($start, $end, $inc, $tag_name, $tag_attribs, $selected, $format = "")
	{
		$start = intval($start);
		$end = intval($end);
		$inc = intval($inc);
		$arr = array();
		
		for ($i = $start; $i <= $end; $i += $inc)
		{
			$fi = $format ? sprintf("$format", $i) : "$i";
			$arr[] = mosHTML::makeOption($fi, $fi);
		}
		
		return mosHTML::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
	}
	
	/**
	 * Writes a select list of month names based on Language settings
	 */
	function monthSelectList($tag_name, $tag_attribs, $selected)
	{
		$arr = array(mosHTML::makeOption('01', _JAN), mosHTML::makeOption('02', _FEB), mosHTML::makeOption('03', _MAR), mosHTML::makeOption('04', _APR), mosHTML::makeOption('05', _MAY), mosHTML::makeOption('06', _JUN), mosHTML::makeOption('07', _JUL), mosHTML::makeOption('08', _AUG), mosHTML::makeOption('09', _SEP), mosHTML::makeOption('10', _OCT), mosHTML::makeOption('11', _NOV), mosHTML::makeOption('12', _DEC));
		
		return mosHTML::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
	}
	
	/**
	 * Generates an HTML select list from a tree based query list
	 */
	function treeSelectList(&$src_list, $src_id, $tgt_list, $tag_name, $tag_attribs, $key, $text, $selected)
	{
		
		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach ($src_list as $v)
		{
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push($list, $v);
			$children[$pt] = $list;
		}
		// second pass - get an indent list of the items
		$ilist = mosTreeRecurse(0, '', array(), $children);
		
		// assemble menu items to the array
		$this_treename = '';
		foreach ($ilist as $item)
		{
			if ($this_treename)
			{
				if ($item->id != $src_id && strpos($item->treename, $this_treename) === false)
				{
					$tgt_list[] = mosHTML::makeOption($item->id, $item->treename);
				}
			}
			else
			{
				if ($item->id != $src_id)
				{
					$tgt_list[] = mosHTML::makeOption($item->id, $item->treename);
				}
				else
				{
					$this_treename = "$item->treename/";
				}
			}
		}
		// build the html select list
		return mosHTML::selectList($tgt_list, $tag_name, $tag_attribs, $key, $text, $selected);
	}
	
	/**
	 * Writes a yes/no select list
	 */
	function yesnoSelectList($tag_name, $tag_attribs, $selected, $yes = _CMN_YES, $no = _CMN_NO)
	{
		$arr = array(mosHTML::makeOption('0', $no), mosHTML::makeOption('1', $yes));
		
		return mosHTML::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
	}
	
	/**
	 * Generates an HTML radio list
	 */
	function radioList(&$arr, $tag_name, $tag_attribs, $selected = null, $key = 'value', $text = 'text')
	{
		reset($arr);
		$html = "";
		for ($i = 0, $n = count($arr); $i < $n; $i++)
		{
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = (isset($arr[$i]->id) ? @$arr[$i]->id : null);
			
			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array($selected))
			{
				foreach ($selected as $obj)
				{
					$k2 = $obj->$key;
					if ($k == $k2)
					{
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			}
			else
			{
				$extra .= ($k == $selected ? " checked=\"checked\"" : '');
			}
			$html .= "\n\t<input type=\"radio\" name=\"$tag_name\" id=\"$tag_name$k\" value=\"" . $k . "\"$extra $tag_attribs />";
			$html .= "\n\t<label for=\"$tag_name$k\">$t</label>";
		}
		$html .= "\n";
		return $html;
	}
	
	/**
	 * Writes a yes/no radio list
	 */
	function yesnoRadioList($tag_name, $tag_attribs, $selected, $yes = _CMN_YES, $no = _CMN_NO)
	{
		$arr = array(mosHTML::makeOption('0', $no), mosHTML::makeOption('1', $yes));
		return mosHTML::radioList($arr, $tag_name, $tag_attribs, $selected);
	}
	
	/**
	 * @param int The row index
	 */
	function idBox($rowNum, $recId, $checkedOut = false, $name = 'cid')
	{
		if ($checkedOut)
		{
			return '';
		}
		else
		{
			return '<input type="checkbox" id="cb' . $rowNum . '" name="' . $name . '[]" value="' . $recId . '" onclick="isChecked(this.checked);" />';
		}
	}
	
	function sortIcon($base_href, $field, $state = 'none')
	{
		global $site_root;
		
		$alts = array('none' => _CMN_SORT_NONE, 'asc' => _CMN_SORT_ASC, 'desc' => _CMN_SORT_DESC);
		$next_state = 'asc';
		if ($state == 'asc')
		{
			$next_state = 'desc';
		}
		else if ($state == 'desc')
		{
			$next_state = 'none';
		}
		
		$html = "<a href=\"$base_href&field=$field&order=$next_state\">" . "<img src=\"$site_root/images/M_images/sort_$state.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"{$alts[$next_state]}\" />" . "</a>";
		return $html;
	}
	
	/**
	 * Writes Close Button
	 */
	function CloseButton(&$params, $hide_js = NULL)
	{
		// displays close button in Pop-up window
		if ($params->get('popup') && !$hide_js)
		{
			?>
<div align="center" style="margin-top: 30px; margin-bottom: 30px;"><a
	href='javascript:window.close();'> <span class="small">
			<?php
			echo _PROMPT_CLOSE;
			?>
			</span> </a></div>
<?php
		}
	}
	
	/**
	 * Writes Back Button
	 */
	function BackButton(&$params, $hide_js = NULL)
	{
		// Back Button
		if ($params->get('back_button') && !$params->get('popup') && !$hide_js)
		{
			?>
<div class="back_button"><a href='javascript:history.go(-1)'>
			<?php
			echo _BACK;
			?>
			</a></div>
<?php
		}
	}
	
	/**
	 * Cleans text of all formating and scripting code
	 */
	function cleanText(&$text)
	{
		$text = preg_replace("'<script[^>]*>.*?</script>'si", '', $text);
		$text = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text);
		$text = preg_replace('/<!--.+?-->/', '', $text);
		$text = preg_replace('/{.+?}/', '', $text);
		$text = preg_replace('/&nbsp;/', ' ', $text);
		$text = preg_replace('/&amp;/', ' ', $text);
		$text = preg_replace('/&quot;/', ' ', $text);
		$text = strip_tags($text);
		$text = htmlspecialchars($text);
		return $text;
	}
	
	/**
	 * Writes Print icon
	 */
	function PrintIcon(&$row, &$params, $hide_js, $link, $status = NULL)
	{
		global $site_root, $mosConfig_absolute_path, $cur_template, $Itemid;
		if ($params->get('print') && !$hide_js)
		{
			// use default settings if none declared
			if (!$status)
			{
				$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
			}
			
			// checks template image directory for image, if non found default are loaded
			if ($params->get('icons'))
			{
				$image = mosAdminMenus::ImageCheck('printButton.png', '/images/M_images/', NULL, NULL, _CMN_PRINT, _CMN_PRINT);
			}
			else
			{
				$image = _ICON_SEP . '&nbsp;' . _CMN_PRINT . '&nbsp;' . _ICON_SEP;
			}
			
			if ($params->get('popup') && !$hide_js)
			{
				// Print Preview button - used when viewing page
				?>
<td align="right" width="100%" class="buttonheading"><a
	href="javascript:void(0)"
	onclick="javascript:window.print(); return false"
	title="<?php
				echo _CMN_PRINT;
				?>">
				<?php
				echo $image;
				?>
				</a></td>
<?php
			}
			else
			{
				// Print Button - used in pop-up window
				?>
<td align="right" width="100%" class="buttonheading"><a
	href="javascript:void(0)"
	onclick="window.open('<?php
				echo $link;
				?>','win2','<?php
				echo $status;
				?>');"
	title="<?php
				echo _CMN_PRINT;
				?>">
				<?php
				echo $image;
				?>
				</a></td>
<?php
			}
		}
	}
	
	function encoding_converter($text)
	{
		// replace vowels with character encoding
		$text = str_replace('a', '&#97;', $text);
		$text = str_replace('e', '&#101;', $text);
		$text = str_replace('i', '&#105;', $text);
		$text = str_replace('o', '&#111;', $text);
		$text = str_replace('u', '&#117;', $text);
		
		return $text;
	}
}

function os_info()
{
	$os = strpos(getenv('HTTP_USER_AGENT'), 'Win');
	if ($os)
		$sys = 'Win';
	else
		$sys = 'Linux';
	return $sys;
}

function getModule($name = "")
{
	global $config_absolute_path;
	$modulname = "mod_" . $name;
	$module_path = $config_absolute_path . "/modules/$modulname.php";
	if (!file_exists($module_path) || filesize($module_path) == 0)
	{
		$module_path = $config_absolute_path . "/modules/mod_error.php";
	}
	require_once ($module_path);
}
function getCategoryList($shopid = '')
{
	global $database;
	$sql = "SELECT * FROM oos_category";
	$sql .= " WHERE store_id = {$shopid}";
	$sql .= " AND is_active = 'Y'";
	$database->setQuery($sql);
	$result = $database->loadObjectList();
	return $result;
}
function getSessionData($session_id, $shop_id = '')
{
	global $database;
	$sql = "SELECT * FROM oos_session WHERE session_id ='{$session_id}'";
	$sql .= " AND store_id=" . $shop_id;
	$database->setQuery($sql);
	$database->loadObject($result);
	return $result;
}
function getShopData($shopid)
{
	global $database;
	$sql = "SELECT u.id,u.user_id,u.user_email,s.shop_name,s.imprint,s.address ,s.city,s.postcode,s.email,s.fax,s.image_url,s.style_url";
	$sql .= " FROM oos_user u LEFT JOIN oos_setting s ON u.id = s.store_id";
	$sql .= " WHERE u.id ='{$shopid}'";
	$database->setQuery($sql);
	$database->loadObject($result);
	return $result;
}
function getCategory($shopid, $id)
{
	global $database;
	$sql = "SELECT * FROM oos_category";
	$sql .= " WHERE id=" . $id . " AND store_id=" . $shopid;
	$sql .= " AND is_active = 'Y'";
	$database->setQuery($sql);
	$database->loadObject($category);
	return $category;
}
function getStoreID($shop_name = '')
{
	global $database;
	$sql = "SELECT * FROM oos_setting";
	$sql .= " WHERE shop_name='" . $shop_name . "'";
	$database->setQuery($sql);
	$database->loadObject($shop);
	return $shop->store_id;
}
function getShopId($shop_name)
{
	global $database;
	$sql = "SELECT * FROM oos_setting";
	$sql .= " WHERE shop_name='" . $shop_name . "'";
	$database->setQuery($sql);
	$database->loadObject($shop);
	return $shop->id;
}
function getProductList($cid)
{
	global $database;
	$sql = "SELECT * FROM oos_product WHERE id_category=" . $cid;
	$sql .= " AND is_active = 'Y'";
	$sql .= " ORDER By code";
	$database->setQuery($sql);
	$productList = $database->loadObjectList();
	return $productList;
}
function getOfferProductList($cid)
{
	global $database;
	$sql = "SELECT * FROM oos_product WHERE id_category=" . $cid;
	$sql .= " AND is_active = 'Y'";
	$sql .= " AND is_offer = 'Y'";
	$database->setQuery($sql);
	$productList = $database->loadObjectList();
	return $productList;
}
function getCustomerData($id)
{
	global $database;
	$sql = "SELECT * FROM oos_customer WHERE id=" . $id;
	$database->setQuery($sql);
	$database->loadObject($result);
	return $result;
}
function _txt($str, $is_num = false)
{
	if (!isset($str) || empty($str))
	{
		$str = ($is_num) ? 0 : '';
	}
	return $str;
}

if (!function_exists('dumpr'))
{
	function dumpr($data)
	{
		
		ob_start();
		var_dump($data);
		$c = ob_get_contents();
		ob_end_clean();
		
		$c = preg_replace("/\r\n|\r/", "\n", $c);
		
		// Insert linebreak after the first '{' character
		if (strpos($c, '{') !== false)
		{
			$c = substr_replace($c, "{\n", strpos($c, '{'), 1);
		}
		
		$c = str_replace("]=>\n", '] = ', $c);
		$c = preg_replace('/= {2,}/', '= ', $c);
		$c = preg_replace("/\[\"(.*?)\"\] = /i", "[$1] = ", $c);
		$c = preg_replace('/  /', "    ", $c);
		$c = preg_replace("/}\n( {0,})\[/i", "}\n\n$1[", $c);
		$c = preg_replace("/\"\"(.*?)\"/i", "\"$1\"", $c);
		
		$c = htmlspecialchars($c, ENT_NOQUOTES);
		
		// Syntax Highlighting of Strings. This seems cryptic, but it will also allow non-terminated strings to get parsed.
		$c = preg_replace("/(\[[\w ]+\] = string\([0-9]+\) )\"(.*?)/sim", "$1<span class=\"string\">\"", $c);
		$c = preg_replace("/(\"\n{1,})( {0,}\})/sim", "$1</span>$2", $c);
		$c = preg_replace("/(\"\n{1,})( {0,}\[)/sim", "$1</span>$2", $c);
		$c = preg_replace("/(string\([0-9]+\) )\"(.*?)\"\n/sim", "$1<span class=\"string\">\"$2\"</span>\n", $c);
		
		$regex = array(// Numberrs
'numbers' => array('/(^|] = )(array|float|int|string|object\(.*\))\(([0-9\.]+)\)/i', '$1$2(<span class="number">$3</span>)'), // Keywords
'null' => array('/(^|] = )(null)/i', '$1<span class="keyword">$2</span>'), 'bool' => array('/(bool)\((true|false)\)/i', '$1(<span class="keyword">$2</span>)'), // Objects
'object' => array('/(object|\&amp;object)\(([\w]+)\)/i', '$1(<span class="object">$2</span>)'), // Function
'function' => array('/(^|] = )(array|string|int|float|bool|object)\(/i', '$1<span class="function">$2</span>('));
		
		foreach ($regex as $x)
		{
			$c = preg_replace($x[0], $x[1], $c);
		}
		
		$style = '
	    /* outside div - it will float and match the screen */
	    .dumpr {
	        margin: 2px;
	        padding: 2px;
	        background-color: #fbfbfb;
	        float: left;
	        clear: both;
	    }
	    /* font size and family */
	    .dumpr pre {
	        color: #000000;
	        font-size: 9pt;
	        font-family: "Courier New",Courier,Monaco,monospace;
	        margin: 0px;
	        padding-top: 5px;
	        padding-bottom: 7px;
	        padding-left: 9px;
	        padding-right: 9px;
	    }
	    /* inside div */
	    .dumpr div {
	        background-color: #fcfcfc;
	        border: 1px solid #d9d9d9;
	        float: left;
	        clear: both;
	    }
	    /* syntax highlighting */
	    .dumpr span.string {color: #c40000;}
	    .dumpr span.number {color: #ff0000;}
	    .dumpr span.keyword {color: #007200;}
	    .dumpr span.function {color: #0000c4;}
	    .dumpr span.object {color: #ac00ac;}
	    ';
		
		$style = preg_replace("/ {2,}/", "", $style);
		$style = preg_replace("/\t|\r\n|\r|\n/", "", $style);
		$style = preg_replace("/\/\*.*?\*\//i", '', $style);
		$style = str_replace('}', '} ', $style);
		$style = str_replace(' {', '{', $style);
		$style = trim($style);
		$c = trim($c);
		
		echo "\n<!-- dumpr -->\n";
		echo "<style type=\"text/css\">" . $style . "</style>\n";
		echo "<div class=\"dumpr\"><div><pre>\n$c\n</pre></div></div><div style=\"clear:both;\">&nbsp;</div>";
		echo "\n<!-- dumpr -->\n";
	}
}
?>
