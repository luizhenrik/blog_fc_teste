<?php
// Add actions
add_action( 'add_meta_boxes', 'answers_posts_metaboxes' );
add_action( 'save_post', 'save_question_post' );
add_action( 'save_post', 'save_answers_post' );

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
    $fd_answer = $_POST['field_answer_text'];
    $id = stripslashes_deep($post->ID);
    $i = 1;
    foreach ($fd_answer as $fd_item) {
      $answer = stripslashes_deep($fd_item);

      if(isset($answer) && $answer != ''):
        # code...
        $sql = "INSERT INTO $table_name (id_posts, indice_answers, answers) VALUES (%d, %o, %s) ON DUPLICATE KEY UPDATE answers = %s";
        //var_dump($sql . '<br>'); // debug
        $sql = $wpdb->prepare($sql,$id,$i,$answer,$answer);
        //var_dump($sql . '<br>'); // debug
        $wpdb->query($sql);
      else:
        # code...
        $sql = "DELETE FROM $table_name WHERE id_posts = %d and indice_answers = %o";
        //var_dump($sql . '<br>'); // debug
        $sql = $wpdb->prepare($sql,$id,$i);
        //var_dump($sql . '<br>'); // debug
        $wpdb->query($sql);
      endif;

      $i++;
    }

}

// Função para salvar perguntas na tabela wp_enquetes_questions
function save_question_post($post_id)
{
  global $wpdb, $post;

  $table_name = $wpdb->prefix . "enquetes_questions";
  $title = stripslashes_deep(get_the_title($post->ID));
  $id = stripslashes_deep($post->ID);

  $sql = "INSERT INTO $table_name (id_posts, questions) VALUES (%d, %s) ON DUPLICATE KEY UPDATE questions = %s";
  //var_dump($sql . '<br>'); // debug
  $sql = $wpdb->prepare($sql,$id,$title,$title);
  //var_dump($sql . '<br>'); // debug
  $wpdb->query($sql);
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
  $c = 0;

    foreach( $fd_answer as $fd_item ) :
      if ( isset( $fd_item->answers ) && $fd_item->answers != ""  ) :
      $c++;
  ?>
  <div class="field field_type-text">
    <p class="label">
      <label for="id_field_answer_<?php echo $c; ?>">Resposta <?php echo $c; ?></label>
      Digite sua resposta!
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

      jQuery('#answers_posts').addClass('acf_postbox ');

      jQuery('#in-category-5').on('change', function(){
        ischeckedPoll = (jQuery('#in-category-5').is(':checked'));
        ischeckedPoll_for_create_fields_button(ischeckedPoll);
      });


      // Adicionar novo HTML de campo
      jQuery(".inside .add").click(function() {
        count = count + 1;

        var fd = '<div class="field field_type-text"> \n\
                    <p class="label"> \n\
                      <label for="id_field_answer_'+count+'">Resposta '+count+'</label> \n\
                      Digite sua resposta! \n\
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
