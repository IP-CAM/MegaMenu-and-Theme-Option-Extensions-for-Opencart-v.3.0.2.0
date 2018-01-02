$(document).ready(function () {
    if(localStorage.getItem('type') == null) {
        var type = $('#category-view-type').val();
        var cols = $('#category-grid-cols').val();

        if(type == "list") {
            category_view.initView(type, cols, 'btn-list');
        }

        if(type == 'grid') {
            category_view.initView(type, cols, 'btn-grid-' + cols);
        }
    } else {
        var type = localStorage.getItem('type');
        var cols = localStorage.getItem('cols');
        var element = localStorage.getItem('element');

        category_view.initView(type, cols, element);
    }
});

var category_view = {
    'initView' : function (type, cols, element) {
        category_view.changeView(type, cols, element);
    },

    'changeView' : function (type, cols, element) {
        if(type == "grid") {
            var column = parseInt(cols);
            if(column == 2) {
                $('#content .item-inner').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12 item-inner');
            }
            if(column == 3) {
                $('#content .item-inner').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-12 col-xs-12 item-inner');
            }
            if(column == 4) {
                $('#content .item-inner').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-12 col-xs-12 item-inner');
            }
            if(column == 5) {
                $('#content .item-inner').attr('class', 'product-layout product-grid col-lg-divide-5 col-md-divide-5 col-sm-12 col-xs-12 item-inner');
            }
        }

        if(type == "list") {
            $('#content .item-inner').attr('class', 'product-layout product-list col-xs-12 item-inner');
        }

        $('.btn-custom-view').removeClass('active');
        $('.' + element).addClass('active');

        localStorage.setItem('type', type);
        localStorage.setItem('cols', cols);
        localStorage.setItem('element', element);
    }
}