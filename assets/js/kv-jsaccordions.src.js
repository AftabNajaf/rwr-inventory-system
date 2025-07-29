function initializeAccordions() {
    $(".accordion-item").each(function (key, value) {
        var panelID = $(this).data("panel-id");
        if (panelID) {
            var $accordionItemPanel;
            if (panelID.indexOf("#") !== -1) {
                $accordionItemPanel = $(panelID);
            } else {
                $accordionItemPanel = $("#" + panelID);
            }

            if ($(this).hasClass("active")) {
                $accordionItemPanel.addClass("active");
            }
        }
    });

    $(".accordion-panel").each(function () {
        if (!$(this).hasClass("active")) {
            $(this).css("height", "0px");
        } else {
            $(this).css("height", $(this).prop("scrollHeight") + "px");
        }
    });
}

$(document).ready(function () {

    initializeAccordions();

    $("body").on("click", ".accordion-item", function (e) {
        var panelID = $(this).data("panel-id");
        if (panelID) {
            var $accordionItemPanel;
            if (panelID.indexOf("#") !== -1) {
                $accordionItemPanel = $(panelID);
            } else {
                $accordionItemPanel = $("#" + panelID);
            }

            if (!$accordionItemPanel.hasClass("active")) {
                var accrPanelHeight = $accordionItemPanel[0].scrollHeight;
                $accordionItemPanel.addClass("active");

                $accordionItemPanel.animate(
                    {
                        height: accrPanelHeight + "px"
                    },
                    250,
                    function () {
                        $accordionItemPanel.css("height", "auto");
                        $accordionItemPanel.css("min-height", accrPanelHeight + "px");
                    });
                $(this).addClass("active");
            } else {
                $accordionItemPanel.removeClass("active");

                $accordionItemPanel.animate(
                    {
                        height: "0px"
                    },
                    250,
                    function () {
                        $accordionItemPanel.css("min-height", "0px");
                    });
                $(this).removeClass("active");
                $accordionItemPanel.css("min-height", "0px");

            }
        }
    });
});