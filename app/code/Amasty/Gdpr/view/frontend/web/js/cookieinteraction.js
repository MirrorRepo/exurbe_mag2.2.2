define([
    'jquery',
    'mage/cookies'
], function ($) {
    'use strict';

    return function (data) {
        var interaction = data.websiteInteraction,
            cookie = $.mage.cookies.get('am_allow_cookies');

        if (cookie === null && interaction == 1 && !$('.cms-cookie-policy').length) {
            $('.page-wrapper').css({
                "pointer-events": "none",
                "-webkit-user-select": "none",
                "-moz-user-select": "none",
                "-ms-user-select": "none",
                "user-select": "none",
                "height": "100%",
                "overflow": "hidden",
                "opacity": "0.1"
            });
        }
    }
});