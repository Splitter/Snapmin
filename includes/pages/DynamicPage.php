<?php
/**
* DynamicPage.php defines the functionality and templates for dynamic option pages.
*
* - A dynamic page contains multiple blocks of options which can be rearranged via drag/drop interface.
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
class DynamicPage extends PageType
{

    public function addOption($option) 
	{
		$class = ucwords(strtolower($option['type']))."Option";
		$name = $option['name'];
		$option['name'] = $option['name']."[]";
		$option['validator'] = ($option['validator'])?$option['validator']:null;
		$option['value'] = $option['def'];
		$opt = new $class($option); 
		$this->options[$name]=$opt;			
		
	}
	
    public function addPageOption($option) 
	{				
		$class = ucwords(strtolower($option['type']))."Option";
		$option['validator'] = (isset($option['validator']))?$option['validator']:null;
		$option['value'] = (isset($this->values['pageOptions'][$option['name']]))?$this->values['pageOptions'][$option['name']]:$option['def'];
		$opt = new $class($option); 
		if($opt->save){$this->values['pageOptions'][$option['name']]=$option['value'];}
		$this->pageOptions[$option['name']]=$opt;			
	}
	
	public function setValue($name,$value)
	{
		if(isset($this->options[$name]))
		{
			$this->options[$name]->setValue($value);
			if(!$this->options[$name]->validate())
			{
				$this->errors = true;
			}
			$this->options[$name]->reset();
		}
		if(isset($this->pageOptions[$name]))
		{
			$this->pageOptions[$name]->setValue($value);
			if(!$this->pageOptions[$name]->validate())
			{
				$this->errors = true;
			}
			$this->pageOptions[$name]->reset();
		}
	}
	
	
	public function proccessForm(){		
		$opts = array_keys($this->options);
		//make sure each post var is array
		foreach($opts as $name)
		{
			if(!is_array($_POST[$name]))
			{
				$this->errors = true;
				$this->errorMessage = __("Error in form submission, please try again.");				
			}
		}
		
		if(!$this->errors)
		{	
			$num = count($_POST[$opts[0]]);			
			$this->values['singleOptions'] = array();
			for($i = 0; $i < $num; $i++)
			{
				$new = array();
				foreach($opts as $name)
				{
					$this->setValue($name,$_POST[$name][$i]);
					$new[$name] = $_POST[$name][$i];
				}								
				$this->values['singleOptions'][$i] = $new;
			}
		}
		else
		{
			$this->errors = true;
			$this->errorMessage = __("Must be atleast one item.");				
		}

		foreach($this->pageOptions as $opt)
			{
				if(isset($_POST[$opt->name]) && $opt->save)
				{					
					$this->values['pageOptions'][$opt->name] = $this->pageOptions[$opt->name]->setValue($_POST[$opt->name]);
					if(!$this->pageOptions[$opt->name]->validate())
					{
						$this->errors = true;
					}
				}			
			}
				
		if(!$this->errors){
			$this->saveOptions();
		}
		
		if(!$this->errors)
		{
			$this->saveOptions();
		}
	}
	
	
	public function renderPage()
	{
		$this->posted = false;
		if((isset($_REQUEST['page']) and $_REQUEST['page']==$this->menuSlug) and (!empty($_POST) && check_admin_referer($this->menuSlug,$this->menuSlug."_wpnonce") ))
		{
			$this->proccessForm();
			$this->posted = true;
		}
		?>
		<script type="text/javascript">var snapmin_url = "<?php echo SNAPMIN_URI;?>"</script>
		<div class="wrap"> 
			<div id="icon-options-general" class="icon32"><br /></div> 
			<h2><?php echo $this->title;?></h2> 
			<br/>
			<?php $this->doNotifications(); ?>
				<div class="dynamic-default">
						<table class="form-table snap-dynamic">
						<tr>
						<?php		
						$count=0;
						foreach($this->options as $opt)
						{
							if($count!=0 && $count % 2 == 0){
								echo"</tr><tr>";
							}
							echo "<td>";
							echo "<label for=".$opt->name.">";
							echo $opt->title;
							echo "</label>";
							$opt->displayElement();
							echo "</td>";
							$count+=1;
						}	
						?>
						</tr>
						</table>			
				</div>
			
			<form method="post" action="admin.php?page=<?php echo $this->menuSlug;?>"> 
				
				<?php wp_nonce_field($this->menuSlug,$this->menuSlug."_wpnonce"); ?>
					
					<table class="form-table" style="margin-bottom:30px;"> 
						<?php
							foreach($this->pageOptions as $opt)
							{
								$this->renderPageElement($opt);
							}
						?>
					</table>
				
				<ul class = "dynamicList">
				<?php
					if(!isset($this->values['singleOptions']))
					{
					?>
					<li>
						<table class="form-table snap-dynamic"> 
						<tr>
						<?php		
						$count=0;
						foreach($this->options as $opt)
						{
							if($count!=0 && $count % 2 == 0){
								echo"</tr><tr>";
							}
							echo "<td>";
							echo "<label for=".$opt->name.">";
							echo $opt->title;
							echo "</label>";
							$opt->displayElement();
							echo "</td>";
							$count+=1;
						}	
						?>
						</tr>
						</table>
					</li>
					<?php
					}
					else
					{	
						foreach($this->values['singleOptions'] as $item)
						{						
						?>
						<li>
							<table class="form-table snap-dynamic"> 
								<tr>
								<?php		
							$count=0;
							foreach($this->options as $name=>$opt)
							{
								if($count!=0 && $count % 2 == 0){
									echo"</tr><tr>";
								}
								echo "<td>";
								echo "<label for=".$opt->name.">";
								echo $opt->title;
								echo "</label>";
								$opt->setValue($item[$name]);
								$opt->displayElement();	
								$opt->reset();
								echo "</td>";
								$count+=1;
							}	
							?>
								</tr>
							</table>
						</li>
						<?php
						}	
					}
				?>
				</ul>
				<p class="dynamic-submit submit"> 
					<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes');?>" /> 
				</p> 
			</form>
		</div>
		<?php	
	}
	
	
	public function doNotifications(){	
			if(!$this->errors and $this->posted)
			{					
			?>			
				<div class='updated settings-error'> 
					<p>
						<strong>
							<?php _e('Successfully Saved.');?>
						</strong>
					</p>
				</div>
			<?php
			}
			else if($this->errors)
			{
			?>
				<div class='error settings-error'> 
					<p>
						<strong>
							<?php _e('Some errors have occured:');?>							
						</strong>
						<ul>
							<?php
							if($this->errorMessage!="")
							{
							?>
								<li> - <?php echo $this->errorMessage;?></li>
							<?php						
							}
							
							foreach($this->options as $opt)
							{
								if($opt->error)
								{
								?>
									<li> - <?php echo $opt->errorMessage;?></li>
								<?php						
								}					
							}
							?>
						</ul>
					</p>
				</div>
			<?php			
			}		
	}
	
	public function renderPageElement($opt){
		if($opt->save==true){
		?>
			<tr valign="top"> 
				<th scope="row">
					<label for="<?php echo $opt->name;?>">
					<?php echo $opt->title;?> 
					</label>
				</th> 
				<td class="sv_static">

					<?php 
					$opt->displayElement();	
					if(isset($opt->desc) and $opt->desc!="")
					{
					?>
					<span class="description">
						<?php echo $opt->desc;?>
					</span>
					<?php 
					}
					?>
				</td> 
			</tr> 
		<?php
		}
		else{
		?>
		
			<tr valign="top"> 
				<td  colspan = "2" >
					<?php echo $opt->displayElement();	?>
				</td>
			</tr>
		<?php
		}
	}
	
	
	
	public function addInit()
	{			
		wp_enqueue_style('pm-dynamic-page', SNAPMIN_URI.'assets/css/dynamic-page.css', false, '1.0', 'all');
		
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('sv_dynamicpage',
							SNAPMIN_URI.'assets/js/dynamicpage.js',
							 array('jquery'),
							'1.0' );
		wp_enqueue_script('sv_tooltip',
							SNAPMIN_URI.'assets/js/jquery.svTitleTooltip.js',
							 array('jquery'),
							'1.0' );
		foreach($this->options as $opt)
		{
			$opt->addInit();
		}	
	}	
	

}



?>




