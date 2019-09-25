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
    serializeData: function(obData, prefixKey)
    {
        var return_str = "",
            hasPrefix = !!prefixKey;

        if(Object.keys(obData).length > 0){
            for(var i in obData){
                if (hasPrefix) {
                    i = prefixKey + "[" + i + "]";
                }
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
            if (inputs[i].name.length > 0) {
                values[inputs[i].name] = inputs[i].value;
            }
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

        var ctx = this;
        if (typeof obCatalogItemsParams == "object") {
            obCatalogItemsParams.offer_id = offerId;
            obCatalogItemsParams.target_id = wrapId;
            ctx.setParams(obCatalogItemsParams);
        }

        ctx.doRequest(
            "GET",
            location.href,
            ctx.serializeData({
                class: "Catalog",
                method: "getCatalogItem",
                params: ctx.params
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
    getCatalogCalcItems: function(params)
    {
        /**
         *
         * global obCatalogCalcItemsParams
         */

        var ctx = this;

        ctx.setParams(params);
        ctx.doRequest(
            "POST",
            location.href,
            ctx.serializeData({
                class: "Catalog",
                method: "getCatalogCalcItems",
                params: params
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
    getCatalogCalcItemsCallBack: function(data)
    {
        var targetBlock = document.getElementById(this.params.target_id);
        if (!!targetBlock) {
            targetBlock.innerHTML = data.html;
        }
    },

    /**
     *
     * @param offerId
     * @param priceId
     * @param evt
     */
    toPreBasketList: function(offerId, priceId, self, evt)
    {
        evt.preventDefault();

        if (!window.offerIdToBasket) {
            window.offerIdToBasket = {};
        }
        if (!!window.offerIdToBasket["OFFER_" + offerId]) {
            delete(window.offerIdToBasket["OFFER_" + offerId]);
            self.classList.remove("active");
        } else {
            window.offerIdToBasket["OFFER_" + offerId] = {
                "OFFER_ID": offerId,
                "PRICE_ID": priceId
            };
            self.classList.add("active");
        }
    },

    /**
     *
     * @param priceId
     * @param evt
     */
    addToBasketMany: function(evt)
    {
        /**
         * global offerIdToBasket
         */
        var priceId = 0,
            arOfferId = [];
        for (var key in window.offerIdToBasket) {
            priceId = window.offerIdToBasket[key].PRICE_ID;
            arOfferId.push(window.offerIdToBasket[key].OFFER_ID);
        }
        if (arOfferId.length > 0) {
            this.addToBasket(arOfferId, priceId, evt);
        }
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

        var ctx = this,
            $qntTarget = $($(evt.target).attr("data-qnt-target")),
            newParams = {
                offer_id: typeof offerId != "object" ? [offerId] : offerId,
                price_id: priceId
            };
        //fix
        if ($qntTarget.length <= 0) {
            $qntTarget = $($(evt.target).closest("a").attr("data-qnt-target"));
        }
        //end fix
        if ($qntTarget.length > 0) {
            newParams.qnt = $qntTarget.val();
        }
        ctx.setParams(newParams);
        ctx.doRequest(
            "POST",
            location.href,
            ctx.serializeData({
                class: "Basket",
                method: "add",
                params: ctx.params
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
        if (!!data.msg) {
            this.addPopupMessage("basket-white", data.msg);
        }
        BX.onCustomEvent('OnBasketChange');
    },

    /**
     *
     * @param form
     * @param evt
     */
    userRegister: function(form, evt)
    {
        evt.preventDefault();

        var ctx = this;
        ctx.params.target_id = form.id;
        ctx.doRequest(
            "POST",
            location.href,
            ctx.serializeData({
                class: "User",
                method: "userRegister",
                params: ctx.getFormObject(form)
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
     * @param id
     * @param target_id
     * @param evt
     */
    getUserAddressForm: function(id, target_id, evt)
    {
        evt.preventDefault();

        var ctx = this;
        ctx.params.target_id = target_id;
        ctx.doRequest(
            "POST",
            location.href,
            ctx.serializeData({
                class: "Component",
                method: "includeClass",
                params: {
                    comp_name: "kDevelop:user.address",
                    method_name: "getUserAddressForm",
                    id: id,
                    target_id: target_id
                }
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
    getUserAddressFormCallBack: function(data)
    {
        if (!!data.html) {
            var elem = document.getElementById(this.params.target_id);
            if (elem) {
                elem.innerHTML = data.html;
                if(typeof obAnimateInput == "object"){
                    obAnimateInput.init();
                }
            }
        }
    },

    /**
     *
     * @param form
     * @param target_id
     * @param evt
     */
    createUserAddress: function(form, target_id, evt)
    {
        evt.preventDefault();

        this.params.target_id = target_id;
        this.doRequest(
            "POST",
            location.href,
            $(form).serialize(), //TODO: отказаться от jQuery и перейти на native js
            [
                ["Content-type", "application/x-www-form-urlencoded"]
            ]
        );
    },

    /**
     *
     * @param data
     */
    createUserAddressCallBack: function(data)
    {
        if (!!data.msg) {
            var errElem = document.getElementById(this.params.target_id + "-error");
            if (!!errElem) {
                errElem.innerText = data.msg;
            }
        } else if (!!data.id) {
            document.getElementById(this.params.target_id).innerHTML = "";
            if (!!data.new_item) {
                var listWrapper = document.querySelector(".address_list"),
                    elem = null;
                if (!!listWrapper) {
                    elem = document.createElement("div");
                    elem.id = this.params.target_id = "address-" + data.id;
                    elem.classList.add("address_list-item");
                    listWrapper.appendChild(elem);
                }
            }
            this.getUserAddressData(data.id, this.params.target_id);
        }
    },

    /**
     *
     * @param id
     * @param target_id
     * @param evt
     */
    getUserAddressData: function(id, target_id, evt)
    {
        if (!!evt) {
            evt.preventDefault();
        }

        var ctx = this;
        ctx.params.target_id = target_id;
        ctx.doRequest(
            "POST",
            location.href,
            ctx.serializeData({
                class: "Component",
                method: "getUserAddressData",
                params: {
                    id: id,
                    target_id: target_id
                }
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
    getUserAddressDataCallBack: function(data)
    {
        if (!!data.html) {
            var elem = document.getElementById(this.params.target_id);
            if (elem) {
                elem.innerHTML = data.html;
            }
        }
    },

    /**
     *
     * @param target_id
     * @param evt
     */
    getUserAddressList: function(target_id, evt)
    {
        evt.preventDefault();

        var ctx = this;
        ctx.params.target_id = target_id;
        ctx.doRequest(
            "POST",
            location.href,
            ctx.serializeData({
                class: "Component",
                method: "getUserAddressList",
                params: {}
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
    getUserAddressListCallBack: function(data)
    {
        if (!!data.html) {
            var elem = document.getElementById(this.params.target_id);
            if (elem) {
                elem.innerHTML = data.html;
            }
        }
    },

    /**
     *
     * @param id
     * @param evt
     */
    setUserAddress: function(id, evt)
    {
        evt.preventDefault();

        var ctx = this;
        ctx.doRequest(
            "POST",
            location.href,
            ctx.serializeData({
                class: "Component",
                method: "getUserAddressData",
                params: {
                    id: id,
                    js_callback: "setUserAddressCallback",
                    is_json: true
                }
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
    setUserAddressCallback: function(data)
    {
        var elem = null;
        if (!!data.NAME) {
            elem = document.getElementById("ORDER_PROP_NAME");
            if (!!elem) {
                elem.value = data.NAME;
                $("#ORDER_PROP_NAME").keyup();
            }
        }
        if (typeof data.PROPERTIES == "object") {
            if (!!data.PROPERTIES.ADDRESS) {
                var addressElemCollection = [
                        document.getElementById("city"),
                        document.getElementById("street"),
                        document.getElementById("house"),
                        document.getElementById("korp")
                    ];
                for (var counter = 0; counter < data.PROPERTIES.ADDRESS.VALUE.length; counter ++) {
                    if (!!addressElemCollection[counter]) {
                        addressElemCollection[counter].value = data.PROPERTIES.ADDRESS.VALUE[counter];
                        $("#" + addressElemCollection[counter].id).keyup();
                    }
                }
                delete data.PROPERTIES.ADDRESS;
            }
            for (var propCode in data.PROPERTIES) {
                elem = document.getElementById("ORDER_PROP_" + propCode);
                if (!!elem) {
                    elem.value = data.PROPERTIES[propCode].VALUE;
                    $("#" + elem.id).keyup();
                }
            }
        }
    },

    /**
     *
     * @param id
     * @param evt
     */
    addToFavorite: function(id, evt)
    {
        evt.preventDefault();

        var ctx = this;
        ctx.doRequest(
            "POST",
            location.href,
            ctx.serializeData({
                class: "Favorite",
                method: "add",
                params: {
                    id: id
                }
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
    addToFavoriteCallBack: function(data)
    {
        if (!!data.msg) {
            this.addPopupMessage("favorite-white", data.msg);
        }
    },

    /**
     *
     * @param id
     * @param evt
     */
    deleteFromFavorite: function(id, evt)
    {
        evt.preventDefault();

        this.doRequest(
            "POST",
            location.href,
            this.serializeData({
                class: "Favorite",
                method: "delete",
                params: {
                    id: id
                }
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
    deleteFromFavoriteCallBack: function(data)
    {
        if (!!data.msg) {
            this.addPopupMessage("favorite-white", data.msg);
        }
    },

    /**
     *
     * @param form
     * @param evt
     */
    getDelivery: function(form, evt)
    {
        evt.preventDefault();

        var ctx = this;
        ctx.params.target_id = form.id;
        ctx.doRequest(
            "POST",
            location.href,
            ctx.serializeData({
                class: "Component",
                method: "includeClass",
                params: {
                    comp_name: "kDevelop:catalog.delivery",
                    method_name: "getDelivery",
                    //id: id,
                    target_id: ctx.params.target_id,
                    form_data: ctx.getFormObject(form)
                }
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
    getDeliveryCallBack: function(data)
    {
        if (!!data.html) {
            var elem = document.getElementById(this.params.target_id);
            if (elem) {
                elem.innerHTML = data.html;
                //animate input
                if(typeof obAnimateInput == "object"){
                    obAnimateInput.init();
                }
                //end
            }
        }
    },

    /**
     *
     * @param form
     * @param evt
     */
    initPayment: function(form, evt)
    {
        evt.preventDefault();

        var ctx = this;
        ctx.params.target_id = form.id;
        ctx.doRequest(
            "POST",
            location.href,
            ctx.serializeData({
                class: "Payment",
                method: "initPayment",
                params: ctx.getFormObject(form)
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
    initPaymentCallBack: function(data)
    {
        if (!!data.url) {
            window.open(data.url);
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