/**
 * theme.js
 * 
 * Cada tema deve ter um script theme.js, que faz as inicializações 
 * e carrega os estilos e scripts específicos do tema
 */

// Obtem a URL base do tema
var baseURL = manager.loader.getBase('theme.js');
console.log(manager.version + ' - Theme in: ' + baseURL);

// Carrega os estilos usados pelo tema
manager.loader.loadCss(baseURL + 'scripts/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css');
manager.loader.loadCss(baseURL + 'scripts/lib/bootstrap-switch-master/css/bootstrap3/bootstrap-switch.min.css');
manager.loader.loadCss(baseURL + 'scripts/lib/jquery-easyui-1.4/themes/default/easyui.css');
manager.loader.loadCss(baseURL + 'scripts/lib/jquery-easyui-1.4/themes/icon.css');
manager.loader.loadCss(baseURL + 'scripts/lib/jointJS/joint.nojquery.css');
manager.loader.loadCss(baseURL + 'style.css');

// Carrega os scripts externos usados pelo tema
manager.loader.loadJs(baseURL + 'scripts/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js');
manager.loader.loadJs(baseURL + 'scripts/lib/jquery-easyui-1.4/jquery.easyui.min.js');
manager.loader.loadJs(baseURL + 'scripts/lib/jquery-easyui-1.4/locale/easyui-lang-pt_BR.js');
manager.loader.loadJs(baseURL + 'scripts/lib/jquery-easyui-1.4/jquery.easyui.patch.js');
manager.loader.loadJs(baseURL + 'scripts/lib/bootstrapvalidator-dist-0.5.3/dist/js/bootstrapValidator.js');
manager.loader.loadJs(baseURL + 'scripts/lib/bootstrapvalidator-dist-0.5.3/dist/js/language/pt_BR.js');
manager.loader.loadJs(baseURL + 'scripts/lib/bootstrap-switch-master/js/bootstrap-switch.min.js');
manager.loader.loadJs(baseURL + 'scripts/lib/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js');
manager.loader.loadJs(baseURL + 'scripts/lib/jquery.hotkeys-master/jquery.hotkeys.js');
manager.loader.loadJs(baseURL + 'scripts/lib/bootstrap-wysiwyg-master/bootstrap-wysiwyg.js');
manager.loader.loadJs(baseURL + 'scripts/lib/jointJS/joint.nojquery.js');
manager.loader.loadJs(baseURL + 'scripts/lib/jointJS/joint.shapes.frame.js');
manager.loader.loadJs(baseURL + 'scripts/lib/jquery.md5.js');

// Carrega os scripts usados pelo painter.php
//manager.loader.loadJs(baseURL + 'scripts/mtextfield.js');
//manager.loader.loadJs(baseURL + 'scripts/mlookup.js');
//manager.loader.loadJs(baseURL + 'scripts/mdatagrid.js');
manager.loader.loadJs(baseURL + 'scripts/extensions.js');
manager.loader.loadJs(baseURL + 'scripts/controls.js');

// Desabilita o parser automatico do EasyUI (o parser é feito pelo Maestro)
$.parser.auto = false;

// Define a função de parser externo - neste tema, é o parser do EasyUI 
manager.externalParse = function (id, onComplete) {
    $.parser.onComplete = onComplete || function () { };
    $.parser.parse(id);
}

// Define o elemento default para o conteudo obtido via Ajax-GET 
manager.contentElement = 'centerPane';

// Define o template e o adapter a serem usados
//manager.setTemplate('easyui');
//manager.setAdapter('easyui');

jQuery(function ($) {
    // Remove a mensagem de loading
    $("#loader").hide();
});

