<?php
/**
 * Affichage du tableau ainsi que la ligne pour ajouter une ligne de note de frais.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Eoxia/Frais.pro
 * @subpackage LigneDeFrais
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><div class="wpeo-table table-flex list-line-header">
	<div class="table-row table-header">
		<div class="table-cell image"><?php esc_html_e( 'Picture', 'frais-pro' ); ?></div>
		<div class="table-cell date"><?php esc_html_e( 'Date', 'frais-pro' ); ?></div>
		<div class="table-cell libelle"><?php esc_html_e( 'Label', 'frais-pro' ); ?></div>
		<div class="table-cell type"><?php esc_html_e( 'Type', 'frais-pro' ); ?></div>
		<div class="table-cell km"><?php esc_html_e( 'Km', 'frais-pro' ); ?></div>
		<div class="table-cell ttc"><?php esc_html_e( 'ATI(€)', 'frais-pro' ); ?></div>
		<div class="table-cell tva"><?php esc_html_e( 'VAT', 'frais-pro' ); ?></div>
		<div class="table-cell status"></div>
		<div class="table-cell action table-end"></div>
	</div>
</div>

<div class="wpeo-table table-flex list-line">
<?php
if ( ! empty( $lines ) ) :
	foreach ( $lines as $line ) :
		Line_Class::g()->display( $line );
	endforeach;
endif;
?>
</div>
