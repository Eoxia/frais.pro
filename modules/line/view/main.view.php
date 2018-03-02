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
		<div class="table-cell libelle"><?php esc_html_e( 'Label', 'frais-pro' ); ?></div>
		<div class="table-cell date"><?php esc_html_e( 'Date', 'frais-pro' ); ?></div>
		<div class="table-cell type"><?php esc_html_e( 'Type', 'frais-pro' ); ?></div>
		<div class="table-cell km"><?php esc_html_e( 'Km', 'frais-pro' ); ?></div>
		<div class="table-cell ttc"><?php esc_html_e( 'ATI(€)', 'frais-pro' ); ?></div>
		<div class="table-cell tva"><?php esc_html_e( 'VAT', 'frais-pro' ); ?></div>
		<div class="table-cell status"></div>
		<div class="table-cell action table-end"></div>
	</div>
</div>

<div class="wpeo-table table-flex list-line" data-nonce="<?php echo esc_attr( wp_create_nonce( 'fp_update_line' ) ); ?>" >
<?php
if ( ! empty( $lines ) ) :
	foreach ( $lines as $line ) :
		Line_Class::g()->display( $line, array( 'note_is_closed' => $note_is_closed ) );
	endforeach;
else :
?>
<div class="table-row line notice-info">
	<?php esc_html_e( 'Actually you do not have any line in this note', 'frais-pro' ); ?>
</div>
<?php
endif;
?>
</div>
