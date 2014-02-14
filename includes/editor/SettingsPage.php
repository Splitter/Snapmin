<?php
/**
* SettingsPage.php - general settings for framework.
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
class SnapSettingsPage
{

	private $posted,
			$xml,
			$errors;
	/* Constructor just initializes a few internals and adds hooks */
	public function __construct($xml)
	{	
		$this->posted = false;
		$this->xml = $xml;
		$this->errors = array();
	
	}
	public function renderPage(){
	
		$this->posted = false;
		$title = (string)$this->xml->title;
		$pos = (string)$this->xml->position;
		$cap = (string)$this->xml->capability;
		
		if(!empty($_POST) && check_admin_referer("snapmin_section","snapmin_section_wpnonce"))
		{
			if(!isset($_POST['snapmin_title']) or trim($_POST['snapmin_title'])==""){
				$this->errors[] = __("All fields must be filled out.");
			
			}
			if(!isset($_POST['snapmin_pos']) or !is_numeric($_POST['snapmin_pos'])){
				$this->errors[] = __("Position must be a Numeric Value.");
			}
			$title = $_POST['snapmin_title'];
			$pos = $_POST['snapmin_pos'];
			$cap = $_POST['snapmin_capability'];
			if(sizeof($this->errors)<=0){
				$this->xml->title = $title;
				$this->xml->position = $pos;
				$this->xml->capability = $cap;
			
				
				$dom = new DOMDocument('1.0');
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;
				$dom->loadXML($this->xml->asXML());
				$dom->save(SNAPMIN_XML_FILE);			
			}
			$this->posted = true;
		}
	?>
	<div class="wrap"> 
		<div id="icon-options-general" class="icon32"><br /></div> 
		<h2><?php _e("Snapmin Section Settings");?></h2> 
		<br/>
		<?php
		$this->renderNotifications();
		
		?>
		
		<form method="post" action="admin.php?page=snapmin_section"> 
			
			<?php wp_nonce_field("snapmin_section","snapmin_section_wpnonce"); ?> 
						
			
			<table class="form-table"> 
			
				<tr valign="top"> 
					<th scope="row">
						<label for="snapmin_title">
							<?php _e("Title :");?> 
						</label>
					</th> 
					<td>
						<input name="snapmin_title" type="text" id="snapmin_title" value="<?php echo $title;?>" class="regular-text" /> 
							<span class="description">
								<?php _e("The main title shown in the menu heading.");?>
							</span>
					</td> 
				</tr> 
				
				
				<tr valign="top"> 
					<th scope="row">
						<label for="snapmin_pos">
							<?php _e("Position :");?> 
						</label>
					</th> 
					<td>
						<input name="snapmin_pos" type="text" id="snapmin_pos" value="<?php echo $pos;?>" class="regular-text" /> 
							<script type="text/javascript">jQuery('input[name=snapmin_pos]').numeric();</script>	
							<span class="description">
								<?php _e("The numeric position in the menu order this menu should appear.");?>
							</span>
					</td> 
				</tr> 
				
				<tr valign="top"> 
					<th scope="row">
						<label for="snapmin_capability">
							<?php _e("Capability :");?> 
						</label>
					</th> 
					<td>
						<select name="snapmin_capability" id="snapmin_capability"> 
								<option value = "manage_options" <?php echo ($cap == 'manage_options')? "selected='selected'" : ""; ?> >manage_options</option>
								<option value = "edit_theme_options" <?php echo ($cap == 'edit_theme_options')? "selected='selected'" : ""; ?>>edit_theme_options</option>
								<option value = "edit_themes" <?php echo ($cap == 'edit_themes')? "selected='selected'" : ""; ?>>edit_themes</option>
								<option value = "update_core" <?php echo ($cap == 'update_core')? "selected='selected'" : ""; ?>>update_core</option>
								<option value = "edit_comment" <?php echo ($cap == 'edit_comment')? "selected='selected'" : ""; ?>>edit_comment</option>
								<option value = "manage_network" <?php echo ($cap == 'manage_network')? "selected='selected'" : ""; ?>>manage_network</option>
								<option value = "manage_sites" <?php echo ($cap == 'manage_sites')? "selected='selected'" : ""; ?>>manage_sites</option>
								<option value = "manage_network_users" <?php echo ($cap == 'manage_network_users')? "selected='selected'" : ""; ?>>manage_network_users</option>
								<option value = "manage_network_themes" <?php echo ($cap == 'manage_network_themes')? "selected='selected'" : ""; ?>>manage_network_themes</option>
								<option value = "manage_network_options" <?php echo ($cap == 'manage_network_options')? "selected='selected'" : ""; ?>>manage_network_options</option>
								<option value = "unfiltered_html" <?php echo ($cap == 'unfiltered_html')? "selected='selected'" : ""; ?>>unfiltered_html</option>
								<option value = "activate_plugins" <?php echo ($cap == 'activate_plugins')? "selected='selected'" : ""; ?>>activate_plugins</option>
								<option value = "add_users" <?php echo ($cap == 'add_users')? "selected='selected'" : ""; ?>>add_users</option>
								<option value = "create_users" <?php echo ($cap == 'create_users')? "selected='selected'" : ""; ?>>create_users</option>
								<option value = "delete_others_pages" <?php echo ($cap == 'delete_others_pages')? "selected='selected'" : ""; ?>>delete_others_pages</option>
								<option value = "delete_others_posts"<?php echo ($cap == 'delete_others_posts')? "selected='selected'" : ""; ?> >delete_others_posts</option>
								<option value = "delete_pages" <?php echo ($cap == 'delete_pages')? "selected='selected'" : ""; ?>>delete_pages</option>
								<option value = "delete_plugins" <?php echo ($cap == 'delete_plugins')? "selected='selected'" : ""; ?>>delete_plugins</option>
								<option value = "delete_posts" <?php echo ($cap == 'delete_posts')? "selected='selected'" : ""; ?>>delete_posts</option>
								<option value = "delete_private_pages" <?php echo ($cap == 'delete_private_pages')? "selected='selected'" : ""; ?>>delete_private_pages</option>
								<option value = "delete_private_posts" <?php echo ($cap == 'delete_private_posts')? "selected='selected'" : ""; ?>>delete_private_posts</option>
								<option value = "delete_published_pages" <?php echo ($cap == 'delete_published_pages')? "selected='selected'" : ""; ?>>delete_published_pages</option>
								<option value = "delete_published_posts" <?php echo ($cap == 'delete_published_posts')? "selected='selected'" : ""; ?>>delete_published_posts</option>
								<option value = "delete_themes" <?php echo ($cap == 'delete_themes')? "selected='selected'" : ""; ?>>delete_themes</option>
								<option value = "delete_users" <?php echo ($cap == 'delete_users')? "selected='selected'" : ""; ?>>delete_users</option>
								<option value = "edit_dashboard" <?php echo ($cap == 'edit_dashboard')? "selected='selected'" : ""; ?>>edit_dashboard</option>
								<option value = "edit_files" <?php echo ($cap == 'edit_files')? "selected='selected'" : ""; ?>>edit_files</option>
								<option value = "edit_others_pages" <?php echo ($cap == 'edit_others_pages')? "selected='selected'" : ""; ?>>edit_others_pages</option>
								<option value = "edit_others_posts" <?php echo ($cap == 'edit_others_posts')? "selected='selected'" : ""; ?>>edit_others_posts</option>
								<option value = "edit_pages" <?php echo ($cap == 'edit_pages')? "selected='selected'" : ""; ?>>edit_pages</option>
								<option value = "edit_plugins" <?php echo ($cap == 'edit_plugins')? "selected='selected'" : ""; ?>>edit_plugins</option>
								<option value = "edit_posts" <?php echo ($cap == 'edit_posts')? "selected='selected'" : ""; ?>>edit_posts</option>
								<option value = "edit_private_pages" <?php echo ($cap == 'edit_private_pages')? "selected='selected'" : ""; ?>>edit_private_pages</option>
								<option value = "edit_private_posts" <?php echo ($cap == 'edit_private_posts')? "selected='selected'" : ""; ?>>edit_private_posts</option>
								<option value = "edit_published_pages" <?php echo ($cap == 'edit_published_pages')? "selected='selected'" : ""; ?> >edit_published_pages</option>
								<option value = "edit_published_posts" <?php echo ($cap == 'edit_published_posts')? "selected='selected'" : ""; ?>>edit_published_posts</option>
								<option value = "edit_users"<?php echo ($cap == 'edit_users')? "selected='selected'" : ""; ?> >edit_users</option>
								<option value = "export" <?php echo ($cap == 'export')? "selected='selected'" : ""; ?> >export</option>
								<option value = "import" <?php echo ($cap == 'import')? "selected='selected'" : ""; ?>>import</option>
								<option value = "install_plugins" <?php echo ($cap == 'install_plugins')? "selected='selected'" : ""; ?>>install_plugins</option>
								<option value = "install_themes" <?php echo ($cap == 'install_themes')? "selected='selected'" : ""; ?>>install_themes</option>
								<option value = "list_users" <?php echo ($cap == 'list_users')? "selected='selected'" : ""; ?>>list_users</option>
								<option value = "manage_categories" <?php echo ($cap == 'manage_categories')? "selected='selected'" : ""; ?>>manage_categories</option>
								<option value = "manage_links" <?php echo ($cap == 'manage_links')? "selected='selected'" : ""; ?>>manage_links</option>
								<option value = "moderate_comments" <?php echo ($cap == 'moderate_comments')? "selected='selected'" : ""; ?>>moderate_comments</option>
								<option value = "promote_users" <?php echo ($cap == 'promote_users')? "selected='selected'" : ""; ?> >promote_users</option>
								<option value = "publish_pages" <?php echo ($cap == 'publish_pages')? "selected='selected'" : ""; ?>>publish_pages</option>
								<option value = "publish_posts" <?php echo ($cap == 'publish_posts')? "selected='selected'" : ""; ?>>publish_posts</option>
								<option value = "read_private_pages" <?php echo ($cap == 'read_private_pages')? "selected='selected'" : ""; ?>>read_private_pages</option>
								<option value = "read_private_posts" <?php echo ($cap == 'read_private_posts')? "selected='selected'" : ""; ?>>read_private_posts</option>
								<option value = "read" <?php echo ($cap == 'read')? "selected='selected'" : ""; ?>>read</option>
								<option value = "remove_users" <?php echo ($cap == 'remove_users')? "selected='selected'" : ""; ?>>remove_users</option>
								<option value = "switch_themes" <?php echo ($cap == 'switch_themes')? "selected='selected'" : ""; ?>>switch_themes</option>
								<option value = "unfiltered_upload" <?php echo ($cap == 'unfiltered_upload')? "selected='selected'" : ""; ?>>unfiltered_upload</option>
						</select>
						<span class="description">
								<?php _e("The capability a user needs in order to access admin page menus.<br/>('manage_options', 'edit_theme_options' or 'edit_themes' recommended).");?>
						</span>
					</td> 
				</tr> 
			
			
			</table>
			<p class="submit"> 
				<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes');?>" /> 
			</p> 
		</form>
	</div>
	<?php
	}
	

	public function renderNotifications(){
	
	
		if(sizeof($this->errors)<=0 and $this->posted)
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
			else if(sizeof($this->errors)>0)
			{
			?>
				<div class='error settings-error'> 
					<p>
						<strong>
							<?php _e('Some errors have occured:');?>							
						</strong>
						<ul>
							<?php
							foreach($this->errors as $error)
							{
								?>
									<li> - <?php echo $error;?></li>
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