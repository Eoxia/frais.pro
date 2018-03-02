<?php
/**
 * Affichage de la checkbox.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><div class="table-cell line-association-selection">
	<input type="checkbox" value="<?php echo esc_attr( $line->data['id'] ); ?>" name="line_to_affect[]" />
</div>
