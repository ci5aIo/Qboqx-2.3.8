/*
 * Forked from jQuery Dropdown: A simple dropdown plugin
 * @license: MIT license: http://opensource.org/licenses/MIT
 *
 */
if (jQuery) (function ($) {

    $.extend($.fn, {
        jqDropdown: function (method, data) {

            switch (method) {
                case 'show':
                    show(null, $(this));
                    return $(this);
                case 'hide':
                    hide();
                    return $(this);
                case 'attach':
                    return $(this).attr('data-qboqx-dropdown', data);
                case 'detach':
                    hide();
                    return $(this).removeAttr('data-qboqx-dropdown');
                case 'disable':
                    return $(this).addClass('qboqx-dropdown-disabled');
                case 'enable':
                    hide();
                    return $(this).removeClass('qboqx-dropdown-disabled');
            }

        }
    });

    function show(event, object) {

        var trigger = event ? $(this) : object,
            jqDropdown = $(trigger.attr('data-qboqx-dropdown')),
            isOpen = trigger.hasClass('qboqx-dropdown-open');

        // In some cases we don't want to show it
        if (event) {
            if ($(event.target).hasClass('qboqx-dropdown-ignore')) return;

            event.preventDefault();
            event.stopPropagation();
        } else {
            if (trigger !== object.target && $(object.target).hasClass('qboqx-dropdown-ignore')) return;
        }
        hide();

        if (isOpen || trigger.hasClass('qboqx-dropdown-disabled')) return;

        // Show it
        trigger.addClass('qboqx-dropdown-open');
        jqDropdown
            .data('qboqx-dropdown-trigger', trigger)
            .show();

        // Position it
        position();

        // Trigger the show callback
        jqDropdown
            .trigger('show', {
                jqDropdown: jqDropdown,
                trigger: trigger
            });

    }


    function pack(event, object) {

        var trigger = event ? $(this) : object,
            jqDropdown = $(trigger.attr('data-pallet')),
            isOpen = trigger.hasClass('qboqx-dropdown-open');
        console.log('object: '+object);

        // In some cases we don't want to show it
        if (event) {
            if ($(event.target).hasClass('qboqx-dropdown-ignore')) return;

            event.preventDefault();
            event.stopPropagation();
        } else {
            if (trigger !== object.target && $(object.target).hasClass('qboqx-dropdown-ignore')) return;
        }
        hide();

        if (isOpen || trigger.hasClass('qboqx-dropdown-disabled')) return;

        // Show it
        trigger.addClass('qboqx-dropdown-open');
        jqDropdown
            .data('qboqx-dropdown-trigger', trigger)
            .show();

        // Position it
        position();

        // Trigger the show callback
        jqDropdown
            .trigger('show', {
                jqDropdown: jqDropdown,
                trigger: trigger
            });

    }

    function hide(event) {

        // In some cases we don't hide them
        var targetGroup = event ? $(event.target).parents().addBack() : null;

        // Are we clicking anywhere in a qboqx-dropdown?
        if (targetGroup && targetGroup.is('.qboqx-dropdown')) {
            // Is it a qboqx-dropdown menu?
            if (targetGroup.is('.qboqx-dropdown-menu')) {
                // Did we click on an option? If so close it.
                if (!targetGroup.is('A')) return;
            } else {
                // Nope, it's a panel. Leave it open.
                return;
            }
        }

        // Trigger the event early, so that it might be prevented on the visible popups
        var hideEvent = jQuery.Event("hide");

        $(document).find('.qboqx-dropdown:visible').each(function () {
            var jqDropdown = $(this);
            jqDropdown
                .hide()
                .removeData('qboqx-dropdown-trigger')
                .trigger('hide', { jqDropdown: jqDropdown });
        });

        if(!hideEvent.isDefaultPrevented()) {
            // Hide any qboqx-dropdown that may be showing
            $(document).find('.qboqx-dropdown:visible').each(function () {
                var jqDropdown = $(this);
                jqDropdown
                    .hide()
                    .removeData('qboqx-dropdown-trigger')
                    .trigger('hide', { jqDropdown: jqDropdown });
            });

            // Remove all qboqx-dropdown-open classes
            $(document).find('.qboqx-dropdown-open').removeClass('qboqx-dropdown-open');
        }
    }

    function position() {

        var jqDropdown = $('.qboqx-dropdown:visible').eq(0),
            trigger = jqDropdown.data('qboqx-dropdown-trigger'),
            hOffset = trigger ? parseInt(trigger.attr('data-horizontal-offset') || 0, 10) : null,
            vOffset = trigger ? parseInt(trigger.attr('data-vertical-offset') || 0, 10) : null,
//@EDIT 2018-06-11 - SAJ - receive fixed positioning values
    		hPosition = trigger ? parseInt(trigger.attr('data-horizontal-fixed') || 0, 10) : null,
    	    vPosition = trigger ? parseInt(trigger.attr('data-vertical-fixed') || 0, 10) : null;

        if (jqDropdown.length === 0 || !trigger) return;

        // Position the qboqx-dropdown relative-to-parent...
        if (jqDropdown.hasClass('qboqx-dropdown-relative')) {
            jqDropdown.css({
                left: jqDropdown.hasClass('qboqx-dropdown-anchor-right') ?
                    trigger.position().left - (jqDropdown.outerWidth(true) - trigger.outerWidth(true)) - parseInt(trigger.css('margin-right'), 10) + hOffset :
                    trigger.position().left + parseInt(trigger.css('margin-left'), 10) + hOffset,
                top: jqDropdown.hasClass('qboqx-dropdown-anchor-top') ?
                	 trigger.position().top + vOffset: 
                     trigger.position().top + trigger.outerHeight(true) - parseInt(trigger.css('margin-top'), 10) + vOffset
            });
        } else {
            // ...or relative to document
            jqDropdown.css({
                left: jqDropdown.hasClass('qboqx-dropdown-anchor-right') ?
                    trigger.offset().left - (jqDropdown.outerWidth() - trigger.outerWidth()) + hOffset : trigger.offset().left + hOffset,
                top: trigger.offset().top + trigger.outerHeight() + vOffset
            });
        }
//@EDIT 2018-06-11 - SAJ - override positioning if fixed positioning values received
//      2018-07-10 - SAJ - commented out.  needs more work.  causes unadjusted menu to not position horizontally
/*        if (hPosition != null || hPosition === 0){
        	jqDropdown.css({
                left: hPosition
        	});
        }*/
    	if (vPosition){
        	jqDropdown.css({
        		top: vPosition
        	});
    	}
    }

    $(document).on('click.qboqx-dropdown', '[data-qboqx-dropdown]', show);
    $(document).on('click.qboqx-dropdown', hide);
    $(window).on('resize', position);

})(jQuery);