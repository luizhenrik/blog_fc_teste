<section class="row row--no-gutters">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <article class="listing-post-single col col--no-gutters col--xs-12 col--sm-12 col--md-12 col--lg-12 col--xl-12">
  		<header class="post-header--default">
  				<figure class="thumb thumb--xs-2-1 thumb--sm-2-1 thumb--md-2-1 thumb--lg-2-1 thumb--xl-2-1">
            <?php the_post_thumbnail( 'all-images', array( 'class' => 'children' ) ); ?>
  					<figcaption>
  						<span><?php echo get_the_date('d');?></span>
  						<span><?php echo get_the_date('M');?></span>
  					</figcaption>
  				</figure>
  		</header>
			<main class="post-main--default post-main--single">
				<h2><?php single_post_title(); ?></h2>
        <?php the_content(); ?>
			</main>
  	<footer class="post-footer--default">
      <?php
      $categories = get_categories( array(
        'orderby' => 'name',
        'parent'  => 0
      ) );

      foreach ( $categories as $category ) {
        $cat_url = esc_url( get_category_link( $category->term_id ) );
        $cat_name = esc_html( $category->name );
        echo '<a href="' . $cat_url . '">#' . $cat_name . '</a>';
      }
      ?>
  	</footer>

  </article>
  <?php
    endwhile;
    else:
  ?>
    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
  <?php endif; ?>
</section>
