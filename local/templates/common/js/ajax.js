var obAjax = {

    params: {},

    /**
     *
     * @param _params
     */
    setParams: function(_params)
    {
        this.params = (_params ? _params : {});
    },

    /**
     *
     * @param obData
     * @returns {string}
     */
    serializeData: function(obData)
    {
        var return_str = "";
        if(Object.keys(obData).length > 0){
            for(var i in obData){
                if(typeof obData[i] == "object"){
                    for(var j in obData[i]){
                        if(typeof obData[i][j] == "object"){
                            for(var k in obData[i][j]){
                                if(typeof obData[i][j][k] == "object"){
                                    for(var l in obData[i][j][k]){
                                        if (return_str.length > 0) {
                                            return_str += "&";
                                        }
                                        return_str += i + "[" + j + "][" + k + "][" + l + "]=" + obData[i][j][k][l];
                                    }
                                } else {
                                    if (return_str.length > 0) {
                                        return_str += "&";
                                    }
                                    return_str += i + "[" + j + "][" + k + "]=" + obData[i][j][k];
                                }
                            }
                        } else {
                            if (return_str.length > 0) {
                                return_str += "&";
                            }
                            return_str += i + "[" + j + "]=" + obData[i][j];
                        }
                    }
                } else{
                    if (return_str.length > 0) {
                        return_str += "&";
                    }
                    return_str += i + "=" + obData[i];
                }
            }
        }

        return return_str;
    },

    /**
     *
     * @param form
     */
    getFormObject: function(form)
    {
        var values = {},
            inputs = form.elements;

        for (var i = 0; i < inputs.length; i++) {
            values[inputs[i].name] = inputs[i].value;
        }

        return values;
    },

    /**
     *
     * @param iconClass
     * @param msg
     */
    addPopupMessage: function(iconClass, msg)
    {
        var id = "system-popup",
            node = document.createElement("div"),
            childNode = document.createElement("i"),
            oldNode = document.getElementById(id);

        if (oldNode) {
            oldNode.remove();
        }

        node.id = id;
        node.classList.add("system-popup");

        childNode.classList.add("icon", iconClass);
        node.appendChild(childNode);

        childNode = document.createElement("span");
        childNode.innerText = msg;
        node.appendChild(childNode);

        document.body.appendChild(node);

        setTimeout(function(){
            node.classList.add("active");
        }, 100);
        setTimeout(function(){
            node.remove();
        }, 3000);
    },

    /**
     *
     * @param wrapId
     * @param offerId
     * @param evt
     */
    getCatalogItem: function(wrapId, offerId, evt)
    {
        /**
         *
         * global obCatalogItemsParams
         */

        evt.preventDefault();

        if (typeof obCatalogItemsParams == "object") {
            obCatalogItemsParams.offer_id = offerId;
            obCatalogItemsParams.target_id = wrapId;
            this.setParams(obCatalogItemsParams);
        }

        this.doRequest(
            "GET",
            location.href,
            this.serializeData({
                class: "Catalog",
                method: "getCatalogItem",
                params: this.params
            }),
            [
                ["Content-type", "application/x-www-form-urlencoded"]
            ]
        );
    },

    /**
     *
     * @param data
     */
    getCatalogItemCallBack: function(data)
    {
        var targetBlock = document.getElementById(this.params.target_id);
        if (!!targetBlock) {
            targetBlock.innerHTML = data.html;
            obSlider.init("#" + this.params.target_id);
        }
    },

    /**
     *
     * @param formItem
     * @param dopFormId
     */
    getCatalogCalcItems: function(formItem, dopFormId)
    {
        /**
         *
         * global obCatalogCalcItemsParams
         */

        var form = formItem.closest("form"),
            subForm = document.getElementById(dopFormId);

        if (!!form && !!subForm) {

        }

        if (typeof obCatalogCalcItemsParams == "object") {
            obCatalogCalcItemsParams.FILTER_VALUES = Object.assign(this.getFormObject(form), this.getFormObject(subForm));
            this.setParams(obCatalogCalcItemsParams);
        }

        this.doRequest(
            "GET",
            location.href,
            this.serializeData({
                class: "Catalog",
                method: "getCatalogCalcItems",
                params: this.params
            }),
            [
                ["Content-type", "application/x-www-form-urlencoded"]
            ]
        );
    },

    /**
     *
     * @param offerId
     * @param priceId
     * @param evt
     */
    addToBasket: function(offerId, priceId, evt)
    {
        evt.preventDefault();

        this.setParams({
            offer_id: [offerId],
            price_id: priceId
        });
        this.doRequest(
            "POST",
            location.href,
            this.serializeData({
                class: "Basket",
                method: "add",
                params: this.params
            }),
            [
                ["Content-type", "application/x-www-form-urlencoded"]
            ]
        );
    },

    /**
     *
     * @param data
     */
    addToBasketCallBack: function(data)
    {
        BX.onCustomEvent('OnBasketChange');
        this.addPopupMessage("basket-white", "Товар добавлен в корзину");
    },

    /**
     *
     * @param form
     * @param evt
     */
    userRegister: function(form, evt)
    {
        evt.preventDefault();

        this.params.target_id = form.id;
        this.doRequest(
            "POST",
            location.href,
            this.serializeData({
                class: "User",
                method: "userRegister",
                params: this.getFormObject(form)
            }),
            [
                ["Content-type", "application/x-www-form-urlencoded"]
            ]
        );
    },

    /**
     *
     * @param data
     */
    userRegisterCallBack: function(data)
    {
        if (!!data.error_msg) {
            var errorBlock = document.querySelector("#" + this.params.target_id + " .error_txt");
            if (!!errorBlock) {
                errorBlock.innerHTML = data.error_msg.join("<br>");
            }
        } else if (data.USER_ID > 0) {
            location.reload();
        }
    },

    /**
     *
     * @param method
     * @param url
     * @param sendData
     * @param arHeaders
     */
    doRequest: function(method, url, sendData, arHeaders)
    {
        var ctx = this,
            ObXhttp = new XMLHttpRequest(),
            arHeaders = this.getHeaders(arHeaders),
            obResponse = null,
            targetBlock = document.getElementById(ctx.params.target_id);

        ObXhttp.open(method, url, true);
        ObXhttp.onloadstart = function() {
            BX.showWait(targetBlock);
        };
        ObXhttp.onloadend = function() {
            BX.closeWait(targetBlock);
        };
        ObXhttp.onload = function() {
            obResponse = JSON.parse(ObXhttp.responseText);
            if(!!obResponse.answer.js_callback && typeof ctx[obResponse.answer.js_callback] == "function") {
                ctx[obResponse.answer.js_callback](obResponse.answer);
            } else if (!!targetBlock) {
                targetBlock.innerHTML = obResponse.answer.html;
            }
        };
        //заголовки
        if (Object.keys(arHeaders).length > 0) {
            for(var headerKey in arHeaders) {
                ObXhttp.setRequestHeader(headerKey, arHeaders[headerKey] + "");
            }
        }
        //end
        ObXhttp.send(sendData);
    },

    /**
     *
     * @param arHeaders
     */
    getHeaders: function(arHeaders)
    {
        var obReturn = {};
        if (arHeaders.length > 0) {
            for (var counter in arHeaders) {
                if (arHeaders[counter].length == 2) {
                    obReturn[arHeaders[counter][0]] = arHeaders[counter][1];
                }
            }
        }

        return Object.assign(obReturn, this.getDefaultHeaders());
    },

    /**
     *
     * @returns {{"X-Requested-With": string}}
     */
    getDefaultHeaders: function()
    {
        return {
            "X-Requested-With": "xmlhttprequest"
        };
    }
}