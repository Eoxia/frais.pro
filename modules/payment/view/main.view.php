<?php
/**
 * Formulaire du paiement.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2015-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   GPLv3 <https://spdx.org/licenses/GPL-3.0-or-later.html>
 *
 * @package   Frais_Pro
 *
 * @since     1.5.0
 */

namespace frais_pro;

defined( 'ABSPATH' ) || exit; ?>

<div class="wpeo-form">
	<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
	<input type="hidden" name="action" value="fp_save_payment" />

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Payment date', 'frais-pro' ); ?></span>
		<label class="form-field-container group-date">
			<input type="hidden" class="mysql-date" name="payment_date" value="" />
			<input type="text" class="form-field date" />
			<span class="wpeo-tooltip-event form-field-label-next" aria-label="<?php esc_html_e( 'Get current date', 'frais-pro' ); ?>"><i class="fas fa-calendar-day"></i></span>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Payment type', 'frais-pro' ); ?></span>
		<label class="form-field-container">
			<select id="payment_type" class="form-field" name="payment_type" >
				<option value="bank-transfer"><?php esc_html_e( 'Brank transfer' , 'frais-pro' ); ?></option>
				<option value="cash"><?php esc_html_e( 'Cash' , 'frais-pro' ); ?></option>
				<option value="check"><?php esc_html_e( 'Check' , 'frais-pro' ); ?></option>
				<option value="credit-card"><?php esc_html_e( 'Credit card' , 'frais-pro' ); ?></option>
				<option value="debit-payment-order"><?php esc_html_e( 'Debit payment order' , 'frais-pro' ); ?></option>
			</select>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Payment number (Check/Transfer N°)', 'frais-pro' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="payment_number" />
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Payment comment', 'frais-pro' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="payment_comment" />
		</label>
	</div>

	<div class="form-element amount">
		<span class="form-label"><?php esc_html_e( 'Payment amount', 'frais-pro' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="payment_amount" />
			<span data-amount="<?php echo $note->data['tax_inclusive_amount']; ?>" class="form-field-label-next wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Get amount from note', 'frais-pro' ); ?>"><i class="fas fa-receipt"></i></span>
		</label>
	</div>
</div>
