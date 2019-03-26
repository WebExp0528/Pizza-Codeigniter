<?php 
echo doctype('xhtml1-trans');
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>';
echo meta('Content-Type', 'text/html; charset='.$this->config->item('charset'), 'http-equiv');
echo '<title>'.$this->config->item('sitename').'</title>';
echo link_tag('css/portal.css');
echo '<script language="javascript">var base_url = "'.base_url().'"; var site_url = "'.site_url().'";</script>';
echo $this->javascript->external('js/jquery-1.8.3.js');
echo $this->javascript->external('js/jquery.validate.js');
echo $this->javascript->external('js/jquery.simplemodal.js');
echo $this->javascript->external('js/jquery.placeholder.js');
echo $this->javascript->external('js/common.js');
echo '</head>';	
?>

<body>
	<div class="main_container">
    	<div class="container">
    	
   	
<?php
	echo portal_header();
?>
            <div class="header_2">
            </div>
            <div class="header_3">
            	<div class="header_man">
                </div>
                <div class="header_3_2">
                	<div class="header_3_2_1">
                    	<div style="text-align:right;">
                        	HIRE POSTLEITZAHL
                        </div>
                        <div style="text-align:right;">
                        	EINGEBEN
                        </div>
                    </div>
                	<div class="header_3_2_2">
                    	<!-- narrow image -->
                    </div>
                	<div class="header_3_2_4" id="id_header_3_2_4">
	              		<div class="header_3_2_4_1">
                        	LOS GEHT'S
                        </div>
                    </div>
                	<div class="header_3_2_3">
                		<?php 
                		echo form_open('portal/search', array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
                		echo form_input(array('type' => 'hidden', 'name' => 'postcode', 'id' => 'id-form-postcode'), '');
                		echo form_close();
                		?>
                		
                       	<input name="postcode" type="text" class="cls_postcode" id="id_postcode" autocomplete="off" value="PLZ (z.B. 10179)" maxlength="50" />
                        <div class="unshown-id-postcode-search">
                        	&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>

                    <div class="clear-both">
                    </div>
                </div>
            </div>
            <div class="header_4">
            	<!---- between header and body--->
            </div>
            <div class="homepage_body">
            	<!------body------>
                <div class="body_1">
                	<div class="body_1_1">
                    	<div class="body_1_1_1">
                        	UNSERE GRÃ–SSTEN STÃ„DT
                        </div>
                        <div class="body_1_1_2">
                        </div>
                        <?php
                        if (!isset($contents) || count($contents) == 0) {
                        	echo '<div class="body_row_white"><div class="body_column" style="color:#F03030;">Er is geen closed shop!</div>';
                        	echo '<div class="clear-both"></div></div>';
                        }
                        else
                        {
                        	$flag = 0;
                        	$idx = 0;
                        	
                        	foreach ($contents as $data) {
                        		if ($idx % 4 == 0) {
                        			if ($flag == 0) {
                        				echo '<div class="body_row_white">';
                        				$flag = 1;
                        			} else {
                        				echo '<div class="body_row_yellow">';
                        				$flag = 0;
                        			}
                        		}
                        		
                        		//echo '<div class="body_column">'.anchor('portal/search/'.$data[0], $data[1], array('class' => 'homepage_link')).'</div>';
                        		echo '<div class="body_column">'.$data->shop_name.'</div>';
                        		//<a href="portal-search.php?region_name= echo $data->region_name " class="homepage_link"> ///echo $data->region_name </a>
                        		
                        		if ($idx == (count($contents) - 1) || $idx % 4 == 3) {
                        			echo '<div class="clear-both"></div></div>';
                        		}
                        		$idx++;
                         	}
                        }
						?>
                        <div class="body_1_1_3">
                        </div>
                        
                        <div class="clear-both"></div>
                    </div>
                    <div class="body_1_2">
                    	<?php echo img(array('src' => 'images/portal-2.gif', 'border' => 0));?>
                    </div>
                    <div class="clear-both"></div>
                </div>
                <div class="body_2">
                	<div class="body_2_1">
                    	Lieferheld - Essen per Lieferservice online bestellen!
                    </div>
                	<div class="body_2_2">
                    	MIT LIEFERHELD NUR WENIGE KLICKS ENTFERNT: ESSEN BESTELLEN BEIM LIEFERSERVICE
                    </div>
                	<div class="body_2_3">
						Donec eget mauris bibendum nulla tincidunt feugiat. Nulla erat. Quisque neque. Etiam justo neque, fringilla ac, fermentum et, congue vel, risus. Proin egestas congue risus. Etiam sed felis. Praesent eget sapien eget dolor mollis auctor. Duis eleifend metus a leo. Donec commodo.
                    </div>
                	<div class="body_2_4">
						Vivamus pretium sem eu mi. Donec vitae erat quis est lacinia mattis. Curabitur tortor. Ut quis orci id eros porttitor tempus. Ut non risus.
                    </div>
                    <div class="body_2_2">
	                    Mit Lieferheld nur wenige Klicks entfernt: Essen bestellen beim Lieferservice
                    </div>
                    <div class="body_2_3">
	                    ivamus molestie pulvinar justo. Sed imperdiet turpis pretium mauris. Cras aliquam enim ac metus. Aenean arcu sem, placerat sit amet, sagittis nec, tincidunt ac, orci. In augue libero, consequat quis, dapibus a, aliquam ut, justo. Cras ornare nunc a leo. Sed bibendum, mauris vitae cursus imperdiet, purus sapien convallis leo, ac dapibus diam diam ac leo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vestibulum at mi et enim viverra adipiscing. In vestibulum, diam sed placerat tempus, elit purus suscipit est, sed convallis nisl tellus a nulla. Duis non quam eget libero condimentum varius.
                    </div>
                    <div class="body_2_2">
	                    Mit Lieferheld nur wenige Klicks entfernt: Essen bestellen beim Lieferservice
                    </div>
                    <div class="body_2_3">
	                    Mauris varius, lorem vitae molestie consectetuer, ligula justo hendrerit felis, ut laoreet diam magna quis diam. Aliquam tempus elit non est. Pellentesque dui. Nunc pharetra aliquet mauris. Vivamus molestie pulvinar justo. Sed imperdiet turpis pretium mauris. Cras aliquam enim ac metus. Aenean arcu sem, placerat sit amet, sagittis nec, tincidunt ac, orci. In augue libero, consequat quis, dapibus a, aliquam ut, justo. Cras ornare nunc a leo. Sed bibendum, mauris vitae cursus imperdiet, purus sapien convallis leo, ac dapibus diam diam ac leo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vestibulum at mi et enim viverra adipiscing.
                    </div>
                    <div class="body_2_3">
	                    Donec eget mauris bibendum nulla tincidunt feugiat. Nulla erat. Quisque neque. Etiam justo neque, fringilla ac, fermentum et, congue vel, risus. Proin egestas congue risus. Etiam sed felis. Praesent eget sapien eget dolor mollis auctor. Duis eleifend metus a leo. Donec commodo.
                    </div>

                </div>
                <div class="clear-both"></div>
            </div>
            <div class="body_3">
            		<!------- footer bar------>
            </div>
            <div class="body_4">
            	Â© <?php echo mdate('Y'); ?> my-shopname.com / my shop name
            </div>
        </div>
    </div>
</body>
</html>
