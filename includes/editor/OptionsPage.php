<?php
/**
* OptionsPage.php - 'manage options' section of the page builder/editor.
*
* LICENSE: GPLv2
*
* @package    Snapmin
* @copyright  Copyright (c) 2012-2014 Mike Pippin a.k.a Splitter
* @license    http://wordpress.org/about/gpl/
* @version    1.0.1
* @link       https://github.com/Splitter/Snapmin
* 
*/
class SnapOptionsPage
{
	private $posted,
			$xml,
			$errors,
			$valid;
			
	public function __construct($xml)
	{	
		$this->posted = false;
		$this->xml = $xml;
		$this->errors = array();	
		$this->valid = array(
								"name",
								"description"
							);
	}
			
	function processForm($SnapOptions){
			$this->posted = true;
			$count = (int)$_POST['numopts'];
			$numPageOptions = (int)$_POST['numpageopts'];
			if($count>0){
					$options = array();
					$pageOptions = array();
					for($i=1;$i<=$count;$i++){						
						if(isset($_POST['names'.$i])){
							$type = $_POST['type'.$i];
							$opt = $SnapOptions[$type];
							$option = array(
										'option'=>array()
										);
							
							$option['option']['type']=$type;
							if(in_array("description",$opt['valid'])){
								$option['option']['desc']= htmlspecialchars( stripslashes($_POST['description'.$i]), ENT_QUOTES);
							
							}
							
							if(in_array("title",$opt['valid'])){
								$option['option']['title']=htmlspecialchars( stripslashes($_POST['title'.$i]), ENT_QUOTES);
								if(trim($_POST['title'.$i])==""){
									$this->errors[1]=__("Title field is required.");
								}
							}
							
							if(in_array("name",$opt['valid'])){
								$option['option']['name']=htmlspecialchars( stripslashes($_POST['names'.$i]), ENT_QUOTES);
								if(!preg_match("/^[A-Za-z0-9_-]+$/", $_POST['names'.$i])){
									$this->errors[2]=__("Name fields can only contain hyphens, underscores and alphanumeric characters.");
								}
							}
							
							if(in_array("default",$opt['valid'])){
								$option['option']['def']=htmlspecialchars( stripslashes($_POST['default'.$i]), ENT_QUOTES);							
							}
							
							if(in_array("options",$opt['valid'])){
									$nArr = array();
									$count2 = 0;
									if(sizeof($_POST['name'.$i])>0){
										for($j=0,$l=sizeof($_POST['name'.$i]);$j<$l;$j++){
											$count2++;
											$val = htmlspecialchars( stripslashes($_POST['value'.$i][$j]), ENT_QUOTES);
											$name = htmlspecialchars( stripslashes($_POST['name'.$i][$j]), ENT_QUOTES);
											$nArr[] = $val.':;'.$name;
										}
										$option['option']['opts']=implode(',,',$nArr);
									}
									else{
										$this->errors[3]=__("If an element has an 'options' block then atleast one name/value pair must be defined.");
									}
							}
							$option['option']['disp']=(int)$_POST['disp'.$i];
							if((int)$_POST['disp'.$i]<=$numPageOptions){
								$pageOptions[(int)$_POST['disp'.$i]] = $option;
							}
							else{
								$options[(int)$_POST['disp'.($i-$numPageOptions)]] = $option;
							}
						}
					}
					
					//Sort options by display order and create array for xml conversion										
					$pArr = array();
					for($i=1;$i<=$numPageOptions;$i++){	
						$cur = null;
						foreach($pageOptions as $poption){
							if($poption['option']['disp']==$i){
								$cur = $poption;
								break;
							}
						}
						if($cur!=null){
							$pArr[] = $cur;
						}
					}
					$nArr = array();
					for($i=1;$i<=$count;$i++){	
						$cur = null;
						foreach($options as $option){
							if($option['option']['disp']==$i){
								$cur = $option;
								break;
							}
						}
						if($cur!=null){
							$nArr[] = $cur;
						}
					}
					
					$page = $this->xml->pages[0]->page[((int)$_REQUEST['edit'])-1];
					$page->options = new SimpleXMLElement("<options></options>");
					$page->pageOptions = new SimpleXMLElement("<pageOptions></pageOptions>");
					$this->array_to_xml($nArr, $page->options);
					$this->array_to_xml($pArr, $page->pageOptions);
					if(empty($this->errors)){
						$dom = new DOMDocument('1.0');
						$dom->preserveWhiteSpace = false;
						$dom->formatOutput = true;
						$dom->loadXML($this->xml->asXML());
						$dom->save(SNAPMIN_XML_FILE);		
					}
			}
	}
	
	public function renderPage(){
		include(SNAPMIN_LOCAL."/includes/options/Options.php");
		$page = $this->xml->pages[0]->page[((int)$_REQUEST['edit'])-1];
		if(isset($_POST['numopts']) ){
			$this->processForm($SnapOptions);
			$this->renderNotifications();
			$this->posted = true;
		}
	?>
	<div id = "snapmin-col-right">
		<h2><?php echo (string)$page->title;?></h2>
		<p><?php _e("All fields shown for elements are required with the exception of 'description' and 'default' values. Although descriptions are used as the text for 'paragraph' and 'heading' fields.");?></p>
		
		<form method="post" action="admin.php?page=snapmin_editor&edit=<?php echo $_REQUEST['edit'];?>&do=options"> 
				<?php wp_nonce_field("snapmin_options_editor","snapmin_options_editor_wpnonce"); ?> 			
				<input type = "hidden" name="do" value = "options"/>
				<input type = "hidden" name ="save" value = "1"/>
				<input type = "hidden" name="edit" value = "<?php echo $_REQUEST['edit'];?>"/>
				
				
		<?php
		$count=0;
		//If pagetype calls for page options
		include(SNAPMIN_LOCAL."/includes/pages/Pages.php");
		if($SnapPages[(string)$page->type]['pageOptions']){
		?>
		<h4><?php _e("Page Options");?></h4>
		<div class ="snapmin-page-container">				
			<ul class="snapmin-options-sortable" id="snapmin-options-page">
				<?php 
					$pagecount = 0;
					foreach($page->pageOptions as $options){
						foreach($options as $option){
							$pagecount++;
							$count++;
							$this->optionBlock($option,$count,$SnapOptions);
						}
					}				
				?>		
			</ul>
		</div>
				
		<h4><?php _e("Object / Element Options");?></h4>
		<?php } //page options?>
		
		<div class ="snapmin-page-container">			
			<ul class="snapmin-options-sortable" id="snapmin-options-single">
				<?php 
					foreach($page->options as $options){
						foreach($options as $option){
							$count++;
							$this->optionBlock($option,$count,$SnapOptions);
						}
					}				
				?>
			
			</ul>			
			
		</div>	
			<input type = "hidden" name = "numopts" value ="<?php echo $count;?>"/>
			<input type = "hidden" name = "numpageopts" value ="<?php echo $pagecount;?>"/>
						
			<p class="submit"> 
					<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes');?>" /> 
			</p> 
		</form>
	</div>
	<div id = "snapmin-col-left">
		<div class ="snapmin-options-heading"><?php _e("Drag an element to page");?></div>
		<div class ="snapmin-options-sidebar">
			<ul class = "snapmin-options-draggable">
			<?php 
				foreach( $SnapOptions as $type => $option){
			?>		
				<li> 
					<img class="<?php _e($type);?>" data-options = "<?php echo implode($option['valid'],',');?>" src = "<?php echo $option['image']; ?>" alt = "<?php _e($option['name']);?>"/>
						<?php _e($option['name']);?>
						
				</li>	
			<?php } ?>
			</ul>
		</div>
	</div>
	
	<?php
	
	
	}
			
	public function optionBlock($option,$count,$SnapOptions){
		$type = (string)$option->type;
		$opt = $SnapOptions[$type];
	?>
				<li>
					<div class="snapmin-options-type">
						<img src = "<?php echo $opt['image']; ?>" alt = ""/>
						<?php _e($opt['name']); ?>
						<img class="snapmin-close" src ="<?php echo SNAPMIN_URI;?>/assets/images/close.png" alt="close"/>
					</div>
		
					
					
					<div class="snapmin-col-right">
					<?php if(in_array("description",$opt['valid'])){?>
						<label>
							<span><?php _e('Description'); ?>&nbsp;&nbsp;</span>
							<textarea class="message" name="description<?php echo $count;?>" ><?php echo htmlspecialchars( stripslashes( (string)$option->desc), ENT_QUOTES);?></textarea>
						  
						</label>
					<?php }?>
					</div>
					<div class="snapmin-col-left">
					<?php if(in_array("title",$opt['valid'])){?>
						<label>
						   <span><?php _e('Title'); ?> *</span>
						   <input type="text"  value ="<?php echo htmlspecialchars( stripslashes( (string)$option->title), ENT_QUOTES);?>" class="snapmin-options-input" name="title<?php echo $count;?>" id="title<?php echo $count;?>"/>
						</label>
					<?php 
					}
					if(in_array("name",$opt['valid'])){
					?>
						<label>
						   <span><?php _e('Name'); ?> *</span>
						   <input type="text" value ="<?php echo htmlspecialchars( stripslashes( (string)$option->name), ENT_QUOTES);?>" class="snapmin-options-input" name="names<?php echo $count;?>" id="names<?php echo $count;?>"/>
						</label>
					<?php 
					}
					if(in_array("default",$opt['valid'])){
					?>
						<label>
						   <span><?php _e('Default'); ?> </span>
						   <input type="text" value ="<?php echo htmlspecialchars( stripslashes( (string)$option->def), ENT_QUOTES);?>" class="snapmin-options-input" name="default<?php echo $count;?>" id="default<?php echo $count;?>"/>
						</label>
					<?php 
					}
					?>
					</div>
					<div class ="snapmin-options-block">
					<?php 
					
					if(in_array("options",$opt['valid'])){
					?>
						<div class="snapmin-options-right">
						<?php 
							$nArr = array();
							 
							$option->opts =htmlspecialchars( stripslashes((string)$option->opts), ENT_QUOTES);
							$opts = explode(',,',$option->opts);
							$count2 = 0;
							foreach($opts as $opt){
								$count2++;
								$nopt = explode(':;',$opt);
								?>
								<div class="snapmin-options-inner">			
								   <input type="text" value = "<?php echo (string)$nopt[1];?>" class="snapmin-options-input" name="name<?php echo $count?>[]" id="name<?php echo $count?>"/>
								   <input type="text" value = "<?php echo (string)$nopt[0];?>" class="snapmin-options-input" name="value<?php echo $count?>[]" id="value<?php echo $count?>"/>
									<img class="snapmin-close-option" src ="<?php echo SNAPMIN_URI;?>/assets/images/close.png" alt="close"/>
								</div>									
								<?php
							}
						?>
						</div>
						<div class="snapmin-options-left">
							<span><?php _e('Options'); ?> *:</span>
							<a href="#" ><?php _e('Add New Option');?></a>
						</div>
						
					<?php 
					}
					?>
					</div>
						<input type = "hidden" name = "type<?php echo $count;?>" value ="<?php echo $type;?>"/>
						<input type = "hidden" class="snapdisplay" name = "disp<?php echo $count;?>" value ="<?php echo $count;?>"/>
						
					<div class="snapmin-clear"></div>
				</li>
	<?php
	
	
	
	}
	
	
	
	function array_to_xml($arr, &$page) {
		foreach($arr as $key => $value) {
			if(is_array($value)) {
				if(!is_numeric($key)){
					$subnode = $page->addChild("$key");
					$this->array_to_xml($value, $subnode);
				}
				else{
					$this->array_to_xml($value, $page);
				}
			}
			else {
				$page->addChild("$key","$value");
			}
		}
	}
	
	/*  */
	function renderNotifications(){
	
		if(empty($this->errors) and $this->posted)
			{					
			?>			
				<div class='updated settings-error'> 
					<p>
						<strong>
							<?php _e('Successfully Saved.'); ?>
						</strong>
					</p>
				</div>
			<?php
			}
			else if(sizeof($this->errors)>0)
			{
			?>
				<div class='error settings-error'> 
					<p>
						<strong>
							<?php _e('Some errors have occured:'); ?>							
						</strong>
						<ul>
							<?php
							foreach($this->errors as $error)
							{
								?>
									<li> - <?php echo $error; ?></li>
								<?php		
							}
							?>
						</ul>
					</p>
				</div>
			<?php			
			}		
	
	}
			
}

?>