<section class="result-statistics col col--xs-12 col--sm-12 col--md-12 col--lg-12 col--xl-12">
<?php
		$polls = array(
	    'post_type' => 'post',
	    'post_status' => 'publish',
			'meta_key' => 'poll_in_home',
			'orderby' => 'meta_value',
			'order' => 'ASC',
	    'meta_query' => array(
	      array(
	        'key' => 'poll_in_home',
	        'value' => 'on'
	      )
	    )
	  );
		$pollsQuery = new WP_Query($polls);
	  if (count($pollsQuery->posts)):

	    //Loop para atulizar campo por campo de cada post
	    while( $pollsQuery->have_posts() ) : $pollsQuery->the_post();

	      //Vars
	      global $post;
?>
  <article class="post-link row row--no-gutters">
    <header class="post-main--default col col--no-gutters col--xs-12 col--sm-12 col--md-12 col--lg-6 col--xl-6 bg-transparent">
      <h3><?php the_title(); ?></h3>
      <p><?php the_excerpt(); ?></p>
      <a href="<?php echo get_category_link(5); ?>" class="button bg-black color-white">Ver mais pesquisas</a>
    </header>
    <main class="col col--no-gutters col--xs-12 col--sm-12 col--md-12 col--lg-6 col--xl-6 bg-transparent">
      <ul class="searching-percent-progress">
        <?php
          $table_name_answers = $wpdb->prefix . "enquetes_answers";
          $result_answers = "SELECT * FROM $table_name_answers WHERE id_posts = $post->ID";
          $result_all_answers = $wpdb->get_results($result_answers);
          $total_answers = count($result_all_answers);
          $total_votados = 0;
          for ($i=0; $i < $total_answers; $i++) {
            # code...
            $total_votados = $total_votados + $result_all_answers[$i]->votes;
          }
          $total_votados = ($total_votados == 0) ? 1 : $total_votados;

          foreach ($result_all_answers as $poll):
            $votes = ($poll->votes / $total_votados) * 100;
        ?>
        <li>
          <div class="range">
            <span data-percent="<?php echo $votes; ?>" style="width: <?php echo $votes; ?>%;"></span>
          </div>
          <p><strong><?php echo $poll->answers; ?></strong></p>
        </li>
        <?php
          endforeach;
        ?>
      </ul>
    </main>
  </article>
<?php
      endwhile;
    endif;
?>
</section>
