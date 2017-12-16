$(document).ready(function () {
    var type = $('#category-view-type').val();
    var cols = $('#category-grid-cols').val();

    category_view.initView(type, cols);
});
var category_view = {
    'initView' : function (type, cols) {
        category_view.changeView(type, cols);
    },

    'changeView' : function (type, cols) {
        if(type == "grid") {
        }

        if(type == "list") {
        }
    }
}