jQuery( function($) {
	// эффекты таблиц
	// ************************************
	$('.table').on('click', '.row-clicable', function(){
		window.location = $(this).attr('data-href');
	});

	// ** конструктор цен ** //
	$('#prod_type').change( function() {
		prod_type_change(this);
	});

    $('.btn-calcprice').click( function() {
        btn_calcprice_onclick();
    });

    $(document).ready(function () {
        $('#prod_type').change();
	});

    // автоматическое назначение класса Active элементу меню */
    $(function() {
        // путь текущей страницы
        var pathPage = location.pathname.slice(1);
        var parentUl = $('.navbar-nav a[href*='+pathPage+']').closest('li').addClass('active').parent('ul');
        if (parent.closest('.navbar-nav li').length) {
            parentUl.closest('li').addClass('active');
        }
    });

});

// при изменении вида продукции
// ************************************
function prod_type_change(obj) {
	prod_type = obj.value;

	$("#gr_format_form").attr('hidden',prod_type == '14579' ? false : true);	// бланк
	$("#gr_format_journal").attr('hidden', prod_type == '14580' ? false : true);	// журнал
	$("#gr_num_sheets").attr('hidden', prod_type == '14580' ? false : true);	// журнал
	$("#gr_stitch").attr('hidden', prod_type == '14580' ? false : true);	// журнал
	$("#gr_cover_type").attr('hidden', prod_type == '14580' ? false : true);	// журнал
	$("#gr_num_pages").attr('hidden', prod_type == '14870' ? false : true);	// брошюра
}

// рассчитать цену
function btn_calcprice_onclick(){
    var request_data;

    $("#calc_container").attr('hidden', true);
    $("#calc_error").attr('hidden', true);
    $("#calc_waiting").attr('hidden', false);

    $("#calc_name").html("");
    $("#calc_num").html("");
    $("#calc_full_price").html("");
    $("#calc_price").html("");
    $("#calc_sum").html("");

    request_data = {
        url: '/getprice/',
        type: 'get',
        data: {
            prod_type : $('#prod_type')[0].value,
            paper_type : $('#paper_type')[0].value,
            format : $('#format_journal')[0].value,
            num_sheets : $('#num_sheets')[0].value,
            stitch : $('#stitch')[0].checked ? 1 : 0,
            numering : $('#numering')[0].checked ? 1 : 0,
            cover_type : $('#cover_type')[0].value,
            num : $('#num')[0].value,
            discount : $('#discount')[0].value
        },
    };

    if(request_data) {

        var request = $.ajax(request_data);

        request.done(function (data) {
            $("#calc_waiting").attr('hidden', true);

            // заполняем
            if(data.error){
                $("#calc_error").html(data.last_error_str);
                $("#calc_error").attr('hidden', false);

            } else{
                $("#calc_name").html(data.name);
                $("#calc_num").html(data.num);
                $("#calc_full_price").html(data.full_price.toFixed(3));
                $("#calc_price").html(data.price.toFixed(3));
                $("#calc_sum").html(data.sum.toFixed(2));
                $("#calc_container").attr('hidden', false);
            }
        });
    }

}