(function ($) {
    $.fn.step = function (options) {
        var settings     = $.extend({
                                        container        : '#stepContainer',
                                        stepSelector     : '.step',
                                        templateContainer: '#stepContent',
                                        nextSelector     : '#stepBtnNext',
                                        prevSelector     : '#stepBtnPrevious',
                                        cancelSelector   : '#stepBtnCancel',
                                        beforeStep       : null,
                                        afterStep        : null,
                                        onComplete       : null,
                                        onSubmit         : null,
                                        onCancel         : null,
                                        onNextClick      : null,
                                        onPrevClick      : null,
                                        onStepChange     : null,
                                        validateStep     : null
                                    }, options);
        var currentIndex = 0;
        var dataStore    = {};
        var $card        = this;
        var $body        = $card.find(settings.container);
        var $steps       = $(settings.templateContainer).find(settings.stepSelector);
        function render(index) {
            $body.html($steps.eq(index).clone());
            if (dataStore[index]) {
                for (const [name, val] of Object.entries(dataStore[index])) {
                    $body.find(`[name="${name}"]`).val(val);
                }
            }
            if (typeof settings.afterStep === 'function') {
                settings.afterStep(index, plugin.getCurrentStepData());
            }
            if (typeof settings.onStepChange === 'function') {
                settings.onStepChange(index);
            }
        }
        function save(index) {
            const inputs     = $body.find('input, select, textarea');
            dataStore[index] = {};
            inputs.each(function () {
                const name = $(this).attr('name');
                if (name) dataStore[index][name] = $(this).val();
            });
        }
        function canProceed(fromIndex, toIndex) {
            if (typeof settings.beforeStep === 'function') {
                if (settings.beforeStep(fromIndex, toIndex, plugin.getCurrentStepData()) === false) return false;
            }
            if (typeof settings.validateStep === 'function') {
                if (settings.validateStep(fromIndex, plugin.getCurrentStepData()) === false) return false;
            }
            return true;
        }
        const plugin = {
            start             : function () {
                currentIndex = 0;
                dataStore    = {};
                render(currentIndex);
                if (typeof settings.onStepChange === 'function') {
                    settings.onStepChange(currentIndex); // <--- burada tetikliyoruz
                }
            },
            next              : function () {
                if (typeof settings.onNextClick === 'function') {
                    if (settings.onNextClick(currentIndex) === false) return;
                }
                save(currentIndex);
                if (currentIndex < $steps.length - 1 && canProceed(currentIndex, currentIndex + 1)) {
                    currentIndex++;
                    render(currentIndex);
                }
                else if (currentIndex === $steps.length - 1) {
                    if (typeof settings.validateStep === 'function') {
                        if (settings.validateStep(currentIndex, plugin.getCurrentStepData()) === false) return;
                    }
                    save(currentIndex);
                    if (typeof settings.onComplete === 'function') {
                        settings.onComplete(plugin.getData());
                    }
                    if (typeof settings.onSubmit === 'function') {
                        settings.onSubmit(plugin.getData());
                    }
                }
            },
            prev              : function () {
                if (typeof settings.onPrevClick === 'function') {
                    if (settings.onPrevClick(currentIndex) === false) return;
                }
                save(currentIndex);
                if (currentIndex > 0 && canProceed(currentIndex, currentIndex - 1)) {
                    currentIndex--;
                    render(currentIndex);
                }
            },
            goTo              : function (index) {
                if (index < 0 || index >= $steps.length) return;
                if (!canProceed(currentIndex, index)) return;
                save(currentIndex);
                currentIndex = index;
                render(currentIndex);
            },
            cancel            : function () {
                if (typeof settings.onCancel === 'function') {
                    settings.onCancel();
                }
            },
            getData           : function () {
                save(currentIndex);
                return Object.values(dataStore).reduce((acc, stepData) => {
                    return Object.assign(acc, stepData);
                }, {});
            },
            getCurrentStepData: function () {
                const inputs   = $body.find('input, select, textarea');
                const stepData = {};
                inputs.each(function () {
                    const name = $(this).attr('name');
                    if (name) stepData[name] = $(this).val();
                });
                return stepData;
            },
            reset             : function () {
                dataStore    = {};
                currentIndex = 0;
                render(currentIndex);
            },
            destroy           : function () {
                $card.off('click', settings.nextSelector);
                $card.off('click', settings.prevSelector);
                $card.off('click', settings.cancelSelector);
                $body.html('');
                dataStore = {};
            }
        };
        $card.on('click', settings.nextSelector, function () {
            plugin.next();
        });
        $card.on('click', settings.prevSelector, function () {
            plugin.prev();
        });
        $card.on('click', settings.cancelSelector, function () {
            plugin.cancel();
        });
        return plugin;
    };
})(jQuery);
