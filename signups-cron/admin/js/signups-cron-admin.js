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

		// const checkbox_active = $("#signups_cron_field_active_enabled");
		// const text_active = $("#text_for_signups_cron_field_active_threshold");
		// const input_active = $("#signups_cron_field_active_threshold");

		// const checkbox_pending = $("#signups_cron_field_pending_enabled");
		// const text_pending = $("#text_for_signups_cron_field_pending_threshold");
		// const input_pending = $("#signups_cron_field_pending_threshold");

		// const text_email = $("#text_for_signups_cron_field_send_email_report");
		// const input_email = $("#signups_cron_field_send_email_report");

		// const text_schedule = $("#text_for_signups_cron_field_cron_schedule");
		// const input_schedule = $("#signups_cron_field_cron_schedule");

		// checkbox_active.change(function () {        //event
		// 	if (!checkbox_active.is(':checked') && !checkbox_pending.is(':checked')) {
		// 		text_active.addClass("text-disabled");
		// 		input_active.prop('disabled', true);

		// 		text_pending.addClass("text-disabled");
		// 		input_pending.prop('disabled', true);

		// 		text_email.addClass("text-disabled");
		// 		input_email.prop('disabled', true);

		// 		text_schedule.addClass("text-disabled");
		// 		input_schedule.prop('disabled', true);

		// 	} else if (!checkbox_active.is(':checked')) {
		// 		text_active.addClass("text-disabled");
		// 		input_active.prop('disabled', true);

		// 		text_email.removeClass("text-disabled");
		// 		input_email.prop('disabled', false);

		// 		text_schedule.removeClass("text-disabled");
		// 		input_schedule.prop('disabled', false);
		// 	} else {
		// 		text_active.removeClass("text-disabled");
		// 		input_active.prop('disabled', false);

		// 		text_email.removeClass("text-disabled");
		// 		input_email.prop('disabled', false);

		// 		text_schedule.removeClass("text-disabled");
		// 		input_schedule.prop('disabled', false);
		// 	}
		// });

		// checkbox_pending.change(function () {        //event
		// 	if (!checkbox_active.is(':checked') && !checkbox_pending.is(':checked')) {
		// 		text_active.addClass("text-disabled");
		// 		input_active.prop('disabled', true);

		// 		text_pending.addClass("text-disabled");
		// 		input_pending.prop('disabled', true);

		// 		text_email.addClass("text-disabled");
		// 		input_email.prop('disabled', true);

		// 		text_schedule.addClass("text-disabled");
		// 		input_schedule.prop('disabled', true);

		// 	} else if (!checkbox_pending.is(':checked')) {
		// 		text_pending.addClass("text-disabled");
		// 		input_pending.prop('disabled', true);

		// 		text_email.removeClass("text-disabled");
		// 		input_email.prop('disabled', false);

		// 		text_schedule.removeClass("text-disabled");
		// 		input_schedule.prop('disabled', false);
		// 	} else {
		// 		text_pending.removeClass("text-disabled");
		// 		input_pending.prop('disabled', false);

		// 		text_email.removeClass("text-disabled");
		// 		input_email.prop('disabled', false);

		// 		text_schedule.removeClass("text-disabled");
		// 		input_schedule.prop('disabled', false);
		// 	}
		// });



		// if (checkbox_active.is(':checked')) {      // document ready
		// 	text_active.removeClass("text-disabled");
		// 	input_active.prop('disabled', false);
		// } else {
		// 	text_active.addClass("text-disabled");
		// 	input_active.prop('disabled', true);
		// }

		// Active enabled checkbox changed
		// checkbox_active.change(function () {        //event
		// 	if (checkbox_active.is(':checked')) {
		// 		text_active.removeClass("text-disabled");
		// 		input_active.prop('disabled', false);
		// 	} else {
		// 		text_active.addClass("text-disabled");
		// 		input_active.prop('disabled', true);
		// 	}
		// });

		// if (checkbox_pending.is(':checked')) {      // document ready
		// 	text_pending.removeClass("text-disabled");
		// 	input_pending.prop('disabled', false);
		// } else {
		// 	text_pending.addClass("text-disabled");
		// 	input_pending.prop('disabled', true);
		// }

		// Pending enabled checkbox changed
		// checkbox_pending.change(function () {        //event
		// 	if (checkbox_pending.is(':checked')) {
		// 		text_pending.removeClass("text-disabled");
		// 		input_pending.prop('disabled', false);
		// 	} else {
		// 		text_pending.addClass("text-disabled");
		// 		input_pending.prop('disabled', true);
		// 	}
		// });

	});

})(jQuery);
