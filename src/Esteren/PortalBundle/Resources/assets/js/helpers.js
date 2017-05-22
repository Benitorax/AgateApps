// Here only some polyfills and helpers useful for customization

/**
 * Re-implementation of toggleClass method inspired by jQuery.
 * Toggles one single class at a time.
 *
 * @param toggledClass
 * @returns {Element}
 */
Element.prototype.toggleClass = function (toggledClass) {
    var classes = this.className.toString().trim();

    toggledClass = toggledClass.trim();

    if (classes.match(toggledClass)) {
        this.className = classes.replace(toggledClass, '');
    } else {
        this.className = classes + toggledClass;
    }

    return this;
};

if (!String.prototype.trim) {
    // Méthode .trim() pour toutes les chaînes de caractères
    String.prototype.trim = function(){
        return this.replace(/^\s+|\s+$/g, '');
    };
}

/**
 * Merges two objects recursively
 * @param targetObject object
 * @param sourceObject object
 * @returns {*}
 */
function mergeRecursive (targetObject, sourceObject) {
    var property;
    if (!targetObject) { targetObject = {}; }
    if (!sourceObject) { sourceObject = {}; }
    for (property in sourceObject) {
        if (sourceObject.hasOwnProperty(property)) {
            try {
                targetObject[property] =
                    (sourceObject[property].constructor == Object)
                        ? this.mergeRecursive(targetObject[property], sourceObject[property])
                        : sourceObject[property];
            }
            catch(e) { targetObject[property] = sourceObject[property]; }
        }
    }
    return targetObject;
}