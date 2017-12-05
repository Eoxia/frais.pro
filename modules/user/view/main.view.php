<?php
/**
 * Vue principale de l'ajout des champs des utilisateurs
 *
 * @package Eoxia\NoteDeFrais
 * @subpackage Views
 *
 * @since 1.0.0
 * @version 1.3.0
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<h2><?php esc_html_e( 'User informations for Frais.pro', 'frais-pro' ); ?></h2>

<table class="form-table">
	<tbody>
		<tr class="user-marque-wrap">
			<th><label for="marque"><?php esc_html_e( 'Brand of the car', 'frais-pro' ); ?></label></th>
			<td><input type="text" name="marque" id="marque" value="<?php echo esc_attr( $user->marque ); ?>" class="regular-text ltr"></td>
		</tr>

		<tr class="user-marque-wrap">
			<th><label for="chevaux"><?php esc_html_e( 'Tax horsepower', 'frais-pro' ); ?></label></th>
			<td>
				<select name="chevaux" id="chevaux">
				<?php

				if ( ! empty( \eoxia\Config_Util::$init['frais-pro']->chevaux ) ) :
					foreach ( \eoxia\Config_Util::$init['frais-pro']->chevaux as $chevaux ) :
						?>
						<option value="<?php echo esc_attr( $chevaux ); ?>" <?php selected( $chevaux, $user->chevaux, true ); ?>><?php echo esc_html( $chevaux ); ?></option>
						<?php
					endforeach;
				endif;
				?>
			</select>
			</td>
		</tr>

		<tr class="user-marque-wrap">
			<th><label for="prixkm"><?php esc_html_e( 'Price per Kilometer', 'frais-pro' ); ?></label></th>
			<td><input type="text" name="prixkm" id="prixkm" value="<?php echo esc_attr( $user->prixkm ); ?>" class="regular-text ltr"></td>
		</tr>

		<tr class="user-marque-wrap">
			<th><label for="marque"><?php esc_html_e( 'Registration document scan', 'frais-pro' ); ?></label></th>
			<td class="eox-note-frais"><?php echo do_shortcode( '[wpeo_upload id="' . $user->id . '" model_name="/note_de_frais/User_Class"]' ); ?></td>
		</tr>

		<?php if ( ( get_current_user_id() !== $user->id ) || ( 1 === get_current_user_id() ) ) : ?>
		<tr class="user-marque-wrap">
			<th><label for="ndf_admin"><?php esc_html_e( 'User is allowed to view all fees sheet', 'frais-pro' ); ?></label></th>
			<td><input type="checkbox" name="ndf_admin" id="ndf_admin" value="1" <?php checked( $user->ndf_admin, true, true ); ?>></td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>
