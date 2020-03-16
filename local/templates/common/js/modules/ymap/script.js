/**
 *
 */
function obMap(obParams){

    this.obParams = obParams;
    this.obMap = null;
    this.arMarkerInfo = [];
    this.routes = [];
    this.obPlacemarkProperties = {};
    this.obPlacemarkOptions = {};
    this.controls = [];

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
 * @param obPlacemarkProperties
 * @param obPlacemarkOptions
 * @returns {ymaps.Placemark}
 */
obMap.prototype.getGeoPlaceMark = function(arCoords, obPlacemarkProperties, obPlacemarkOptions){

    var ctx = this;

    obPlacemarkProperties = obPlacemarkProperties || ctx.obPlacemarkProperties;
    obPlacemarkOptions = obPlacemarkOptions || ctx.obPlacemarkOptions;

    return new ymaps.Placemark(arCoords, obPlacemarkProperties, obPlacemarkOptions);

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
 * @param routeInfo
 */
obMap.prototype.addRoute = function(routeInfo) {
    this.routes.push(routeInfo);
};

/**
 *
 */
obMap.prototype.initMap = function(){

    var ctx = this;

    if(!ctx.obMap){
        ctx.obMap = new ymaps.Map(ctx.obParams.mapId,{
            center: ctx.obParams.mapCenter,
            zoom: ctx.obParams.mapZoom,
            controls: ctx.obParams.controls
        });
    }

    if(ctx.arMarkerInfo.length > 0){
        ctx.arMarkerInfo.forEach(function(obMarkerInfo){
            ctx.obMap.geoObjects.add(
                ctx.getGeoPlaceMark(obMarkerInfo.coords)
            );
        });
    }

    if (ctx.routes.length > 0) {
        ymaps.route(
            ctx.routes,
            {
                mapStateAutoApply: true
            }
        ).then(function (route) {
            route.getPaths()
                .options.set({
                    balloonContentLayout: ymaps.templateLayoutFactory.createClass('{{ properties.humanJamsTime }}'),
                    strokeColor: '5eadfd',
                    opacity: 0.9
                });
            route.getWayPoints()
                .each(function (item) {
                    item.options.set('visible', false);
                });
            ctx.obMap.geoObjects.add(route);
        });
        ctx.routes.forEach(function (obMarkerInfo) {
            if (obMarkerInfo.type === 'wayPoint') {
                ctx.obMap.geoObjects.add(
                    ctx.getGeoPlaceMark(
                        obMarkerInfo.point,
                        obMarkerInfo.properties,
                        obMarkerInfo.options
                    )
                );
            }
        });
    }

};
