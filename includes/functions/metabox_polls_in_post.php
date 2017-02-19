<?php
// Add actions
add_action( 'add_meta_boxes', 'answers_posts_metaboxes' );
add_action( 'save_post', 'save_answers_post' );
add_action( 'save_post', 'save_poll_in_home' );

// Criação da caixa do metabox
function answers_posts_metaboxes()
{
    add_meta_box(
        'answers_posts',
        __( 'Respostas', 'myplugin_textdomain' ),
        'answers_posts_metaboxes_fields',
        'post');
}


// Função para salvar perguntas na tabela wp_enquetes_answers
function save_answers_post($post_id)
{

  global $wpdb, $post;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( !isset( $_POST['dynamicMeta_noncename'] ) )
        return;

    if ( !wp_verify_nonce( $_POST['dynamicMeta_noncename'], plugin_basename( __FILE__ ) ) )
        return;

    $table_name = $wpdb->prefix . "enquetes_answers";
    $fd_answer = isset($_POST['field_answer_text']) ? $_POST['field_answer_text'] : '';
    $id = stripslashes_deep($post->ID);
    $i = 1;

    foreach ($fd_answer as $fd_item) :
      $answer = stripslashes_deep($fd_item);

      if(isset($answer) && $answer != ''):
        $existing_answer = "SELECT * FROM $table_name WHERE id_posts = $post->ID and indice_answers = $i";
        $exist = $wpdb->get_results($existing_answer);
        if($exist == null):
          $sql = "INSERT INTO $table_name (id_posts, indice_answers, answers) VALUES (%d, %o, %s)";
          //var_dump($sql . '<br>'); // debug
          $sql = $wpdb->prepare($sql,$id,$i,$answer,$answer);
          //var_dump($sql . '<br>'); // debug
          $wpdb->query($sql);
        else:
          $sql = "UPDATE $table_name SET id_posts=%d,indice_answers=%o,answers=%s WHERE id_posts = $post->ID and indice_answers = $i";
          //var_dump($sql . '<br>'); // debug
          $sql = $wpdb->prepare($sql,$id,$i,$answer,$answer);
          //var_dump($sql . '<br>'); // debug
          $wpdb->query($sql);
        endif;

      else:
        # code...
        $sql = "DELETE FROM $table_name WHERE id_posts = %d and indice_answers = %o";
        //var_dump($sql . '<br>'); // debug
        $sql = $wpdb->prepare($sql,$id,$i);
        //var_dump($sql . '<br>'); // debug
        $wpdb->query($sql);
      endif;

      //Registrando valores do metabox no post
      update_post_meta($id, 'answers_polls', $fd_answer);

      $i++;
    endforeach;
}

// Função para registrar valor do chk de poll_in_home e remover em outros posts com o mesmo valor
function save_poll_in_home($post_id)
{
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

  if ( !isset( $_POST['dynamicMeta_noncename'] ) )
      return;

  if ( !wp_verify_nonce( $_POST['dynamicMeta_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  // Enviar valores desse post

  $post_poll_in_home = $_POST['poll_in_home'];
  update_post_meta($post_id, 'poll_in_home', $post_poll_in_home);

  // Recuperar valor enviado
  $get_poll_in_home = get_post_meta($post_id, 'poll_in_home');
  var_dump($get_poll_in_home[0]);
  // Argumentos para a query de verificação de posts com posicionamento igual
  $args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    // don't include the current post
    'post__not_in' => array($post_id),
    'meta_query' => array(
      array(
        'key' => 'poll_in_home',
        'value' => $get_poll_in_home[0]
      )
    )
  );

  // Inicio da query para acumular os posts
  $query = new WP_Query($args);
  if (count($query->posts)):

    //Loop para atulizar campo por campo de cada post
    while( $query->have_posts() ) : $query->the_post();

      //Vars
      global $post;
      update_post_meta($post->ID, 'poll_in_home', null);

    endwhile;

  endif;
}

// Pesquisa no banco se há algum valor de respostas
function answers_posts_metaboxes_fields()
{
  global $wpdb, $post;
  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'dynamicMeta_noncename' );
  ?>
  <?php

  // Resgata todos os valores no banco
  $table_name = $wpdb->prefix . "enquetes_answers";
  $sql = "SELECT * FROM $table_name WHERE id_posts = $post->ID";
  $fd_answer = $wpdb->get_results($sql);
  $get_poll_in_home = get_post_meta($post->ID, 'poll_in_home');
  $c = 0;
  ?>
  <div class="field field_type-text">
    <p class="label" style="text-align: right;">
      <label for="poll_in_home"><input type="checkbox" id="poll_in_home" name="poll_in_home"<?php echo ($get_poll_in_home != null && isset($get_poll_in_home[0]) && $get_poll_in_home[0] != null) ? ' checked' : ''; ?>> Deseja que apareça essa enquete na home do blog?</label>
    </p>
  </div>
  <?php
    foreach( $fd_answer as $fd_item ) :
      if ( isset( $fd_item->answers ) && $fd_item->answers != ""  ) :
      $c++;
  ?>
  <div class="field field_type-text">
    <p class="label">
      <label for="id_field_answer_<?php echo $c; ?>">Digite sua resposta!</label>
    </p>
    <div class="input-wrap">
      <input type="text" id="id_field_answer_%1$s" class="text" name="field_answer_text[<?php echo $c; ?>]" value="<?php echo $fd_item->answers ?>" placeholder="Digite sua resposta para esta pergunta!">
    </div>
    <div class="field remove">
      <button type="button" class="button button-primary button-large" style="width: 100%; margin-top: 10px;">Remover</button>
    </div>
  </div>

  <?php
      endif;
    endforeach;
  ?>
    <div class="add field field-type-button field-create-new-field">
      <button class="button button-primary button-large">Nova Resposta</button>
    </div>

    <script>
    var $ =jQuery.noConflict();

    function ischeckedPoll_for_create_fields_button(ischeckedPoll)
    {
      if(ischeckedPoll){
        jQuery('#answers_posts').show();
      }else{
        jQuery('#answers_posts').hide();

      }
    }

    jQuery(document).ready(function() {
      var count = <?php echo $c; ?>;
      var ischeckedPoll = (jQuery('#in-category-5').is(':checked'));
      ischeckedPoll_for_create_fields_button(ischeckedPoll);


      jQuery('#in-category-5').on('change', function(){
        ischeckedPoll = (jQuery('#in-category-5').is(':checked'));
        ischeckedPoll_for_create_fields_button(ischeckedPoll);
      });

      // Adicionar novo HTML de campo
      jQuery(".inside .add").click(function() {
        count = count + 1;

        var fd = '<div class="field field_type-text"> \n\
                    <p class="label"> \n\
                      <label for="id_field_answer_'+count+'">Digite sua resposta!</label> \n\
                    </p> \n\
                    <div class="input-wrap"> \n\
                      <input type="text" id="id_field_answer_'+count+'" class="text" name="field_answer_text['+count+']" value="" placeholder="Digite sua resposta para esta pergunta!"> \n\
                    </div> \n\
                  </div> \n\
                  <div class="field remove"> \n\
                    <button type="button" class="button button-primary button-large" style="width: 100%; margin-top: 10px;" >Remove</button> \n\
                  </div> \n\
                  </br>';

      jQuery(fd).insertBefore(jQuery(this));

      return false;

    });

    // function para remover resposta
    jQuery(".inside .remove").live('click', function() {
      jQuery(this).parent().hide();
      jQuery(this).parent().find('input[type="text"]').val('');
    });

  });
</script>
<?php

}
