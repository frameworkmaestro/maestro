/*
 * jQuery.addObject
 * 
 * http://github.com/rpflorence/jQuery.addObject
 *
 * Copyright (c) 2010 Ryan Florence
 * Dual licensed under the MIT and GPL licenses.
 */

jQuery.addObject = function (name, object) {
    jQuery.fn[name] = function (arg) {
        var instance = this.data(name);
        if (typeof arg == 'string') {
            var prop = instance[arg];
            if (typeof prop == 'undefined') {
                prop = instance['wildcard'];
                var returns = prop.apply(instance, arguments);
                return (returns == instance) ? this : returns;
            }
            if (typeof prop == 'function') {
                var returns = prop.apply(instance, Array.prototype.slice.call(arguments, 1));
                return (returns == instance) ? this : returns;
            }
            if (arguments.length == 1) return prop;
            instance[arg] = arguments[1];
            return this;
        }
        if (instance) return instance;
        options = $.extend({}, jQuery.fn[name].defaults, arg);
        this.data(name, new object(this.selector, options));
        return this;
    };
    jQuery.fn[name].defaults = {};
};
