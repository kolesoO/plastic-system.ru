var obAnimateInput = {

    target: ".js-animate_input",

    /**
     *
     * @param node
     */
    onKeyUpHandler: function(node){

        var $parent = $(node).parents(this.target).first();

        if($(node).val().length == 0){
            $parent.addClass("_empty");
        }
        else{
            $parent.removeClass("_empty");
        }

    },

    /**
     *
     */
    init: function(){

        var ctx = this;

        $("body").find(ctx.target).each(function(){
            ctx.onKeyUpHandler($(this).find("input, textarea").first());
        })
        $(ctx.target + " input, " + ctx.target + " textarea").keyup(function(){
            ctx.onKeyUpHandler(this);
        })

    }

}