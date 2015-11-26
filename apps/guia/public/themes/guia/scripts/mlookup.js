var mlookup = function (id) {
    console.log('inside mLookup');
    $comboGrid = $('#' + id);
    var custom = JSON.parse(decodeURIComponent($comboGrid.attr('lookup')));
    /** Required:
     *  idField, textfield, columns, url
     */
    custom.related = {};
    var related = $comboGrid.attr('related').split(',')
        .forEach(function (element, index) {
            var pair = element.split(':');
            custom.related[pair[1].toUpperCase()] = pair[0];
        });
    custom.queryParams = {};
    var filters = $comboGrid.attr('filters').split(',').forEach(function (element) {
        custom.queryParams[element] = '';
    });
    var options = {
        mode: 'remote',
        minLength: 3,
        fitColumns: false,
        panelWidth: 300,
        loadMsg: "Carregando...",
        onSelect: function (index, row) {
            var pairs = $comboGrid.combogrid('options').related;
            for (var key in pairs) {
                try {
                    $('#' + pairs[key]).textbox('setValue', row[key]);
                } catch (e) {
                    $('#' + pairs[key]).attr('value', row[key]);
                }
            }
        },
        onBeforeLoad: function () {
            var lookupOptions = $comboGrid.combogrid('options');
            var minLength = lookupOptions.minLength;
            if ($comboGrid.combogrid('getValue').length < minLength) {
                return false;
            }
        },
        onChange: function (newValue, oldValue) {
            var minLength = $comboGrid.combogrid('options').minLength;
            if (newValue.length < minLength) {
                $comboGrid.combogrid('hidePanel');
            } else {
                $comboGrid.combogrid('showPanel');
            }
            //Can't change queryParams at onBeforeLoad, have to do it sometime before
            for(var filter in $comboGrid.combogrid('options').queryParams){
                try {
                    $comboGrid.combogrid('options').queryParams[filter] = $('#' + filter).textbox('getValue');
                } catch (e) {
                    $comboGrid.combogrid('options').queryParams[filter] = $('#' + filter).attr('value');
                }
            }
        }
    };
    $.extend(options, custom);
    $comboGrid.combogrid(options);
};