var mlookup = function (id) {
    console.log('inside mLookup ' + id);
    $comboGrid = $('#' + id);
    //var custom = JSON.parse(decodeURIComponent($comboGrid.attr('lookup')));
    var custom = JSON.parse($comboGrid.data('lookup'));
    console.log(custom);
    /** Required:
     *  idField, textfield, columns, url
     */
    console.log(JSON.parse($comboGrid.data('related')));
    custom.related = {};
    var pattern = /(.*):([^:]*)/;
    var related = JSON.parse($comboGrid.data('related')).split(',')
        .forEach(function (element, index) {
            console.log(element);
            var pair = pattern.exec(element) || Array(element, element, element);
            console.log(pair);
            custom.related[pair[2].toUpperCase()] = pair[1];
        });
    console.log(custom.related);
    custom.queryParams = {};
    console.log(JSON.parse($comboGrid.data('filters')));
    var filters = JSON.parse($comboGrid.data('filters')).split(',').forEach(function (element) {
        custom.queryParams[element] = '';
    });
    var options = {
        mode: 'remote',
        //minLength: 3,
        //fitColumns: false,
        //panelWidth: 300,
        //loadMsg: "Carregando...",
        onSelect: function (index, row) {
            console.log('onselect');
            var pairs = $comboGrid.combogrid('options').related;
            console.log(index);
            console.log(row);
            console.log(pairs);
            for (var key in pairs) {
                console.log(key);
                try {
                    manager.byId(pairs[key]).textbox('setValue', row[key]);
                } catch (e) {
                    manager.byId(pairs[key]).attr('value', row[key]);
                }
                console.log(manager.byId(pairs[key]).attr('value'));
                console.log('aaaa');
            }
//            console.log($comboGrid.combogrid('options').textField);
  //          $comboGrid.combogrid('setValue',  row[$comboGrid.combogrid('options').textField]);
    //        console.log($comboGrid.combogrid('getValue'));
        },
        onBeforeLoad: function () {
            console.log('onbeforeload');
            var lookupOptions = $comboGrid.combogrid('options');
            var minLength = lookupOptions.minLength;
            if ($comboGrid.combogrid('getValue').length < minLength) {
                return false;
            }
        },
        onChange: function (newValue, oldValue) {
            console.log('onchange');

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
            console.log($comboGrid.combogrid('options').queryParams);
        }
    };
    $.extend(options, custom);
    console.log(options);
    $comboGrid.combogrid(options);
};
