var theme = {
    datagrid: function(id, dataJson, eventJson) {
        var data = JSON.parse(dataJson);
        var custom = data.custom;
        //console.log(custom);
        var columns = data.columns;
        //console.log(columns);
        var parseNeeded = false;
        var events = eventJson.events;
console.log(events);

        $.each(columns, function (index, element) {
            var field = columns[index];
            console.log(field.type);
            if (field.type === 'link') {
                element.formatter = function(value,row,index) {
                    var idGrid = $(this)[0].idGrid;
                    var action = $(this)[0].action;
                    var field = $(this)[0].field;
                    var matches = action.match(/#(.*)#/);
                    if (matches[1]) {
                        var idField = matches[1].toUpperCase();
                        var target = row[idField];
                        var action = action.replace(/#(.*)#/,target);
                        var idLink = idGrid + '_' + idField + '_' + target;
                        var maction = new manager.action();
                        var jsAction = maction.getAction(idLink, action);
                        return "<a id='" + idLink + "' onclick=\"" + jsAction + "\">" + value + "</a>";
                    } else {
                        return value;
                    }
                };
            }
            if (field.type === 'control') {
                parseNeeded = true;
                element.formatter = function(value,row,index) {
                    /*
                    var idGrid = $(this)[0].idGrid;
                    //var htmlControl = jQuery(\$id).data(field.idControl);
                    var field = $(this)[0].field;
                    var idControl = $(this)[0].idControl;
                    var matches = htmlControl.match(/#(.*)#/);
                    if (matches[1]) {
                        var target = row[matches[1].toUpperCase()];
                        var htmlControl = htmlControl.replace(/#(.*)#/,target);
                    }
                    var idField = this.getOption('idField').toUpperCase();
                    var idValue = row[idField];
                    var id = idControl + '_' + idValue;
                    var name = idControl + '[' + idValue + ']';
                    var cssClass = idGrid + '_' + idControl;
                    var htmlReplace = $(htmlControl).attr('id',id).attr('name',name).addClass(cssClass);
                    var container = $('<div></div>').append(htmlReplace);
                    // existem eventos associados?
                    if (manager.events[idControl]) {
                        jQuery.each(manager.events[idControl], function(index, event) {
                            event.id = id;
                            manager.registerEvent(event);
                        });
                    }
                    return container.html();
                    */
                   return value;
                };
            }
            if (field.render) {
                element.formatter = function(value,row,index) {
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
                
        function registerChecked(index,row) {
            var checked = $('#' + id).datagrid('getChecked');
            var idField = $('#' + id).datagrid('options').idField;
            var chk = [];
            for (i in checked) {
                chk[chk.length] = checked[i][idField];
            }
            var data = {
                checked: chk, 
                idField: idField 
            };
            console.log(data);
            $('#' + id + '_data').val('json:' + JSON.stringify(data));
        };
        
        custom.onCheck = registerChecked;
        custom.onUncheck = registerChecked;
        custom.onCheckAll = registerChecked;
        custom.onUncheckAll = registerChecked;

        custom.columns = [columns];
        
        for (event in events) {
            custom[event] = events[event];
        }
        
        $('#' + id).datagrid(custom);
        //  $('#' + id).datagrid('load');                
    },
    mlookup: function (id) {
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
                for (var filter in $comboGrid.combogrid('options').queryParams) {
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
    },
    prompt: function (id, dataJson, action1, action2) {
        var data = JSON.parse(dataJson);
        data.show = function() {
            var maction = new manager.action();
            var f1 = maction.getAction('', action1);
            if ((this.type == 'question') || (this.type == 'confirm')) {
                var f2 = maction.getAction('', action2);
                var f = new Function("r", "if (r) { " + f1 + "} else { " + f2 + "}");
                $.messager.confirm(this.title, this.msg, f);
            } else {
                var f = new Function("r", f1);
                $.messager.alert(this.title, this.msg, this.type, f);
            }
        }
        return data;
    }
}
