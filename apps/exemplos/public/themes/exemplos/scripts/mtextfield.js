/*
 * Customizações necessárias para os controles MTextField
 */

/*
 * Adaptação do formato de datas de EasyUI para DD/MM/YYYY em MCalendarField
 */

$.fn.datebox.defaults.formatter = function(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return d+'/'+m+'/'+y;
};

$.fn.datebox.defaults.parser = function(s){
    if (s != '') {
        var date = s.split('/');
        var y = parseInt(date[2]);
        var m = parseInt(date[1]) - 1;
        var d = parseInt(date[0]);
        var dt = new Date(y,m,d,0,0,0,0);
    } else {
        var dt = new Date();
    }    
    return dt;
};

/*
 * Opções default para o plugin 'jQuery Mask Plugin'
 */
$.jMaskGlobals = {
    nonInput: 'td,span,div', 
    dataMask: true, 
    watchInterval: 300,
    watchInputs: false, 
    watchDataMask: false,
    byPassKeys: [9, 16, 17, 18, 36, 37, 38, 39, 40, 91],
    translation: {
      '0': {pattern: /\d/},
      '9': {pattern: /\d/, optional: true},
      '#': {pattern: /\d/, recursive: true},
      '*': {pattern: /[a-zA-Z0-9]/},
      'A': {pattern: /[a-zA-Z0-9]/},
      'a': {pattern: /[a-zA-Z]/},
      'S': {pattern: /[a-zA-Z]/}
    }
  };
  
