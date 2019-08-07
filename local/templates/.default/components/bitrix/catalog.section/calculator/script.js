var obCalculator = {

        width: 0,
        height: 0,
        length: 0,
        items: [], //полки
        products: [], //товары,
        currentItemKey: 0, //текущая выбранная полка

        /**
         *
         * @param itemKey
         * @returns {*}
         */
        getItemHeight: function(itemKey)
        {
            return this.items[itemKey].height;
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
         * @param self
         */
        addItem: function(self)
        {
            this.items.push({
                height: parseFloat(self.value),
                products: []
            });
        },

        /**
         *
         * @param itemKey
         * @param self
         */
        updateItem: function(itemKey, self)
        {
            if (!this.items[itemKey]) {
                this.addItem(self);
            } else {
                this.items[itemKey].height = parseFloat(self.value);
            }
        },

        /**
         *
         * @param _length
         * @param _width
         * @param _height
         * @param productId
         */
        addProduct: function(_length, _width, _height, productId)
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
        }

    },
    obCalculatorRender = {

        blockId: "rack",

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
         */
        renderMain: function()
        {
            var wrapperNode = document.getElementById(this.blockId),
                rackNode = null,
                wrapperHeight = 0,
                ctx = this;
            if (!!wrapperNode) {
                wrapperNode.innerHTML = "";
                obCalculator.items.forEach(function(item) {
                    rackNode = ctx.string2Node(ctx.getRackItem(item.height));
                    item.products.forEach(function(product) {
                        rackNode.appendChild(ctx.string2Node(ctx.getTraiItem(product.width, product.height)))
                    })
                    wrapperNode.appendChild(rackNode);
                    if (item.height > wrapperHeight) {
                        wrapperHeight = item.height;
                    }
                });
                wrapperHeight += 50;
                wrapperNode.style.height = wrapperHeight + "px";
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
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.002 17.997"><defs><style>.a{fill:#ff2429;}</style></defs><path class="a" d="M-21980.4,206.55l-3.6-3.6v-14.4h2v13.6l2.4,2.4h18.2l2.4-2.4v-11.6h-2v9h-19v-9h-2v-2h25v14.4l-3.6,3.6Zm2.4-9h15v-7h-15Z" transform="translate(21984 -188.552)"/></svg>' +
                '</div>'
            ;
        },

        /**
         *
         * @param height
         * @returns {string}
         */
        getRackItem: function(height)
        {
            return '<div class="rack_item" style="top: ' + height + 'px"></div>';
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
        }

    };