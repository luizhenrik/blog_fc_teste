<div class="banner row row--no-gutters">
	<?php
		$bannerArgs = array(
	    'post_type' => 'post',
	    'post_status' => 'publish',
			'meta_key' => 'index_banner',
			'orderby' => 'meta_value',
			'order' => 'ASC',
	    'meta_query' => array(
	      array(
	        'key' => 'index_banner',
	        'value' => '',
					'compare' => '!=',
	      )
	    )
	  );
		$bannerQuery = new WP_Query($bannerArgs);
	  if (count($bannerQuery->posts)):

	    //Loop para atulizar campo por campo de cada post
	    while( $bannerQuery->have_posts() ) : $bannerQuery->the_post();

	      //Vars
	      global $post;
				if(get_field( "index_banner" ) == '1' || get_field( "index_banner" ) == '2'){
					$class_banner__thumbs = "banner--thumbs col col--no-gutters col--xs-12 col--sm-6 col--md-6 col--lg-6 col--xl-6";
					$class_thumb__dimensions = "thumb thumb--xs-4-3 thumb--sm-2-1 thumb--md-2-1 thumb--lg-2-1 thumb--xl-2-1";
				}else{
					$class_banner__thumbs = "banner--thumbs col col--no-gutters col--xs-12 col--sm-6 col--md-6 col--lg-3 col--xl-3";
					$class_thumb__dimensions = "thumb thumb--xs-4-3 thumb--sm-2-1 thumb--md-2-1 thumb--lg-4-3 thumb--xl-4-3";
				}
	?>
		<div class="<?php echo $class_banner__thumbs; ?>">
			<a href="<?php echo get_permalink( $post->ID ); ?>" class="<?php echo $class_thumb__dimensions; ?>">
				<figure class="children">
					<?php the_post_thumbnail( 'all-images' ); ?>
					<figcaption>
						<h4><?php echo $post->post_title; ?></h4>
					</figcaption>
				</figure>
			</a>
		</div>
	<?php
		endwhile;
		endif;
	?>

</div>
