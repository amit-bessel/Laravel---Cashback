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

<div id="post-<?php the_ID(); ?>" class="blog-detail-sec">
	<?php
	if ( is_sticky() && is_home() ) :
		echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
	endif;
	?>


<div class="blog-detail-content">

<div class="blog-detail-title">
<a href="<?php echo site_url('/blog/'); ?>" class="backto-bloglist-link"><i class="fas fa-arrow-left"></i></a>
<h2><?php echo the_title(); ?></h2>
</div>

<div class="blog-detail-img-holder">

	<div class="blog-detail-cat-name">
		<?php 
			$category_detail=get_the_category();
				foreach($category_detail as $cd){
				echo '<span>';
				echo $cd->cat_name;
				echo '</span>';
			}
		?>
		
	</div>

<?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
</div>

<div class="blog-detail-sm-info">
<div class="blog-date"><i class="fas fa-clock"></i> <?php echo get_the_date(); ?></div>
<div class="blog-comnt-count"><i class="fas fa-comment-alt"></i> <?php echo comments_number(); ?></div>
</div>

  
<div class="blog-detail-dsc-text">
		<?php
		/* translators: %s: Name of current post */
		//the_content();
       
		the_content();

		/*wp_link_pages( array(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );*/
		?>
</div>




</div><!--end blog-card-->

	<?php
	if ( is_single() ) {
		//twentyseventeen_entry_footer();
	}
	?>

</div><!-- #post-## -->

<script type="text/javascript">
function checkEmty(idVal){
	var checkVal = $('#'+idVal).val();
	if(checkVal==''){
		if($('#error'+idVal).length>0){}
		else{
           if(idVal == 'subject')
           console.log($('#'+idVal).parents('p[class^="comment-form"]').length );

			$('#'+idVal).parents('p[class^="comment-form"]').append('<span class="custom_error" id="error'+idVal+'">This field is required</span>');
		}
	}
	else
		$('#'+idVal).next('.custom_error').remove();
}

$( window ).load(function() {
		if($('#submit').length > 0){
			//alert();
           $('#submit').attr('type','button');
        }
    $('#submit').on('click' , function(){			

				checkEmty('author');
				checkEmty('email');
				checkEmty('subject');
				checkEmty('comment');

		if($('.custom_error').length == 0){
			$('#submit').attr('type','submit');
		}

	});

	$('#commentform input, #commentform textarea').on('keyup',function(){
		checkEmty($(this).attr("id"));
		
	})

});


</script>