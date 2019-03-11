<?php
global $database, $shop, $site_root, $shop_url;

$list = getCategoryList($shop->id);
$cat_id = ($list) ? $list[0]->id : '';
$cat_id = mosGetParam( $_REQUEST, 'catid', $cat_id);
$op_req = mosGetParam( $_REQUEST, 'op', 'category');

$sql = "SELECT * FROM oos_specialoffer";
$sql .= " WHERE store_id = {$shop->id}";
$database->setQuery($sql);
$offers = $database->loadObjectList();
$list = array_merge($offers,$list);
$row_num = $col_num = 0;
$cnt = count($list);
if($cnt>0) {
	$row_num = 5;
	$col_num = 4;
}
if($cnt>20) $col_num = ceil($cnt/5);
$index = 0;
$flag = 0;
?> 
<div id="category_list">
	<?php for($i=0;$i<$col_num;$i++){?>
		<ul>
			<?php for($j = 0; $j < $row_num; $j++){
				$list[$index]->name = isset($list[$index]->offer_name) ? $list[$index]->offer_name : $list[$index]->name;
				$url_name = isset($list[$index]->seo_name) ? $list[$index]->seo_name : $list[$index]->name;
				//$url = "index.php?task=category&request=list&special=2&id=".$list[$index]->id;
				if(isset($list[$index]->offer_name)) {
					$op = "offer";$special="3";$class="offer_link";
					$url = $shop_url."?task=category&request=special_offer&special=3&offer_id=".$list[$index]->id;
				}
				else {
					$op = "category";$special="2";$class="category_link";
					$url = $shop_url."/".$url_name.".html?catid=".$list[$index]->id;
				}
				//$url .= "&op=".$op."&special=".$special;
				if($list[$index]->id == $cat_id && $op == $op_req) $class.=" is_selected";
				?>
				<a href="#" alt="<?php echo $url;?>" class="<?php echo $class;?>"><li type="square"><?php echo $list[$index]->name; ?></li></a>
			<?php $index++;
			if($index == $cnt) {$flag= 1;break;}
			}?>
		</ul>
	<?php if($flag == 1) break;}?>
</div>
