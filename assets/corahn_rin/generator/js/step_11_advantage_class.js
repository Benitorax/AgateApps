
/**
 * Advantage immutable object type.
 *
 * @param object
 * @constructor
 * @property {Number} id
 * @property {Number} xp
 * @property {Number} baseValue
 * @property {Number} currentValue
 * @property {Number} bonus
 * @property {boolean} isAdvantage
 * @property {Element} input
 * @property {Element} label
 * @property {Element} indicationInput
 */
var Advantage = function(object) {
    this.id           = !isNaN(parseInt(object.id)) ? parseInt(object.id, 10) : null;
    this.xp           = !isNaN(parseInt(object.xp)) ? parseInt(object.xp, 10) : null;
    this.baseValue    = !isNaN(parseInt(object.baseValue)) ? parseInt(object.baseValue, 10) : null;
    this.currentValue = !isNaN(parseInt(object.currentValue)) ? parseInt(object.currentValue, 10) : null;
    this.bonus        = !isNaN(parseInt(object.bonus)) ? parseInt(object.bonus, 10) : null;
    this.isAdvantage  = !!object.isAdvantage;
    this.input        = object.input || null;
    this.label        = object.label || null;
    this.indicationInput = object.indicationInput || null;
};

Advantage.new = function(object) {
    return Object.freeze(new Advantage(object));
};
