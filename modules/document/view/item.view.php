<?php
/**
 * LIste des documents pour une note
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.4.0
 * @copyright 2017-2018 Eoxia
 * @package Frais.pro
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<tr class="document-item">
	<td class="document-title" >
		<span><?php echo esc_html( $document->data['title'] ); ?></span>
		<div class="document-generation-date" ><?php esc_html_e( 'Generated on', 'frais-pro' ); ?> : <?php echo esc_html( $document->data['date_modified']['rendered']['date_time'] ); ?></div>
	</td>
	<td class="document-summary">
		<?php
		if ( ! empty( $document_checked['mime_type']['ext'] ) ) : ?>
			<span class="document-icon"><i class="fas fa-file fa-fw"></i></span> <?php
			echo esc_html( $document_checked['mime_type']['ext'] );
		else :
			esc_html_e( 'Corrupt file extension', 'frais-pro' );
		endif;
		?>
	</td>
	<td class="document-action" >
	<?php if ( $document_checked['exists'] ) : ?>
		<a href="<?php echo esc_url( $document_checked['link'] ); ?>" class="wpeo-button button-main button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php echo esc_attr( sprintf( __( 'Download %s file', 'frais-pro' ), $document_checked['mime_type']['ext'] ) ); ?>" >
			<i class="button-icon fas fa-download"></i>
		</a>
	<?php else : ?>
		<span class="wpeo-button button-disable button-event button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php echo esc_attr_e( 'File does not exists', 'frais-pro' ); ?>">
			<i class="far fa-file-times fa-lg button-icon" aria-hidden="true"></i>
		</span>
	<?php endif; ?>
	</td>
</tr>
