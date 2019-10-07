var obCalculator = {

        width: 0,
        height: 0,
        length: 0,
        items: [], //полки
        products: [], //товары,
        currentItemKey: -1, //текущая выбранная полка
        colorLib: {},

        /**
         *
         * @param param
         * @param self
         */
        setWidth: function(self)
        {
            this.width = self.value;
            this.items = [];
            this.currentItemKey = -1;
            obCalculatorRender.renderMain();
        },

        /**
         *
         * @param self
         */
        setHeight: function(self)
        {
            var wrapperNode = document.getElementById(obCalculatorRender.blockId);

            this.height = self.value;
            if (!!wrapperNode) {
                wrapperNode.style.height = this.height + "px";
            }
            this.items = [];
            this.currentItemKey = -1;
            obCalculatorRender.renderMain();
        },

        /**
         *
         * @param self
         */
        setLength: function(self)
        {
            this.length = self.value;
            this.items = [];
            this.currentItemKey = -1;
            obCalculatorRender.renderMain();
        },

        /**
         *
         * @param data
         */
        setColor: function(data)
        {
            this.colorLib = data;
        },

        /**
         *
         * @param itemKey
         */
        setCurrentItem: function(itemKey)
        {
            this.currentItemKey = itemKey;
        },

        /**
         *
         * @returns {*}
         */
        getCurrentItem: function()
        {
            if (!this.items[this.currentItemKey]) {
                obCalculatorRender.showMessage("Полка не выбрана");
                return false;
            }

            return this.items[this.currentItemKey];
        },

        /**
         *
         * @param self
         */
        addItem: function(targetSelector)
        {
            var $target = $(targetSelector),
                ctx = this;

            if ($target.length > 0) {
                var itemHeight = parseFloat($target.val()),
                    fullHeight = 0;
                if (!isNaN(itemHeight) && itemHeight > 0) {
                    this.items.forEach(function(item) {
                        fullHeight += ctx.getItemRealHeight(item, 1);
                    });
                    if (this.height >= fullHeight + itemHeight + obCalculatorRender.rackItemHeight) {
                        this.items.push({
                            height: itemHeight,
                            products: []
                        });
                        obCalculatorRender.renderMain();
                    } else {
                        obCalculatorRender.showMessage("Большая высота");
                    }
                }
            }
        },

        /**
         *
         * @param itemKey
         */
        deleteItem: function(itemKey)
        {
            this.items.splice(itemKey, 1);
            this.currentItemKey = -1;
            obCalculatorRender.renderMain();
        },

        /**
         *
         * @param _width
         * @param _height
         * @param _length
         * @param _price
         * @param _price_id
         * @param colorKey
         * @param productId
         * @returns {{color: string, price: *, length: *, width: *, price_id: *, id: *, height: *}}
         */
        getProduct: function(_width, _height, _length, _price, _price_id, colorKey, productId)
        {
            return {
                length: _length,
                width: _width,
                height: _height,
                price: _price,
                price_id: _price_id,
                color: !!this.colorLib[colorKey] ? this.colorLib[colorKey] : '',
                id: productId
            };
        },

        /**
         *
         * @param key
         */
        deleteProduct: function(key)
        {
            if (!!this.items[this.currentItemKey].products[key]) {
                this.items[this.currentItemKey].products.slice(key, 1);
            }
        },

        /**
         *
         * @param width
         * @param height
         * @param length
         * @param price
         * @param price_id
         * @param colorKey
         * @param productId
         * @param event
         */
        addCollection: function(width, height, length, price, price_id, colorKey, productId, event)
        {
            event.preventDefault();

            var error = "",
                arProducts = [],
                ctx = this,
                oldCollectionsHeight = 0,
                curItem;

            if (this.width < width) {
                error = "Большая ширина";
            } else if (this.items[this.currentItemKey].height < height) {
                error = "Большая высота";
            } else if (this.length < length) {
                error = "Большая глубина";
            } else {
                if (curItem = this.getCurrentItem()) {
                    for (var counter = 0; counter < curItem.products.length; counter++) {
                        oldCollectionsHeight += this.items[this.currentItemKey].products[counter][0].height;
                    }
                    if (curItem.height < oldCollectionsHeight + height) {
                        error = "Превышена допустимая высота полки";
                    }
                }
            }
            if (error.length > 0) {
                obCalculatorRender.showMessage(error);
                return;
            }
            for (var counter = 0; counter < parseInt(this.width/width); counter++) {
                arProducts.push(ctx.getProduct(width, height, length, price, price_id, colorKey, productId));
            }
            this.items[this.currentItemKey].products.push(arProducts);
            obCalculatorRender.renderMain();
        },

        /**
         *
         * @param item
         * @param index
         * @returns {*}
         */
        getItemRealHeight: function(item, index)
        {
            var height = item.height;
            if (index > 0) {
                height += obCalculatorRender.rackItemHeight;
            }

            return height;
        },

        /**
         *
         * @param self
         */
        getCatalogItems: function(self)
        {
            /**
             *
             * global obCatalogCalcItemsParams
             */

            if (this.length == 0 || this.width == 0 || this.height == 0) {
                obCalculatorRender.showMessage("Параметры стеллажа не заполнены полностью");
                return;
            }

            if (typeof obCatalogCalcItemsParams == "object") {
                var curItem;
                if (curItem = this.getCurrentItem()) {
                    obCatalogCalcItemsParams.SECTION_ID = self.value;
                    obCatalogCalcItemsParams.FILTER_VALUES = {
                        "PROPERTY_DLINA_MM_NUMBER": this.length,
                        "PROPERTY_SHIRINA_MM_NUMBER": this.width,
                        "PROPERTY_VYSOTA_MM_NUMBER": curItem.height
                    };
                    obAjax.getCatalogCalcItems(obCatalogCalcItemsParams);
                }
            }
        },

        /**
         *
         * @param evt
         */
        addToBasketMany: function(evt)
        {
            evt.preventDefault();

            var curItem;
            if (curItem = this.getCurrentItem()) {
                var offerId = [],
                    priceId = 0;
                curItem.products.forEach(function(row) {
                    row.forEach(function (product) {
                        offerId.push(product.id);
                        priceId = product.price_id;
                    });
                });
                obAjax.addToBasketMany(offerId, priceId, evt);
            }
        }

    },
    obCalculatorRender = {

        blockId: "rack-content",

        traiIdPrefix: "trai-",

        traiItemHeight: 18,

        rackItemHeight: 10,

        /**
         *
         * @param id
         */
        setBlockId: function(id)
        {
            this.blockId = id;
        },

        /**
         *
         * @param self
         */
        setRackItemHeight: function(self)
        {
            var cacheHeight = 0,
                height = 0;
            if ($(self).length > 0) {
                height = parseFloat($(self).val());
                if (!isNaN(height)) {
                    var fullHeight = 0;
                    cacheHeight = this.rackItemHeight;
                    this.rackItemHeight = height;
                    obCalculator.items.forEach(function(item, index) {
                        fullHeight += obCalculator.getItemRealHeight(item, 1);
                    });
                    if (obCalculator.height >= fullHeight) {
                        obCalculatorRender.renderMain();
                    } else {
                        obCalculatorRender.showMessage("Большая высота");
                        this.rackItemHeight = cacheHeight;
                    }
                }
            }
        },

        /**
         *
         */
        renderMain: function()
        {
            var wrapperNode = document.getElementById(this.blockId),
                rackNode = null,
                ctx = this,
                newItemHeight = 0,
                productWidthPercent = 0,
                productTopCoord = 0,
                fullQnt = 0,
                fullPrice = 0;

            if (!!wrapperNode) {
                wrapperNode.innerHTML = "";
                obCalculator.items.forEach(function(item, index) {
                    newItemHeight += obCalculator.getItemRealHeight(item, index);
                    rackNode = ctx.string2Node(ctx.getRackItem(newItemHeight, index));
                    item.products.forEach(function(row, index) {
                        productTopCoord += (index > 0 ? 2*row[0].height : row[0].height);
                        row.forEach(function(product) {
                            fullQnt++;
                            fullPrice+= product.price;
                            productWidthPercent = product.width/obCalculator.width*100;
                            rackNode.appendChild(ctx.string2Node(ctx.getTraiItem(productWidthPercent, product.height, productTopCoord, product.color)));
                        });
                    });
                    wrapperNode.appendChild(rackNode);
                    wrapperNode.appendChild(ctx.string2Node(ctx.getRackDelete(newItemHeight, index)));

                    //events
                    rackNode.addEventListener("click", function() {
                        $(".rack_item").removeClass("active");
                        $(this).addClass("active");
                        obCalculator.setCurrentItem($(this).attr("data-index"));
                    });
                    //end

                    //styles
                    if(index == obCalculator.currentItemKey) {
                        rackNode.classList.add("active");
                    }
                    //end
                });

                //update total
                var countNode = document.getElementById("full_count"),
                    priceNode = document.getElementById("full_price");
                if (!!countNode) {
                    countNode.innerText = fullQnt.toString();
                }
                if (!!priceNode) {
                    priceNode.innerText = fullPrice.toString();
                }
                //end
            }
        },

        /**
         *
         * @param widthPercent
         * @param height
         * @param top
         * @param color
         * @returns {string}
         */
        getTraiItem: function(widthPercent, height, top, color)
        {
            return '<div class="tray_item" style="width: ' + widthPercent + '%;height: ' + height + 'px;top: -' + top + 'px;background-color:' + color + '"></div>';
        },

        /**
         *
         * @param top
         * @param id
         * @returns {string}
         */
        getRackItem: function(top, id)
        {
            return '<div class="rack_item" style="top: ' + top + 'px;height: ' + this.rackItemHeight + 'px" data-index="' + id + '"></div>';
        },

        /**
         *
         * @param top
         * @param id
         * @returns {string}
         */
        getRackDelete: function(top, id)
        {
            return '<a href="#" class="rack-delete" style="top:' + top + 'px" title="удалить" onclick="obCalculator.deleteItem(' + id + ')">' +
                    '<i class="icon close"></i>' +
                '</a>';
        },

        /**
         *
         * @param string
         * @returns {ChildNode}
         */
        string2Node: function(string)
        {
            var parser = new DOMParser(),
                dom = parser.parseFromString(string, "text/html");

            return dom.body.childNodes[0];
        },

        /**
         *
         * @param string
         */
        showMessage: function(string)
        {
            obAjax.addPopupMessage("attention", string);
        }

    };