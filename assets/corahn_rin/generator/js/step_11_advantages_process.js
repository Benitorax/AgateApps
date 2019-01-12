(function ($, d) {

    // Depends on the "Advantage" class.
    // Depends on all functions for this specific step.

    if (d.getElementById('generator_11_advantages')) {

        // Vars
        var $labelsCollection = $('.change_char_advantage');
        var xpElement = d.getElementById('xp');
        var advantagesList = {};
        var disadvantagesList = {};
        var numberOfAdvantages = 0;
        var numberOfDisadvantages = 0;

        // Initialize the two arrays.
        // Allows us to only work with memory and not always with loops through DOM...
        for (var i = 0, l = $labelsCollection.length; i < l; i++) {
            var input = $labelsCollection[i].querySelector('input');
            var element = getAdvantageFromInput(input, $labelsCollection[i]);
            var elementId = parseInt(element.id, 10);
            // Push these changes into memory array.
            if (element.isAdvantage) {
                advantagesList[elementId] = element;
                ++numberOfAdvantages;
            } else {
                disadvantagesList[elementId] = element;
                ++numberOfDisadvantages;
            }
        }
        // End initialize

        // Process event listeners.
        // If we're here, input DOM attributes are already validated, so no more checks.
        $labelsCollection.on('click', function(){
            var currentInput = this.querySelector('input');
            var currentAdvantage = getAdvantageFromInput(currentInput, this);
            var currentAdvantageId = parseInt(currentAdvantage.id, 10);
            var currentValue = parseInt(currentInput.value, 10);
            var experience = 100;
            var i, currentXp, virtualValueToTest, gainedWithDisadvantages;

            if (isNaN(currentValue)) {
                throw 'Incorrect value for input.';
            }

            // Handle groups of advantages that cannot be selected together

            // Case "Allies"
            if ([1, 2, 3].indexOf(currentAdvantageId) >= 0) {
                for (i = 1; i <= 3; i++) {
                    if (currentAdvantageId !== i) {
                        updateAdvantageValue(advantagesList[i], 0);
                    }
                }
            }

            // Case "Financial ease"
            if ([4, 5, 6, 7, 8].indexOf(currentAdvantageId) >= 0) {
                for (i = 4; i <= 8; i++) {
                    if (currentAdvantageId !== i) {
                        updateAdvantageValue(advantagesList[i], 0);
                    }
                }
            }

            // Calculate from disadvantages.
            experience = gainOrSpendExperience(currentAdvantageId, experience, disadvantagesList);

            // Keep the "gain" in memory to check later if it's superior to 80.
            gainedWithDisadvantages = experience - 100;

            // Calculate from advantages.
            experience = gainOrSpendExperience(currentAdvantageId, experience, advantagesList);

            virtualValueToTest = currentValue + 1;
            if (
                (currentAdvantageId === 50 && virtualValueToTest > 3)
                || (currentAdvantageId !== 50 && virtualValueToTest > 2)
                || (currentAdvantage.bonus === 0 && virtualValueToTest >= 2)
            ) {
                virtualValueToTest = 0;
            }

            currentXp = calculateXpFromAdvantage(currentAdvantage, virtualValueToTest);

            if (experience + currentXp < 0 || (!currentAdvantage.isAdvantage && gainedWithDisadvantages + currentXp > 80)) {
                return;
            }

            updateAdvantageValue(currentAdvantage);
            updateIndicationsDisplay();

            experience += currentXp;

            xpElement.innerHTML = experience;
        });
    }
})(jQuery, document);
