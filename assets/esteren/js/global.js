window._enableJsComponents = null;

(function($, d, Materialize){
    "use strict";

    function enableJsComponents(context) {
        Materialize.AutoInit(context);
    }

    // Manage the "disable tags" cookie CNIL requirement
    var button = d.querySelector('button.disable_tags');

    if (button) {
        button.addEventListener('click', function(e){
            if (e.target.tagName.toLowerCase() === 'button' && e.target.className.match('disable_tags')) {
                d.cookie = "disable_tags=1";
                e.target.innerHTML = 'OK';
            }
        });
    }

    enableJsComponents(d.body);

    window._enableJsComponents = enableJsComponents;
})(jQuery, document, M);
