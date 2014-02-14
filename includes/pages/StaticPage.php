<?php
/**
* StaticPage.php defines the functionality and templates for static option pages.
*
* - A static page is a single page with options of various types listed sequentially
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
class StaticPage extends PageType
{
	//add a new option/element to the page
    public function addOption($option) 
	{
		$class = ucwords(strtolower($option['type']))."Option";
		$option['validator'] = (isset($option['validator']))?$option['validator']:null;
		$option['value'] = (isset($this->values[$option['name']]))?$this->values[$option['name']]:$option['def'];
		$opt = new $class($option); 
		if($opt->save){$this->values[$option['name']]=$option['value'];}
		$this->options[$option['name']]=$opt;	
	}
	
	//Handles rendering of page
	public function renderPage(){
		//process form if form was submitted. 
		$this->posted = false;
		if((isset($_REQUEST['page']) and $_REQUEST['page']==$this->menuSlug) and (!empty($_POST) && check_admin_referer($this->menuSlug,$this->menuSlug."_wpnonce") ))
		{
			$this->proccessForm();
			$this->posted = true;
		}
		
		//render actual page html using template functions of class
		$this->renderHead();	
		foreach($this->options as $opt)
		{
			$this->renderElement($opt);
		}
		$this->renderFoot();
		
	}
	
	//process form
	public function proccessForm(){
		
		foreach($this->options as $opt)
			{
				if(isset($_POST[$opt->name]) && $opt->save)
				{					
					$this->values[$opt->name] = $this->options[$opt->name]->setValue($_POST[$opt->name]);
					if(!$this->options[$opt->name]->validate())
					{
						$this->errors = true;
					}
				}			
			}
				
		if(!$this->errors){
			$this->saveOptions();
		}
	}
	
	public function addInit(){
		wp_enqueue_script('sv_staticpage',
							SNAPMIN_URI.'assets/js/staticpage.js',
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
	
/**
*
* Functions that hold the actual HTML templates for the page
*
*
*/
	public function renderHead(){
		?>	<script type="text/javascript">var snapmin_url = "<?php echo SNAPMIN_URI;?>"</script>
			<div class="wrap"> 
				<div id="icon-options-general" class="icon32"><br /></div> 
				<h2><?php echo $this->title;?></h2> 
				<br/>
				<?php $this->renderNotifications(); ?>	
				<form method="post" action="admin.php?page=<?php echo $this->menuSlug;?>"> 
					
					<?php wp_nonce_field($this->menuSlug,$this->menuSlug."_wpnonce"); ?>
					
					<table class="form-table"> 
		<?php
	}
	
	
	public function renderFoot(){	
		?>
					</table>
					<p class="submit"> 
					<?php submit_button( 'Save Changes' );?>
					</p> 
				</form>
			</div>	
		<?php
	}
	
	
	public function renderElement($opt){
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
	
	public function renderNotifications(){
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
						foreach($this->options as $opt)
						{
							if($opt->error)
							{
						?>
						<li> <?php echo $opt->title." - ".$opt->errorMessage;?></li>
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
};




?>