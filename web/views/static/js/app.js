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
});