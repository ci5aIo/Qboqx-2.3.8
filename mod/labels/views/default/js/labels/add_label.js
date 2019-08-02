define(['elgg', 'jquery', 'elgg/widgets'], function(elgg, $, labels) {

	$(document).on('click', '#label_manager_labels_select li.elgg-state-available', labels.add);
	/* Moved to jot_form_elements.js
	$(document).on('keyup', 'input.LabelsSearch__input___3BARDmFr', function() {
		var cid        = $(this).data('cid');
		var $container = $('.BoqxLabelsPicker__Vof1oGNB[data-cid='+cid+']');
		var $items = $container.find('.SmartListSelector__child___zbvaMzth');
		var q = $(this).val();
		console.log('cid: '+cid);
		console.log('input: '+q);		

		if (q === "") {
			$items.show();
		} else {
			$items.hide();
			$items.filter(function () {
				console.log($(this).text().toUpperCase());
				console.log($(this).text().toUpperCase().indexOf(q.toUpperCase()));
				return $(this).text().toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}).show();
		}
	});*/
});
