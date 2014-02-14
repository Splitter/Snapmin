<?php
/**
* Category.php - dropdown select containing all available categories 
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
class CategoryOption extends OptionType
{ 
	public function displayElement()
	{
	
		$categories = &get_categories( array( 'hide_empty' => false ) );
       		if ( $categories )
       		{
		?>	
		<select name="<?php echo $this->name;?>" id="<?php echo $this->name;?>"> 
			<?php foreach($categories as $opt){?>
				<option value = "<?php echo $opt->term_id;?>" <?php if ( $opt->term_id  == $this->value) { echo ' selected="selected"'; } ?>><?php echo $opt->name;?></option>
			
			<?php } ?>
		</select>
		<?php
			}
	}	
}



?>