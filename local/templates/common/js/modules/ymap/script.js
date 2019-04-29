/**
 *
 */
function obMap(obParams){

    this.obParams = obParams;
    this.obMap = null;
    this.arMarkerInfo = [];
    this.obPlacemarkProperties = {};
    this.obPlacemarkOptions = {};

}

/**
 *
 * @param strMsg
 */
obMap.prototype.showMessage = function(strMsg){

    console.log(strMsg);

};

/**
 *
 * @param arCoords
 * @param key
 * @returns {ymaps.Placemark}
 */
obMap.prototype.getGeoPlaceMark = function(arCoords, key){

    var ctx = this;

    return new ymaps.Placemark(arCoords, ctx.obPlacemarkProperties[key], ctx.obPlacemarkOptions);

};

/**
 *
 * @param arProps
 */
obMap.prototype.setPlacemarkProperties = function(arProps){

    var ctx = this;

    ctx.obPlacemarkProperties = arProps;

};

/**
 *
 * @param obProps
 */
obMap.prototype.setPlacemarkOptions = function(obProps){

    var ctx = this;

    for(var key in obProps){
        ctx.obPlacemarkOptions[key] = obProps[key];
    }

};

/**
 *
 * @param arInfo
 */
obMap.prototype.setMarkerInfo = function(arInfo){

    if(typeof arInfo == "object" && arInfo.length > 0){
        this.arMarkerInfo = arInfo;
    }

};

/**
 *
 * @param arCoords
 * @param arCoordsInner
 * @param obProps
 * @param obOptions
 * @returns {ymaps.Polygon}
 */
obMap.prototype.getPolygon = function(arCoords, arCoordsInner, obProps, obOptions){

    return new ymaps.Polygon([arCoords, arCoordsInner], obProps, obOptions);

};

/**
 *
 * @param arCoords
 * @param arCoordsInner
 * @param obProps
 * @param obOptions
 */
obMap.prototype.setPolygon = function(arCoords, arCoordsInner, obProps, obOptions){

    if(!!this.obMap){
        this.obMap.geoObjects.add(this.getPolygon(arCoords, arCoordsInner, obProps, obOptions));
    }

};

/**
 *
 */
obMap.prototype.initMap = function(){

    var ctx = this;

    if(ctx.arMarkerInfo.length > 0){
        if(!ctx.obMap){
            ctx.obMap = new ymaps.Map(ctx.obParams.mapId,{
                center: ctx.obParams.mapCenter,
                zoom: ctx.obParams.mapZoom
            });
        }
        for (var key in ctx.arMarkerInfo) {
            ctx.obMap.geoObjects.add(ctx.getGeoPlaceMark(ctx.arMarkerInfo[key].coords, key));
        }
    }

};