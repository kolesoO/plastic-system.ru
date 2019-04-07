var obAnimateInput = {

    target: ".js-animate_input",

    /**
     *
     * @param self
     */
    onKeyUpHandler: function(input){

        var $parent = $(input).parents(this.target).first();

        if($(input).val().length == 0){
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
            ctx.onKeyUpHandler($(this).find("input").first());
        })
        $(ctx.target + " input").keyup(function(){
            ctx.onKeyUpHandler(this);
        })

    }

}