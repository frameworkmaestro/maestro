/**
 * theme.js
 * 
 * Cada tema deve ter um script theme.js, que faz as inicializações 
 * e carrega os estilos e scripts específicos do tema
 */

// Obtem a URL base do tema
var baseURL = manager.loader.getBase('theme.js');
manager.contentElement = 'centerPane';
manager.externalParse = function (id, onComplete) {
}