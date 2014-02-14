<?php
/**
* GallerytOption.php - image gallery option
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
class GalleryOption extends OptionType
{ 
	public function __construct($option) {
		$this->save = true;
		$this->name =$option['name'];
		$this->title = $option['title'];
		$this->desc = (isset($option['description']))? $option['description']:"";
		$this->def = (isset($option['def']))? $option['def']:"";
		$this->value = (isset($option['value']))? $option['value']:"";
		$this->options = (isset($option['options']))? $option['options']:array();
		$this->validator = (isset($option['validator']))? $option['validator']:null;
		$this->errorMessage = (isset($option['errorMessage']))? $option['errorMessage']:__("Invalid Value!");
		
		add_action( 'wp_ajax_gallery_update', array( $this, 'ajax_gallery_update' ) );
		
	}
	
	public function displayElement()
	{	
        $ids = ! empty( $this->value ) && $this->value != '' ? explode( ',', $this->value ) : array();
		?>
		<div class = "sv-gallery-wrapper">
			<div class="sv-gallery-button top">
				<a href="#"  class="button button-secondary sv-gallery-edit"><?php _e( 'Edit Gallery' );?></a>
			</div>
			<div class="sv-gallery-inner">
			<?php if ( ! empty( $ids ) ) { ?>
				<ul>
				<?php
				foreach( $ids as $id ) {
            
					if ( $id == '' )
					  continue;
					  
					$thumb = wp_get_attachment_image_src( $id, 'thumbnail' );
				
					echo '<li><img  src="' . $thumb[0] . '" /></li>';
				
				 }
				?>
				
				</ul>
			
			
			
				<?php }else{  _e( 'no images to display yet...'); }?>
			</div>
			<input type="hidden" name="<?php echo $this->name ?>" id="<?php echo $this->name ?>" value="<?php echo $this->value ?>" class="sv-gallery-data" />
			<div class="sv-gallery-button">
				<a href="#"  class="button button-secondary sv-gallery-edit"><?php _e( 'Edit Gallery' );?></a>
			</div>
		</div>
		<?php
	}	
	
	public function addInit()
	{
	    wp_enqueue_script( 'jquery' );
		wp_enqueue_media();
		wp_enqueue_script('sv_gallery',
							SNAPMIN_URI.'assets/js/gallery.js',
							 array('jquery'),
							'1.0.1' );
		wp_enqueue_style( 'sv_galler', 
							SNAPMIN_URI.'assets/css/gallery.css');
	
	}
	public function addHead()
	{
	
	}
	public function ajax_gallery_update() {
    
      if ( ! empty( $_POST['ids'] ) )  {
        
        $return = '<ul>';
        
        foreach( $_POST['ids'] as $id ) {
        
			if ( $id == '' )
					  continue;
					  
			$thumb = wp_get_attachment_image_src( $id, 'thumbnail' );				
			$return .= '<li><img  src="' . $thumb[0] . '" /></li>';
          
        }
        
        echo $return."</ul>";
        exit();
      
      }
      
    }
}



?>