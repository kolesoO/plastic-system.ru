var obAnimateInput={target:".js-animate_input",onKeyUpHandler:function(a){var b=$(a).parents(this.target).first();if($(a).val().length==0){b.addClass("_empty")}else{b.removeClass("_empty")}},init:function(){var a=this;$("body").find(a.target).each(function(){a.onKeyUpHandler($(this).find("input, textarea").first())});$(a.target+" input, "+a.target+" textarea").keyup(function(){a.onKeyUpHandler(this)})}};