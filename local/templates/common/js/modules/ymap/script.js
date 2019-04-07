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
 * @returns {ymaps.Placemark}
 */
obMap.prototype.getGeoPlaceMark = function(arCoords){

    var ctx = this;

    return new ymaps.Placemark(arCoords, ctx.obPlacemarkProperties, ctx.obPlacemarkOptions);

};

/**
 *
 * @param obProps
 */
obMap.prototype.setPlacemarkProperties = function(obProps){

    var ctx = this;

    for(var key in obProps){
        ctx.obPlacemarkProperties[key] = obProps[key];
    }

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

    var ctx = this,
        obGeoObject = null;

    if(ctx.arMarkerInfo.length > 0){
        if(!ctx.obMap){
            ctx.obMap = new ymaps.Map(ctx.obParams.mapId,{
                center: ctx.obParams.mapCenter,
                zoom: ctx.obParams.mapZoom
            });
        }
        ctx.arMarkerInfo.forEach(function(obMarkerInfo){
            obGeoObject = ctx.getGeoPlaceMark(obMarkerInfo.coords);
        })
        ctx.obMap.geoObjects.add(obGeoObject);
    }

};