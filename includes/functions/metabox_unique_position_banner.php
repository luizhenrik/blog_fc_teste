<?php
add_action('acf/save_post', 'unique_position_banner', 1);
function unique_position_banner($post_id) {
  // Pega todos os metaboxes do post que estou salvando
  $this_fields = get_field_objects();

  // Argumentos para a query de verificação de posts com posicionamento igual
  $args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'category__not_in' => array( 5 ),
    // don't include the current post
    'post__not_in' => array($post_id),
    'meta_query' => array(
      array(
        'key' => $this_fields['index_banner']['name'],
        'value' => $this_fields['index_banner']['value']
      )
    )
  );

  // Inicio da query para acumular os posts
  $query = new WP_Query($args);
  if (count($query->posts)){

    //Loop para atulizar campo por campo de cada post
    while( $query->have_posts() ) : $query->the_post();

      //Vars
      global $post;
      $fields = get_field_objects($post->ID);
      $field_index_banner_name = $fields['index_banner']['name'];
      $field_destaque_name = $fields['destaque']['name'];
      $field_index_banner_value = $fields['index_banner']['value'];
      $field_destaque_value = $fields['destaque']['value'];

      // 1° condição verifica se o valor do select dos post é igual ao do post que estou
      // e se o checkbox esta selecionado se ambos for verdadeiros entra

      // 2° condição verifica se o valor do select dos post é igual ao do post que estou
      // e se o checkbox nao esta selecionado se ambos for verdadeiros entra

      if($field_index_banner_value == $this_fields['index_banner']['value'] && $field_destaque_value == 1){
        update_field($field_destaque_name, 0, $post->ID);
        update_field($field_index_banner_name, null, $post->ID);
      }elseif($field_index_banner_value == $this_fields['index_banner']['value'] && $field_destaque_value == 0){
        update_field($field_index_banner_name, null, $post->ID);
      }

    endwhile;
  }

}
