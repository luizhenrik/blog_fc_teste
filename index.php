<?php get_header(); ?>
<?php load_template( dirname( __FILE__ ) . '/includes/styles-posts/banner.php' ); ?>
<div class="grid grid--container">
	<div class="row row--no-gutters">
		<?php load_template( dirname( __FILE__ ) . '/includes/styles-posts/listing-posts-card.php' ); ?>
	</div>
</div>
<div class="row row--no-gutters">
	<!-- Posts mixed align full page -->
	<?php load_template( dirname( __FILE__ ) . '/includes/styles-posts/posts-mixed-align-full-pages.php' ); ?>
</div>
<div class="grid grid--container">
	<div class="row row--no-gutters">
		<?php load_template( dirname( __FILE__ ) . '/includes/styles-posts/result-statistics.php' ); ?>
	</div>
</div>
<div class="row row--no-gutters">
	<!-- Posts full page -->
	<?php load_template( dirname( __FILE__ ) . '/includes/styles-posts/posts-full-pages.php' ); ?>
</div>
<?php get_footer(); ?>
