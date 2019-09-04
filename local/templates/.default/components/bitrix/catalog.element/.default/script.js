(function(window){
	'use strict';
	window.catalogElementDetail = function(obParams)
	{
		this.params = obParams;
		this.obFavorite = document.querySelector('[data-entity="favorite"]');
		this.obCompare = document.querySelector('[data-entity="compare"]');
	};

	window.catalogElementDetail.prototype = {
		/**
		 *
		 * @param flag
		 */
		initCompare: function(flag)
		{
			if (!!this.obCompare) {
				var text = BX.message("COMPARE_TITLE");
				if (flag) {
					text = BX.message("BTN_MESSAGE_COMPARE_REDIRECT");
					this.obCompare.href = this.params.compare.COMPARE_PATH;
				} else {
					var ctx = this;
					ctx.obCompare.addEventListener("click", function() {
						BX.ajax({
							method: 'POST',
							dataType: 'json',
							url: ctx.params.compare.COMPARE_PATH + "?action=ADD_TO_COMPARE_LIST&ajax_action=Y&id=" + ctx.params.OFFER_ID,
							onsuccess: function(response) {
								ctx.doCompareCallBack(response);
							}
						});
					});
				}
				this.obCompare.querySelector("span").innerHTML = text;
			}
		},

		/**
		 *
		 * @param flag
		 */
		initFavorite: function(flag)
		{
			if (!!this.obFavorite) {
				var text = BX.message("FAVORITE_TITLE"),
					ctx = this;
				if (flag) {
					text = BX.message("BTN_MESSAGE_FAVORITE_REDIRECT");
					ctx.obFavorite.href = "/favorite/";
				} else {
					ctx.obFavorite.addEventListener("click", function(event) {
						ctx.initFavorite(true);
						obAjax.addToFavorite(this.getAttribute("data-id"), event);
					});
				}
				this.obFavorite.querySelector("span").innerHTML = text;
			}
		},

		/**
		 *
		 * @param resp
		 */
		doCompareCallBack: function(resp)
		{
			if (!resp.STATUS) {
				resp.STATUS = "FAIL";
			}
			if (!!resp.MESSAGE) {
				obAjax.addPopupMessage("compare-white", resp.MESSAGE);
			}
			this.initCompare(resp.STATUS == "OK");
		},

		/**
		 *
		 * @param value
		 * @param type
		 * @returns {*}
		 */
		getActualQnt: function(value, type)
		{
			value = parseInt(value);
			if (isNaN(value)) {
				value = 1;
			}
			switch (type) {
				case "plus": {
					value += 1;
					break;
				}
				case "minus": {
					value -= 1;
					break;
				}
			}
			if (value <= 0) {
				value = 1;
			}

			return value;
		},

		/**
		 *
		 * @param self
		 * @param type
		 */
		setQnt: function(self, type)
		{
			var $target = $($(self).attr("data-target"));
			if ($target.length > 0) {
				var newValue = this.getActualQnt($target.val(), type);
				$target.attr("value", newValue);
				$target.val(newValue);
			}
		},

		/**
		 *
		 * @param self
		 */
		changeQnt: function(self)
		{
			var newValue = this.getActualQnt($(self).val(), "");
			$(self).attr("value", newValue);
			$(self).val(newValue);
		}
	}
})(window);