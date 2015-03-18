$(function() {
	$('div[id^="book-"]').click(function () {
		var item = $(this).find(".book-price");
		if (item.hasClass("selected")) {
			item.removeClass("selected");
			item.html('<span style="font-size: 19px">S$ </span>' + item.attr("data-price"));
			var sum = (parseFloat($('#sum').html()) - parseFloat(item.attr("data-price"))).toFixed(2);
			if (sum > 0) {
				$('#checkout').removeAttr('disabled');
			} else {
				$('#checkout').attr('disabled', 'disabled');
			}
			$('#sum').html(sum);
		} else {
			item.addClass("selected");
			var sum = (parseFloat($('#sum').html()) + parseFloat(item.attr("data-price"))).toFixed(2);
			if (sum > 0) {
				$('#checkout').removeAttr('disabled');
			} else {
				$('#checkout').attr('disabled', 'disabled');
			}
			$('#sum').html(sum);
        	item.html('<i class="fa fa-check-circle"></i>');
		}
    }); 

    $('#checkout').click(function () {
    	$('div[id^="book-"]').each(function () {
    		var id = $(this).attr('id').substr(5);
    		if (is_selected(id)) {
    			$('#order-' + id).show();
    			$('#checkbox-' + id).attr('checked', true);
    		}
    	});

    	var sum = parseFloat($('#sum').html());
    	$('#modal-sum').html((sum * 1.07).toFixed(2));
    });
});

function is_selected(id) {
	var item = $('#book-' + id).find(".book-price");
	console.log(item);
	if (item.hasClass("selected")) {
		return true;
	} else {
		return false;
	}
}