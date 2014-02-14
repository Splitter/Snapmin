<?php
/**
* Tag.php - dropdown select containing all available tags
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
class TagOption extends OptionType
{ 
	public function displayElement()
	{
	
		$tags = &get_tags( array( 'hide_empty' => false ) );
       		if ( $tags )
       		{
		?>	
		<select name="<?php echo $this->name;?>" id="<?php echo $this->name;?>"> 
			<?php foreach($tags as $opt){?>
				<option value = "<?php echo $opt->term_id;?>" <?php if ( $opt->term_id  == $this->value) { echo ' selected="selected"'; } ?>><?php echo $opt->name;?></option>
			
			<?php } ?>
		</select>
		<?php
			}
	}	
}



?>