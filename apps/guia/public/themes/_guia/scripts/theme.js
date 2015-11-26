require(["dijit/layout/BorderContainer"]);
// elemento que ser� preenchido com o conte�do da p�gina
require(["manager/Core"], function(Core) {
    Core.page.mainElement = 'centerPane';
});

dojo.addOnLoad(function() {
    setTimeout(function hideLoader(){
        dojo.fadeOut({ 
             node: 'loader', 
             duration:100,
             onEnd: function(n){
                        n.style.display = "none";
             }
        }).play();
    }, 250);
});
