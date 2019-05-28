$(document).ready(function(){

    setTimeout(function(){
        $("body").removeClass("body_load");
    }, 500);

    var isMobile = $(window).width() <= 640;

    //#-href
    $(document).on("click", "a[href^='#']", function(e){
        e.preventDefault();
        var id = $(this).attr("href");
        if(id != "#" && $(id).length > 0){
            $("body,html").animate({"scrollTop": $(id).offset().top}, 1000);
        }
    });
    //end

    //drop-down
    $(".js-drop_down").each(function(){
        $(this).find(".js-drop_down-content").each(function(){
            $(this).attr("data-height", $(this).height());
            $(this).height(0);
        })
    })
    $("body").on("click", ".js-drop_down-btn", function(e){
        e.preventDefault();
        var $parent = $(this).parents(".js-drop_down").first();
        if($parent.length > 0){
            if($(this).hasClass("active")){
                $parent.find(".js-drop_down-content").removeClass("active");
                $parent.find(".js-drop_down-content").height(0);
            }
            else{
                $parent.find(".js-drop_down-content").addClass("active");
                $parent.find(".js-drop_down-content").height($parent.find(".js-drop_down-content").attr("data-height"));
            }
        }
        if($(this).hasClass("active")){
            $(this).removeClass("active");
        }
        else{
            $(this).addClass("active");
        }
    })
    $(document).on("click", function(e){
        if($(e.target).closest(".js-drop_down-btn").length > 0) return;
        if($(e.target).closest(".js-drop_down-content").length > 0) return;
        $(".js-drop_down-btn").removeClass("active");
        $(".js-drop_down-content").removeClass("active");
        $(".js-drop_down-content").height(0);
    })
    //end

    //popup
    $("body").on("click", "[data-popup-open]", function(e){
        e.preventDefault();
        var $target = $($(this).attr("data-popup-open"));
        if($target.length > 0){
            $(".popup").hide();
            $("body").css("overflow", "hidden");
            $target.fadeIn();
            if($target[0].hasAttribute("data-animate")){
                $target.find(".js-popup_content").addClass("animate");
            }
        }
    })
    $("body").on("click", "[data-popup-close]", function(e){
        e.preventDefault();
        var $popup = $(this).parents(".popup").first();
        $("body").css("overflow", "");
        $popup.hide();
        $popup.find(".js-popup_content").removeClass("animate");
    })
    $(document).click(function(event){
        if($(event.target).closest(".js-popup_content").length > 0) return;
        if($(event.target).closest("[data-popup-open]").length > 0) return;
        $("body").css("overflow", "");
        $(".popup .popup-content").removeClass("animate");
        $(".popup").hide();
    })
    //end

    //tool-tip
    $("body").on("click", ".js-tool_tip", function(e){
        e.preventDefault();
        var $target = $($(this).attr("data-target")),
            $parent = $(this).parent();
        $parent.find(".js-tool_tip-content").css({
            opacity: 0,
            zIndex: -1,
            left: -10000
        });
        $target.css({
            top: $(this).position().top - $target.outerHeight() - 10,
            left: $(this).position().left + ($(this).outerWidth() - $target.outerWidth())/2,
            opacity: 1,
            zIndex: 3
        });
    })
    $(document).on("click", function(e){
        if($(e.target).closest(".js-tool_tip-content").length > 0) return;
        if($(e.target).closest(".js-tool_tip").length > 0) return;
        $(".js-tool_tip-content").css({
            opacity: 0,
            zIndex: -1,
            left: -10000
        });
    })
    //end

    //toggle-class
    $("body").on("click", ".js-toggle-class", function(e){
        e.preventDefault();
        var $target = $($(this).attr("data-target")),
            $parent = $(this).parent(),
            ctx = this;
        if($target.length > 0){
            if(!!$(this).attr("data-class_delete")){
                $target.removeClass($(this).attr("data-class_delete"));
            }
            if(!!$(this).attr("data-class_add")){
                $target.addClass($(this).attr("data-class_add"));
            }
        }
        $parent.find(".js-toggle-class").removeClass("active");
        $(this).addClass("active");
        setTimeout(function(){
            obSlider.reInit($(ctx).attr("data-target"));
        }, 1000);
    })
    $("body").on("click", ".js-toggle", function(e) {
        e.preventDefault();
        if(!!$(this).attr("data-class")){
            var strClass = $(this).attr("data-class"),
                $target = $($(this).attr("data-target"));
            if($target.length == 0) $target = $(this);
            if($target.hasClass(strClass)){
                $target.removeClass(strClass);
            }
            else{
                $target.addClass(strClass)
            }
        }
    })
    //end

    //fixed aside
    if($(".js-aside").length > 0 && !isMobile){
        var obAsideInfo = {
                height: $(".js-aside").outerHeight(),
                top: $(".js-aside").offset().top,
                left: $(".js-aside").offset().left - 15 //scroll panel in fixed style
            },
            obNewCss = {},
            fixedTopCoord = 0;
        if($(".js-fixed").length > 0){
            fixedTopCoord = $(".js-fixed").outerHeight();
        }
        console.log(obAsideInfo);
        $(document).scroll(function(){
            if($(this).scrollTop() >= (obAsideInfo.top - fixedTopCoord) && $(this).scrollTop() <= ($(".js-footer").offset().top - obAsideInfo.height)){
                console.log(obAsideInfo);
                obNewCss = {
                    position: "fixed",
                    top: fixedTopCoord,
                    left: obAsideInfo.left,
                    width: $(".js-aside").outerWidth()
                }
            }
            else{
                obNewCss = {
                    position: "relative",
                    top: "auto",
                    left: "auto",
                    width: "auto"
                }
            }
            $(".js-aside").css(obNewCss);
        })
    }
    //end

    //fixed
    $(".js-fixed").each(function(){
        $(this).attr("data-top", $(this).offset().top);
    })
    $(document).scroll(function(){
        var topCoord,
            $parent = null;
        $(".js-fixed").each(function(){
            topCoord = parseFloat($(this).attr("data-top"));
            $parent = $(this).parent();
            var $this = $(this);
            if($(document).scrollTop() >= topCoord){
                $parent.css("height", $parent.height());
                $(this).addClass("fixed");
            }
            else{
                $(this).removeClass("fixed");
                $parent.css("height", "");
            }
        })
    })
    //end

    //tumbler
    $("body").on("click", ".js-tumbler [data-content]", function(){
        var $wrapper = $(this).parents(".js-tumbler").first();
        if($wrapper.length > 0){
            var $content = $wrapper.find("[data-content]").first(),
                arValues = $content.attr("data-target").split(","),
                arPositions = $content.attr("data-positions").split(","),
                curValue = $content.attr("data-value"),
                key = null;
            if(arValues.length > 0){
                $wrapper.find(curValue).prop("checked", false);
                $wrapper.find(curValue).removeAttr("checked");
                if(arValues[0] == curValue){
                    curValue = arValues[1];
                    key = 1;
                }
                else{
                    curValue = arValues[0];
                    key = 0;
                }
                $wrapper.find(curValue).prop("checked", true);
                $wrapper.find(curValue).attr("checked", "checked");
                $content.attr("data-position", arPositions[key]);
                $content.attr("data-value", curValue);
            }
        }
    })
    //end

    //mobile menu
    $("body").on("click", ".js-mobile_menu-item", function(e){
        if(!$(this).hasClass("active")){
            e.preventDefault();
            var $parent = $(this).parents(".js-mobile_menu").first();
            $parent.addClass("active");
            $parent.find(".js-mobile_menu-item").removeClass("active");
            $(this).addClass("active");
        }
        else{
            location.href = $(this).attr("href");
        }
    })
    $("body").on("click", ".js-mobile_menu-back", function(e){
        e.preventDefault();
        var $parent = $(this).parents(".js-mobile_menu").first();
        $parent.removeClass("active");
        $parent.find(".js-mobile_menu-item").removeClass("active");
    })
    //end

    //slider
    if(typeof obSlider == "object"){
        obSlider.init();
    }
    //end

    //tabs
    if(typeof obTabs == "object"){
        obTabs.init();
    }
    //end

    //animate input
    if(typeof obAnimateInput == "object"){
        obAnimateInput.init();
    }
    //end

})

//bitrix js
BX.ready(function(){

    BX.showWait = function (node) {
        BX.lastNode = node;
        BX.addClass(BX(node), "loading");
    };

    BX.closeWait = function (node) {
        if (!node) {
            node = BX.lastNode;
        }
        BX.removeClass(BX(node), "loading");
    }

})
//end