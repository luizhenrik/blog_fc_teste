<?php
add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );
add_filter( 'the_content', 'img_p_class_content_filter' ,20);

function img_p_class_content_filter($content) {
    // assuming you have created a page/post entitled 'debug'
    $content = preg_replace("/(<p[^>]*)(\>.*)(\<img.*)(<\/p>)/im", "\$1 class='content-img-wrap'\$2\$3\$4", $content);

    return $content;
}

function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}
