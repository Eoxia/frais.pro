<?php
/**
 * Affichage du tableau ainsi que la ligne pour ajouter une ligne de note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Eoxia/NodeDeFrais
 * @subpackage LigneDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><li class="dropdown-item action-delete"
	data-message-delete="<?php esc_html_e( 'Are you sure you want to dissociate this line', 'frais-pro' ); ?>"
	data-id="<?php echo esc_attr( $line->id ); ?>"
	data-parent-id="<?php echo esc_attr( $line->parent_id ); ?>"
	data-action="<?php echo esc_attr( 'fp_dissociate_line_from_note' ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_dissociate_line_from_note' ) ); ?>" ><i class="dropdown-icon far fa-unlink fa-fw"></i> <?php esc_html_e( 'Dissociate from note', 'frais-pro' ); ?></li>
