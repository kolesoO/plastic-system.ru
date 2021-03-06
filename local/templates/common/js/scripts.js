$(document).ready(function() {
    setTimeout(function() {
        $("body").removeClass("body_load")
    }, 500);
    var b = $(window).width() <= 640;
    $(document).on("click", "a[href^='#']", function(f) {
        f.preventDefault();
        var g = $(this).attr("href");
        if (g != "#" && $(g).length > 0) {
            $("body,html").animate({
                scrollTop: $(g).offset().top
            }, 1000)
        }
    });
    $(".js-drop_down").each(function() {
        $(this).find(".js-drop_down-content").each(function() {
            $(this).attr("data-height", $(this).height());
            $(this).height(0)
        })
    });
    $("body").on("click", ".js-drop_down-btn", function(g) {
        g.preventDefault();
        var f = $(this).parents(".js-drop_down").first(),
            h;
        if (f.length > 0) {
            if ($(this).hasClass("active")) {
                f.find(".js-drop_down-content").removeClass("active");
                f.find(".js-drop_down-content").height(0)
            } else {
                f.find(".js-drop_down-content").addClass("active");
                f.find(".js-drop_down-content").height(f.find(".js-drop_down-content").attr("data-height"))
            }
        }
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            h = $(this).attr("data-content")
        } else {
            h = $(this).attr("data-active_content");
            $(this).addClass("active")
        }
        if (!!h && h.length > 0) {
            $(this).text(h)
        }
    });
    $(document).on("click", function(f) {
        if ($(f.target).closest(".js-drop_down-btn").length > 0) {
            return
        }
        if ($(f.target).closest(".js-drop_down-content").length > 0) {
            return
        }
        $(".js-drop_down-btn").removeClass("active");
        $(".js-drop_down-content").removeClass("active");
        $(".js-drop_down-content").height(0)
    });
    var e = function(h, i, g) {
        i.preventDefault();
        var f = $($(h).attr(g));
        if (f.length > 0) {
            $(".popup").hide();
            $("body").css("overflow", "hidden");
            f.fadeIn();
            if (f[0].hasAttribute("data-animate")) {
                f.find(".js-popup_content").addClass("animate")
            }
        }
    };
    $("body").on("click", "[data-popup-open]", function(f) {
        e(this, f, "data-popup-open")
    });
    $("body").on("mouseover", "[data-popup-hover]", function(f) {
        e(this, f, "data-popup-hover")
    });
    $("body").on("click", "[data-popup-close]", function(f) {
        f.preventDefault();
        var g = $(this).parents(".popup").first();
        $("body").css("overflow", "");
        g.hide();
        g.find(".js-popup_content").removeClass("animate")
    });
    $(document).click(function(f) {
        if ($(f.target).closest(".js-popup_content").length > 0) {
            return
        }
        if ($(f.target).closest("[data-popup-open]").length > 0) {
            return
        }
        if ($(f.target).closest('ymaps').length > 0) {
            return
        }
        $("body").css("overflow", "");
        $(".popup .popup-content").removeClass("animate");
        $(".popup").hide()
    });
    $("body").on("click", ".js-tool_tip", function(h) {
        h.preventDefault();
        var f = $($(this).attr("data-target")),
            g = $(this).parent();
        g.find(".js-tool_tip-content").css({
            opacity: 0,
            zIndex: -1,
            left: -10000
        });
        f.css({
            top: $(this).position().top - f.outerHeight() - 10,
            left: $(this).position().left + ($(this).outerWidth() - f.outerWidth()) / 2,
            opacity: 1,
            zIndex: 3
        })
    });
    $(document).on("click", function(f) {
        if ($(f.target).closest(".js-tool_tip-content").length > 0) {
            return
        }
        if ($(f.target).closest(".js-tool_tip").length > 0) {
            return
        }
        $(".js-tool_tip-content").css({
            opacity: 0,
            zIndex: -1,
            left: -10000
        })
    });
    $("body").on("click", ".js-toggle-class", function(i) {
        i.preventDefault();
        var f = $($(this).attr("data-target")),
            h = $(this).parent(),
            g = this;
        if (f.length > 0) {
            if (!!$(this).attr("data-class_delete")) {
                f.removeClass($(this).attr("data-class_delete"))
            }
            if (!!$(this).attr("data-class_add")) {
                f.addClass($(this).attr("data-class_add"))
            }
        }
        h.find(".js-toggle-class").removeClass("active");
        $(this).addClass("active");
        setTimeout(function() {
            obSlider.reInit($(g).attr("data-target"))
        }, 1000)
    });
    $("body").on("click", ".js-toggle", function(g) {
        g.preventDefault();
        if (!!$(this).attr("data-class")) {
            var h = $(this).attr("data-class"),
                f = $($(this).attr("data-target"));
            if (f.length == 0) {
                f = $(this)
            }
            if (f.hasClass(h)) {
                f.removeClass(h)
            } else {
                f.addClass(h)
            }
        }
    });
    $("body").on("mouseover", ".js-toggle-hover", function(g) {
        g.preventDefault();
        if (!!$(this).attr("data-class")) {
            var h = $(this).attr("data-class"),
                f = $($(this).attr("data-target"));
            if (f.length == 0) {
                f = $(this)
            }
            f.addClass(h)
        }
    });
    $("body").on("mouseleave", ".js-catalog-menu.active", function(f) {
        f.preventDefault();
        $(this).removeClass("active")
    });
    if ($(".js-aside").length > 0 && !b) {
        var d = {
                height: $(".js-aside").outerHeight(),
                top: $(".js-aside").offset().top,
                left: $(".js-aside").offset().left - 15
            },
            a = {},
            c = 0;
        if ($(".js-fixed").length > 0) {
            c = $(".js-fixed").outerHeight()
        }
        console.log(d);
        $(document).scroll(function() {
            if ($(this).scrollTop() >= (d.top - c) && $(this).scrollTop() <= ($(".js-footer").offset().top - d.height)) {
                console.log(d);
                a = {
                    position: "fixed",
                    top: c,
                    left: d.left,
                    width: $(".js-aside").outerWidth()
                }
            } else {
                a = {
                    position: "relative",
                    top: "auto",
                    left: "auto",
                    width: "auto"
                }
            }
            $(".js-aside").css(a)
        })
    }
    $(".js-fixed").each(function() {
        $(this).attr("data-top", $(this).offset().top)
    });
    $(document).scroll(function() {
        var f, g = null;
        $(".js-fixed").each(function() {
            f = parseFloat($(this).attr("data-top"));
            g = $(this).parent();
            var h = $(this);
            if ($(document).scrollTop() >= f) {
                g.css("height", g.height());
                $(this).addClass("fixed")
            } else {
                $(this).removeClass("fixed");
                g.css("height", "")
            }
        })
    });
    $("body").on("click", ".js-tumbler [data-content]", function() {
        var j = $(this).parents(".js-tumbler").first();
        if (j.length > 0) {
            var h = j.find("[data-content]").first(),
                g = h.attr("data-target").split(","),
                f = h.attr("data-positions").split(","),
                k = h.attr("data-value"),
                i = null;
            if (g.length > 0) {
                j.find(k).prop("checked", false);
                j.find(k).removeAttr("checked");
                if (g[0] == k) {
                    k = g[1];
                    i = 1
                } else {
                    k = g[0];
                    i = 0
                }
                j.find(k).prop("checked", true);
                j.find(k).attr("checked", "checked");
                j.find(k).change();
                h.attr("data-position", f[i]);
                h.attr("data-value", k)
            }
        }
    });
    $("body").on("click", ".js-mobile_menu-item", function(g) {
        if (!$(this).hasClass("active")) {
            g.preventDefault();
            var f = $(this).parents(".js-mobile_menu").first();
            f.addClass("active");
            f.find(".js-mobile_menu-item").removeClass("active");
            $(this).addClass("active")
        } else {
            location.href = $(this).attr("href")
        }
    });
    $("body").on("click", ".js-mobile_menu-back", function(g) {
        g.preventDefault();
        var f = $(this).parents(".js-mobile_menu").first();
        f.removeClass("active");
        f.find(".js-mobile_menu-item").removeClass("active")
    });
    if (typeof obSlider == "object") {
        obSlider.init()
    }
    if (typeof obTabs == "object") {
        obTabs.init()
    }
    if (typeof obAnimateInput == "object") {
        obAnimateInput.init()
    }
});
BX.ready(function() {
    BX.showWait = function(a) {
        BX.lastNode = a;
        BX.addClass(BX(a), "loading")
    };
    BX.closeWait = function(a) {
        if (!a) {
            a = BX.lastNode
        }
        BX.removeClass(BX(a), "loading")
    };
    var ck = document.getElementById('popup-cookie');
    if (!!ck) {
        ck.classList.add('active');
        var tm = new Date();
        tm.setTime(tm.getTime() + (1 * 24 * 60 * 60 * 1000));
        document.cookie = 'cookie_policy=true;expires=' + tm.toGMTString() + ';path=/'
    }
});
