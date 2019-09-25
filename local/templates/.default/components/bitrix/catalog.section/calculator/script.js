var obCalculator = {

        width: 0,
        height: 0,
        length: 0,
        items: [], //полки
        products: [], //товары,
        currentItemKey: -1, //текущая выбранная полка

        /**
         *
         * @param param
         * @param self
         */
        setWidth: function(self)
        {
            this.width = self.value;
            this.items = [];
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
            obCalculatorRender.renderMain();
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
         * @param _length
         * @param _width
         * @param _height
         * @param productId
         */
        addProduct: function(_width, _height, _length, productId)
        {
            this.items[this.currentItemKey].products.push({
                length: _length,
                width: _width,
                height: _height,
                id: productId
            });
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
         * @param productId
         */
        addCollection: function(width, height, length, productId)
        {
            var error = "";

            if (this.width < width) {
                error = "Большая ширина";
            } else if (this.items[this.currentItemKey].height < height) {
                error = "Большая высота";
            } else if (this.length < length) {
                error = "Большая глубина";
            }
            if (error.length > 0) {
                this.showMessage(error);
                return;
            }
            for (var counter = 0; counter < parseInt(this.width/width); counter++) {
                this.addProduct(width, height, length, productId)
            }
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

        getCatalogItems: function(self)
        {
            /**
             *
             * global obCatalogCalcItemsParams
             */

            if (this.length == 0 || this.width == 0 || this.height == 0) {
                obCalculatorRender.showMessage("Параметры стеллежа не заполнены полностью");
                return;
            }

            if (typeof obCatalogCalcItemsParams == "object") {
                var curItem;
                if (curItem = this.getCurrentItem()) {
                    obCatalogCalcItemsParams.FILTER_VALUES = {
                        "SECTION_ID": self.value,
                        "PROPERTY_DLINA_MM_VALUE": this.length,
                        "PROPERTY_SHIRINA_MM_VALUE": this.width,
                        "PROPERTY_VYSOTA_MM_VALUE": curItem.height
                    };
                    obAjax.getCatalogCalcItems(obCatalogCalcItemsParams);
                }
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
                newItemHeight = 0;

            if (!!wrapperNode) {
                wrapperNode.innerHTML = "";
                console.log(obCalculator);
                obCalculator.items.forEach(function(item, index) {
                    newItemHeight += obCalculator.getItemRealHeight(item, index);
                    rackNode = ctx.string2Node(ctx.getRackItem(newItemHeight, index));
                    item.products.forEach(function(product) {
                        rackNode.appendChild(ctx.string2Node(ctx.getTraiItem(product.width, product.height)))
                    })
                    wrapperNode.appendChild(rackNode);
                    wrapperNode.appendChild(ctx.string2Node(ctx.getRackDelete(newItemHeight, index)));

                    //events
                    rackNode.addEventListener("click", function() {
                        $(".rack_item").removeClass("active");
                        $(this).addClass("active");
                        obCalculator.setCurrentItem($(this).attr("data-index"));
                    });
                    //end
                });
            }
        },

        /**
         *
         * @param width
         * @param height
         * @returns {string}
         */
        getTraiItem: function(width, height)
        {
            return '<div class="tray_item" style="width: ' + width + 'px;height: ' + height + 'px;top: -' + height + 'px">' +
                '<a href="#" class="tray_item-close" title="удалить"><i class="icon close"></i></a>' +
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.002 17.997"><defs><style>.a_{fill:#ff2429;}</style></defs><path class="a_" d="M-21980.4,206.55l-3.6-3.6v-14.4h2v13.6l2.4,2.4h18.2l2.4-2.4v-11.6h-2v9h-19v-9h-2v-2h25v14.4l-3.6,3.6Zm2.4-9h15v-7h-15Z" transform="translate(21984 -188.552)"/></svg>' +
                '</div>'
            ;
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
            console.log(string);
        }

    };