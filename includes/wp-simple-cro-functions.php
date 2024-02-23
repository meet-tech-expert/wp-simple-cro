<?php
/**
 * @param mixed $msg
 * @param mixed $type='info'
 * 
 * @return [type]
 */
function cro_admin_notice($msg, $type='info') {
    if ( empty( $msg ) ) {
		return;
	}

    add_action('admin_notices', function() use ( $msg, $type ) {
        $type = "notice is-dismissible notice-".$type;
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $type ), esc_html( $msg ) );
    });    
}

