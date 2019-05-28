var obSlider = {

    isMobile: $(window).width() < 1249,

    defSliderTarget: ".js-slider",

    defWrapperTarget: "body",

    autoplaySpeed: 500,

    speed: 500,

    slidesToShow: 1,

    slidesToScroll: 1,

    nextArrow: '<a href="javascript:void(0)" class="arrow-left"></a>',

    prevArrow: '<a href="javascript:void(0)" class="arrow-right"></a>',

    /**
     *
     * @param self
     * @returns {{}}
     */
    getParams: function(self){

        var obParams = {};

        obParams.autoplay = ($(self).attr("data-autoplay") == "true");
        obParams.autoplaySpeed = (!!$(self).attr("data-autoplaySpeed") ? $(self).attr("data-autoplaySpeed") : this.autoplaySpeed);
        obParams.infinite = $(self).attr("data-infinite") == "true";
        obParams.speed = (!!$(self).attr("data-speed") ? $(self).attr("data-speed") : this.speed);
        obParams.arrows = $(self).attr("data-arrows") == "true";
        if(obParams.arrows){
            obParams.nextArrow = (!!$(self).attr("data-nextArrow") ? $(self).attr("data-nextArrow") : this.nextArrow);
            obParams.prevArrow = (!!$(self).attr("data-prevArrow") ? $(self).attr("data-prevArrow") : this.prevArrow);
        }
        obParams.dots = $(self).attr("data-dots") == "true";
        obParams.slidesToShow = (!!$(self).attr("data-slidesToShow") ? parseInt($(self).attr("data-slidesToShow")) : this.slidesToShow);
        obParams.slidesToScroll = (!!$(self).attr("data-slidesToScroll") ? parseInt($(self).attr("data-slidesToScroll")) : this.slidesToScroll);
        obParams.centerMode = $(self).attr("data-centerMode") == "true";

        return obParams;

    },

    /**
     *
     * @param strTarget
     */
    init: function(strWrapper){

        var ctx = this;

        if(!strWrapper) strWrapper = ctx.defWrapperTarget;
        $(strWrapper).find(ctx.defSliderTarget).each(function(){
            $(this).slick(ctx.getParams(this));
            ctx.setFixedItems(this);
            ctx.customSliderActions(this);
        })

    },

    /**
     *
     * @param strWrapper
     */
    reInit: function(strWrapper){

        $(strWrapper).find(this.defSliderTarget).each(function(){
            $(this).slick("unslick");
        })
        this.init(strWrapper);

    },

    /**
     *
     * @param self
     */
    setFixedItems: function(self){

        var $collection = $(self).find("[data-fixed_target]"),
            $parent = null,
            hasArrows = $(self).attr("data-arrows") == "true";

        if($collection.length > 0){
            $(self).wrap('<div style="position:relative;z-index:1;margin:' + $(self).css("margin") + '"></div>');
            $(self).css("margin", "0");
            $parent = $(self).parent();
            $collection.each(function(){
                $(this)
                    .clone()
                    .css({
                        position:"absolute",
                        top:$(this).position().top,
                        left:$(this).position().left,
                    })
                    .appendTo($parent);
                $(this).html("&nbsp;");
            })
        }

    },

    /**
     *
     * @param self
     */
    customSliderActions: function(self){

        if($(self).hasClass("js-main_slider")){
            var cssProp = (this.isMobile ? "left" : "right");
            $(self).find(".slick-dots").css(cssProp, $("body").outerWidth()/2 - $(".container").outerWidth()/2);
        }

    }

}