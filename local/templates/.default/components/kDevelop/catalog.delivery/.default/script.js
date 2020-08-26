var catalogDelivery = {
    getAddress: function(self, parentId)
    {
        var ctx = this,
            parentValue = "";

        if ($(self).val().length > 3) {
            if (!!parentId) {
                parentValue = $("#" + parentId).val();
            }
            $.ajax({
                url: location.href,
                data: {
                    class: "Address",
                    method: "getList",
                    params: {
                        query: $(self).val(),
                        type: $(self).attr("data-type"),
                        parent: parentValue
                    }
                },
                success: function(response) {
                    ctx.renderAddressList(self, response.answer);
                }
            })
        }
    },

    setAddress: function(targetId, self, evt)
    {
        evt.preventDefault();

        var $target = $($("#" + targetId).attr("data-target")),
            targetValue = [];

        $("#" + targetId).val($(self).text());
        $("#" + targetId).attr("value", $(self).text());

        $("[data-target='" + $("#" + targetId).attr("data-target") + "']").each(function() {
            targetValue.push($(this).val());
        });

        $target.val(targetValue);
        $target.attr("value", targetValue);

        $target.change();
    },

    renderAddressList: function(self, list)
    {
        var $parent = $(self).parent(),
            $node = $('<div class="tooltip"></div>'),
            innerNode = null,
            selfId = $(self).attr("id"),
            ctx = this;

        if ($parent.length > 0) {
            list.forEach(function(item) {
                innerNode = document.createElement("a");
                innerNode.href = "#";
                innerNode.classList.add("link");
                innerNode.innerText = item.name;
                innerNode.addEventListener("click", function() {
                    ctx.setAddress(selfId, this, event);
                });
                $node.append(innerNode);
            });
            $parent.find(".tooltip").remove();
            $parent.append($node);
        }
    },

    updateAddress: function(self)
    {
        var targetSelector = $(self).attr("data-target"),
            arNewValues = [];

        if ($(targetSelector).length > 0) {
            $("[data-target='" + targetSelector + "']").each(function() {
                arNewValues.push($(this).val());
            })
        }
        if (arNewValues.length > 0) {
            $(targetSelector).val(arNewValues.join(", "));
            $(targetSelector).attr("value", arNewValues.join(", "));
            $(targetSelector).trigger("change");
        }
    }
};

$(document).ready(function(e) {
    $('body').on('click', '#get_delivery-form', function () {
        if ($(e.target).closest(".tooltip").length > 0) return;

        $(this).find('.tooltip').remove();
    });
});
