jQuery(document).live('acf/setup_fields', function(e, div){

	// div is the element with new html.
	// on first load, this is the jQuery('#poststuff')
	// on adding a repeater row, this is the tr

  if(jQuery('[name="fields[field_58a6350fb5c8a]"]').length > 0){
    var ischeckedPoll = (jQuery('[name="fields[field_58a6350fb5c8a]"]').is(':checked'));
    jQuery('[name="fields[field_58a6350fb5c8a]"]').on('change', function(){
      ischeckedPoll = (jQuery(this).is(':checked'));
      ischeckedPoll_for_create_fields_button(ischeckedPoll);
    });

    ischeckedPoll_for_create_fields_button(ischeckedPoll);
    onClick_field_create_new_field();
  }
});


function ischeckedPoll_for_create_fields_button(ischeckedPoll)
{
  if(ischeckedPoll){
    jQuery('[name="fields[field_58a6350fb5c8a]"]').closest('.inside').append('<div class="field field-type-button field-create-new-field"><button class="button button-primary button-large">Criar novo campo</button></div>')
  }else{
    jQuery('.inside .field-create-new-field').remove();
  }
}

function onClick_field_create_new_field()
{
  jQuery('.field-create-new-field button').unbind('click').on('click', function(e){
    e.preventDefault();
    var total_acf_field_type_text = jQuery('.field_type-text').length;
    var html =  '<div id="acf-resposta_' + (total_acf_field_type_text + 1) + '" class="field field_type-text field_key-field_answer_' + (total_acf_field_type_text + 1) + ' acf-conditional_logic-show" data-field_name="resposta_' + (total_acf_field_type_text + 1) + '" data-field_key="field_answer_' + (total_acf_field_type_text + 1) + '" data-field_type="text">';
        html += '<p class="label">';
        html += '<label for="acf-field-resposta_' + (total_acf_field_type_text + 1) + '">Resposta ' + (total_acf_field_type_text + 1) + '</label>';
        html += 'Digite sua resposta!';
        html += '</p>';
        html += '<div class="acf-input-wrap">';
        html += '<input type="text" id="acf-field-resposta_' + (total_acf_field_type_text + 1) + '" class="text" name="field_answer[' + (total_acf_field_type_text + 1) + ']" value="" placeholder="Digite sua resposta para esta pergunta!">';
        html += '</div>';
        html += '</div>';

        console.log(html);

    jQuery(html).insertBefore(jQuery(this).parent());
  });
}
