$(function() {
	$('div[id^="book-"]').click(function () {
		var item = $(this).find(".book-price");
		if (item.hasClass("selected")) {
			item.removeClass("selected");
			item.html(item.attr("data-price"));
			$('#sum').html((parseFloat($('#sum').html()) - parseFloat(item.html())).toFixed(2));
		} else {
			item.addClass("selected");
			$('#sum').html((parseFloat($('#sum').html()) + parseFloat(item.html())).toFixed(2));
        	item.html('<i class="fa fa-check-circle"></i>');
		}
    }); 
});