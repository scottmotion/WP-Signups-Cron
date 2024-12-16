(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(function () {

		const checkbox_active = $("#signups_cron_field_active_enabled");
		const text_active = $("#text_for_signups_cron_field_active_threshold");
		const input_active = $("#signups_cron_field_active_threshold");

		if (checkbox_active.is(':checked')) {      // document ready
			text_active.removeClass("text-disabled");
			input_active.prop('disabled', false);
		} else {
			text_active.addClass("text-disabled");
			input_active.prop('disabled', true);
		}

		checkbox_active.change(function () {        //event
			if (checkbox_active.is(':checked')) {
				text_active.removeClass("text-disabled");
				input_active.prop('disabled', false);
			} else {
				text_active.addClass("text-disabled");
				input_active.prop('disabled', true);
			}
		});

		const checkbox_pending = $("#signups_cron_field_pending_enabled");
		const text_pending = $("#text_for_signups_cron_field_pending_threshold");
		const input_pending = $("#signups_cron_field_pending_threshold");

		if (checkbox_pending.is(':checked')) {      // document ready
			text_pending.removeClass("text-disabled");
			input_pending.prop('disabled', false);
		} else {
			text_pending.addClass("text-disabled");
			input_pending.prop('disabled', true);
		}

		checkbox_pending.change(function () {        //event
			if (checkbox_pending.is(':checked')) {
				text_pending.removeClass("text-disabled");
				input_pending.prop('disabled', false);
			} else {
				text_pending.addClass("text-disabled");
				input_pending.prop('disabled', true);
			}
		});

	});

})(jQuery);
