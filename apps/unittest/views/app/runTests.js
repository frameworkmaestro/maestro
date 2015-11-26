String.prototype.endsWith = function (suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
};
var runTests = function () {
    try {
        //console.log($.parseHTML("<span>aa</span>")[0]);
        //return;
        var xhr = new XMLHttpRequest();
        xhr.previous_text = '';
        var previousLen = 0;

        xhr.onerror = function () {
            alert("[XHR] Fatal Error.");
        };
        xhr.onreadystatechange = function () {
            try {
                if (xhr.readyState == 4) {
                    //End
                }
                else if (xhr.readyState > 2) {
                    var new_response = xhr.responseText.substring(previousLen);
                    var result = new_response.trim();
                    if(result) {
                        $('#centerPane').append(result);
                        console.log(result);
                    }
                    previousLen = xhr.responseText.length;
                }
            }
            catch (e) {
                $('#divProgress').progressbar("setValue", e.message);
            }
        };
        xhr.open("GET", "runTests", true);
        xhr.send();
    }
    catch (e) {
        alert("[XHR REQUEST] Exception: " + e);
    }
};