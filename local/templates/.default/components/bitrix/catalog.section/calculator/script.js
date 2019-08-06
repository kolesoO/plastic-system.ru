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

        blockId: "",

        /**
         *
         * @param id
         */
        setBlockId: function(id)
        {
            this.blockId = id;
        },

        renderMain: function()
        {

        }

    };