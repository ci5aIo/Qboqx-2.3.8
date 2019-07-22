/**
 * Forked from vendor/elgg/elgg/js/lib/ui.widgets.js
 * Even thought this looks like an async require call, it in fact does not
 * issue an async call to load quebx/widgets, which  is inlined in framework.js.
 * This makes sure quebx.space.q_widgets methods are available for plugins to use,
 * once framework module is loaded.
 * @deprecated 2.1
 */
require(['elgg', 'quebx/widgets'], function (elgg, q_widgets) {

	elgg.provide('quebx.space.q_widgets');

	quebx.space.q_widgets = {
		_notice: function() {
			elgg.deprecated_notice('Don\'t use quebx.space.q_widgets directly. Use the AMD quebx/widgets module', '2.1');
		},
		init: function() {
			quebx.space.q_widgets._notice();
			return q_widgets.init.apply(this, arguments);
		},
		add: function () {
			quebx.space.q_widgets._notice();
			return q_widgets.add.apply(this, arguments);
		},
		move: function () {
			quebx.space.q_widgets._notice();
			return q_widgets.move.apply(this, arguments);
		},
		remove: function () {
			quebx.space.q_widgets._notice();
			return q_widgets.remove.apply(this, arguments);
		},
		collapseToggle: function() {
			quebx.space.q_widgets._notice();
			return q_widgets.collapseToggle.apply(this, arguments);
		},
		setMinHeight: function() {
			quebx.space.q_widgets._notice();
			return q_widgets.setMinHeight.apply(this, arguments);
		}
	};

	elgg.register_hook_handler('init', 'system', q_widgets.init);
});
