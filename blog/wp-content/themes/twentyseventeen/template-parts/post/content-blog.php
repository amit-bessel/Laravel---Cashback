<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>
<?php 
	//echo 'aa=>'.$i;
if($paged==1){
	if($i==1){
		$showTitle =  wp_trim_words( get_the_title(), 15 );
		$showDescription =  get_the_content();	
		$showDescription = mb_strimwidth($showDescription, 0, 100, '...');	
		$class = 'col-lg-8 col-md-12 latest-blog-card';
	}
	else if($i==2){
		$showTitle =  wp_trim_words( get_the_title(), 5 );
		$showDescription =  get_the_content();	
		$showDescription = mb_strimwidth($showDescription, 0, 40, '...');		
		$class = 'col-lg-4 col-md-6';
	}
	else{
		$showTitle =  wp_trim_words( get_the_title(), 5 );
		$showDescription =  get_the_content();	
		$showDescription = mb_strimwidth($showDescription, 0, 40, '...');	
		$class = 'col-lg-4 col-md-6';
	}

	}
	else{

		$showTitle =  wp_trim_words( get_the_title(), 5 );
		$showDescription =  get_the_content();	
		$showDescription = mb_strimwidth($showDescription, 0, 40, '...');	
		$class = 'col-lg-4 col-md-6';
	}
?>


<div id="post-<?php the_ID(); ?>" class="<?php echo $class;?>">
	<?php
	if ( is_sticky() && is_home() ) :
		echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
	endif;
	?>


<div class="blog-card">
<div class="blog-img-holder">
<?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
</div>

<div class="blog-dsc-content-wrap">
<div class="blog-dsc-left-info">
<!-- <a href="#" class="link"><i class="fas fa-chevron-right"></i></a> -->
</div>

<div class="blog-dsc-content">
	<div class="blog-dsc-cat-name">
		<?php 
			$category_detail=get_the_category();
				foreach($category_detail as $cd){
				echo '<span>';
				echo $cd->cat_name;
				echo '</span>';
			}
		?>
		
	</div>
  <h2><?php echo $showTitle; ?></h2>
<div class="blog-dsc-text">
		<?php
		/* translators: %s: Name of current post */
		//the_content();
       
		echo 'aa'. $showDescription;

		/*wp_link_pages( array(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );*/
		?>
</div>
<div class="d-flex justify-content-between align-items-center">
<div class="blog-dsc-date"><i class="fas fa-clock"></i> <?php echo get_the_date(); ?></div>
<a href="<?php the_permalink(); ?>" class="blog-link"><i class="la la-arrow-right"></i></a>
</div>
</div>
</div><!--end blog-dsc-content-->
</div><!--end blog-card-->

	<?php
	if ( is_single() ) {
		twentyseventeen_entry_footer();
	}
	?>

</div><!-- #post-## -->
