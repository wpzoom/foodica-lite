<?php
/**
 * Search form.
 *
 * Uses classes instead of fixed ids, and a unique id per form for the label,
 * so the form can appear more than once on a page (header, Recipe Index,
 * Search widget) without duplicate ids.
 *
 * @package foodica
 */

$foodica_search_id = wp_unique_id( 'foodica-search-' );
?>
<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $foodica_search_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'foodica' ); ?></label>
	<input type="search" class="sb-search-input" placeholder="<?php esc_attr_e( 'Enter your keywords...', 'foodica' ); ?>" name="s" id="<?php echo esc_attr( $foodica_search_id ); ?>" />
	<input type="submit" class="sb-search-submit" value="<?php esc_attr_e( 'Search', 'foodica' ); ?>" />
	<span class="sb-icon-search"></span>
</form>
