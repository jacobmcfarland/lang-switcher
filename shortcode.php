function trpc_language_switcher( $atts ){
	ob_start();

	global $TRP_LANGUAGE;

	$shortcode_attributes = shortcode_atts( array(
		'display' => 0,
	), $atts );

	$trp = TRP_Translate_Press::get_trp_instance();
	$trp_languages = $trp->get_component( 'languages' );
	$trp_settings = $trp->get_component( 'settings' );
	$settings = $trp_settings->get_settings();
	$url_converter = $trp->get_component( 'url_converter' );

	

	if ( current_user_can(apply_filters( 'trp_translating_capability', 'manage_options' )) ){
		$languages_to_display = $settings['translation-languages'];
	}else{
		$languages_to_display = $settings['publish-languages'];
	}
	$published_languages = $trp_languages->get_language_names( $languages_to_display );
	$current_language = array();
	$other_languages = array();

	foreach( $published_languages as $code => $name ) {
		if( $code == $TRP_LANGUAGE ) {
			$current_language['code'] = $code;
			$current_language['name'] = $name;
		} else {
			$other_languages[$code] = $name;
		}
	}

?>
<div class="trp-language-switcher trp-language-switcher-container" data-no-translation <?php echo ( isset( $_GET['trp-edit-translation'] ) && $_GET['trp-edit-translation'] == 'preview' ) ? 'data-trp-unpreviewable="trp-unpreviewable"' : '' ?>>
    <div class="trp-ls-shortcode-current-language">
        <a href="#" id="nav-first-item" class="trp-ls-shortcode-disabled-language trp-ls-disabled-language" title="<?php echo esc_attr( $current_language['name'] ); ?>" onclick="event.preventDefault()">
			<?php echo $current_language['name']; // WPCS: ok. ?>
			
		</a>
    </div>
    <div class="trp-ls-shortcode-language">
    <?php foreach ( $other_languages as $code => $name ){
        ?>
        <a href="<?php echo esc_url( $url_converter->get_url_for_language($code, false) ); ?>" title="<?php echo esc_attr( $name ); ?>">
            <?php echo $name; // WPCS: ok. ?>
        </a>

    <?php } ?>
    </div>
    <script type="application/javascript">
        // some javascript if needed.
    </script>
</div>
<?php

	return ob_get_clean();
}

add_shortcode('custom-language-switcher', 'trpc_language_switcher');
