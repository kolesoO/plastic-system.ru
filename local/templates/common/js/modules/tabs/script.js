var obTabs = {

    defWrapperTarget: "body",

    defTabTarget: ".js-tabs",

    /**
     *
     * @param strWrapper
     */
    init: function(strWrapper){

        var ctx = this;

        if(!strWrapper) strWrapper = ctx.defWrapperTarget;
        $(strWrapper).find(ctx.defTabTarget).each(function(){
            ctx.initBtns($(this).find("[data-tab_target]"));
            ctx.initContent($(this).find("[data-tab_content] [data-tab_item]"));
        })
        ctx.initEvents();

    },

    /**
     *
     * @param $arCollection
     */
    initBtns: function($arCollection){

        $arCollection.removeClass("active");
        $arCollection.first().addClass("active");

    },

    /**
     *
     * @param $arCollection
     */
    initContent: function($arCollection){

        $arCollection.hide();
        $arCollection.first().fadeIn();

    },

    /**
     *
     */
    initEvents: function(){

        var ctx = this;

        $(ctx.defTabTarget).on("click", "[data-tab_target]", function(e){
            ctx.tabClickCallBack(e, this);
        });

    },

    /**
     *
     * @param evt
     * @param self
     */
    tabClickCallBack: function(evt, self){

        evt.preventDefault();

        var ctx = this,
            $wrap = $(self).parents(ctx.defTabTarget).first(),
            $target = $($(self).attr("data-tab_target"));

        $wrap.find("[data-tab_target]").removeClass("active");
        $wrap.find("[data-tab_content] [data-tab_item]").hide();
        $(self).addClass("active");
        $target.fadeIn();

    }

}