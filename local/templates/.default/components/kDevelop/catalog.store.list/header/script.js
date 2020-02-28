BX.storeNav = {

    cookieName: "store_id",

    cookieOptions: {
        expires: 86400,
        path: "/"
    },

    /**
     *
     * @param id
     * @param event
     */
    setStore: function(id, event) {
        event.preventDefault();
        BX.setCookie(this.cookieName, id, this.cookieOptions);
        location.reload();
    }

};