/* Copyright [2011, 2012, 2013] da Universidade Federal de Juiz de Fora
 * Este arquivo é parte do programa Framework Maestro.
 * O Framework Maestro é um software livre; você pode redistribuí-lo e/ou 
 * modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada 
 * pela Fundação do Software Livre (FSF); na versão 2 da Licença.
 * Este programa é distribuído na esperança que possa ser  útil, 
 * mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL 
 * em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título
 * "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software
 * Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

/**
 * Maestro 2.0 - Manager Action Plugin
 */

(function ($) {
    manager.action = function (elements, options) {
        this.jQuery = $(elements);
        this.elements = elements;
        this.options = jQuery.extend({}, this.defaults, options);
        this._attach();
        this.parseAction();
    };
    manager.action.prototype = {
        version: 'Manager 2.0',
        onPlugginLoad: function () {
        },
        returnThis: function () {
            return this;
        },
        defaults: {
            action: null,
            href: null
        },
        _attach: function () {
            var self = this;
            this.jQuery.each(function (index, element) {
                element.options = $.extend({}, self.defaults, self.parseOptions(element), element.options);
            });
        },
        parseOptions: function (target) {
            return $.extend({}, manager.parser.parseOptions(target,
                    ['action']
                    ), {});
        },
        parseAction: function () {
            var getAction = this.getAction;
            return this.jQuery.each(function (index, element) {
                var opts = element.options;
                if (opts.action) {
                    var id = $(element).attr('id');
                    var action = getAction(id, opts.action);
                    manager.registerEvent({id: id, event: 'click', handler: action, preventDefault: true});
                }
            });
        },
        getAction: function (id, action) {
            if (action === 'POST') {
                return "manager.doPost('" + id + "');";
            }
            var modifier = action.charAt(0);
            if (modifier === '!') {
                return action.substr(1);
            }
            if (modifier === ':') {
                var parts = action.substr(1).split('|');
                var url = window.location.href.replace(/\/index\.php\/(.*)/, "/index.php/" + parts[0]);
                return "manager.doAjaxText('" + url + "','" + parts[2] + "','" + parts[1] + "');";
            }
            if (modifier === '*') {
                var parts = action.substr(1).split('|');
                var url = window.location.href.replace(/\/index\.php\/(.*)/, "/index.php/" + parts[0]);
                return "manager.doAjax('" + url + "'," + parts[2] + ",'" + parts[1] + "');";
            }
            var parts = action.substr(1).split('|');
            var url = window.location.href.replace(/\/index\.php\/(.*)/, "/index.php/" + parts[0]);
            if (modifier === '@') {
                return parts[1] ? "manager.doPost('" + id + "','" + url + "','" + parts[1] + "');" : "manager.doPost('" + id + "','" + url + "');";
            }
            if (modifier === '>') {
                return parts[1] ? "manager.doGet('" + url + "','" + parts[1] + "');" : "manager.doGet('" + url + "');";
            }
            if (modifier === '^') {
                //return "manager.doDialog('" + id + "','" + url + "');";
                var wrapper =  parts[1] ? parts[1] : 'manager-dialog-' + $.md5(url);
                //console.log("manager.doGet('" + url + "', '', '" + wrapper + "');");
                return "manager.doGet('" + url + "', '', '" + wrapper + "');";
            }
            if (modifier === '+') {
                return "manager.doWindow('" + url + "');";
            }
            if (modifier === '=') {
                return "manager.doRedirect('" + url + "');";
            }
            if (modifier === 'd') {
                return "manager.showDialog('" + action.substr(2) + "');";
            }
            if (modifier === 'p') {
                return "manager.showPrompt('" + action.substr(2) + "');";
            }
            if (modifier === 'h') {
                return "manager.showDialog('" + action.substr(2) + "');";
            }
            if (modifier === 'f') {
                var url = window.location.href.replace(/\/index\.php\/(.*)/, "/index.php/" +action.substr(2));
                console.log('****' +  url);
                return "manager.doGetFile('" + url + "');";
            }
            return action;
        },
        exec: function (action, id) {
            var a = this.getAction(id, action);
            (new Function('return ' + a))();
        }
    };

    // add it to the jQuery API
    $.addObject('maction', manager.action);

})(jQuery);
