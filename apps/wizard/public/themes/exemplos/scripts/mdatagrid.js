var mdatagrid = function (id) {
    console.log('inside mDatagrid');
    $dataGrid = $('#' + id);

    //var custom = JSON.parse(decodeURIComponent($dataGrid.attr('custom')));
    //var columns = JSON.parse(decodeURIComponent($dataGrid.attr('fields')));
    
    var custom = JSON.parse($dataGrid.data('custom'));
    custom.data = $dataGrid.data('data');
    var columns = JSON.parse($dataGrid.data('fields'));
    
    console.log(custom);
    console.log(columns);
    
    var parseNeeded = false;
    
    $.each(columns, function (index, element) {
        var field = columns[index];
        console.log(field.type);
        if (field.type === 'check') {
            element.formatter = function(value,row,index) {
                var idGrid = $(this)[0].idGrid;
                var $id = '#' + idGrid;
                return jQuery($id).mgrid('getFieldCheck', $(this)[0].field, row);
            };
        }
        if (field.type === 'link') {
            element.formatter = function(value,row,index) {
                var idGrid = $(this)[0].idGrid;
                var $id = '#' + idGrid;
                return jQuery($id).mgrid('getFieldLink', $(this)[0].field, row, value, $(this)[0].action);
            };
        }
        if (field.type === 'control') {
            parseNeeded = true;
            element.formatter = function(value,row,index) {
                var idGrid = $(this)[0].idGrid;
                var $id = '#' + idGrid;
                var htmlControl = jQuery($id).data(field.idControl);
                return jQuery($id).mgrid('getFieldControl', $(this)[0].field, row, value, htmlControl, $(this)[0].idControl);
            };
        }
        if (field.render) {
            element.formatter = function(value,row,index) {
                //return window[$(this)[0].render](value,row,index);
                return window[field.render](value,row,index);
            }
        }
        if (field.options) {
            element.formatter = function(value,row,index) {
                var options = $(this)[0].options;
                return options[value];
            };
        }
    });
    custom.columns = [columns];
    var options = {};
    options.onBeforeLoad = function(param) {
            console.log('before load');
            return true;
    };
    options.pageNumber = 1;
    if (parseNeeded) {
        options.onLoadSuccess = function() {
            $.parser.parse($('div.datagrid-body'));
            $(this).mgrid('onAfterLoad');
        };
    }

    $.extend(options, custom);
    console.log(options);
    $dataGrid.datagrid(options);
};