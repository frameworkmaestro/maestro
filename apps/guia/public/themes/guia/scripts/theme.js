    console.log(manager.version + ' - Theme: MEasyUI');
    
    /* Desabilita o parser automatico do EasyUI (o parser é feito pelo Maestro) */
    $.parser.auto = false;
    /* Define a função de parser - neste tema, o parser do EasyUI */
    manager.externalParse = function (id, onComplete) {
        $.parser.onComplete = onComplete || function () { };
        $.parser.parse(id);
    }

    manager.contentElement = 'centerPane';
    manager.setTemplate('easyui');
    manager.setAdapter('easyui');

jQuery(function ($) {
    /* Remove o loading */
    $("#loader").hide();
});

