var obYandexRouting = {

    debugMode: true,

    obParams: {},

    arDefaultMapCenter: [0,0],

    iDefaultZoom: 4,

    arMarkers: [],

    arDynamicMarkers: [],

    arGeoObjects: [],

    obMap: null,

    obCluster: null,

    obGeoObjectSettings: {},

    obMultiRoute: {},

    allowDistance: 0, //дистанция, которую можно проехать без дозаправки (км)

    allowDistanceKoef: 0.5, //допустимый процент дистанции без дозаправки при расчете наличия ближайших заправок

    allowDistanceStep: 0.1, // процент шага допустимой дистанции

    arRoutCoords: 0, //массив координат для построения маршрута

    requestCount: 0, //кол-во асинхронных запросов

    promiseChain: [], //цепочка асинхронных запросов,

    rsLastRoute: null,

    /**
     *
     * @param strMsg
     */
    showMessage: function(strMsg){

        console.log(strMsg);

    },

    /**
     *
     * @returns {number}
     */
    getAllowDistance: function(){

        return this.allowDistance*this.allowDistanceKoef;

    },

    /**
     *
     * @returns {number}
     */
    getAllowStep: function(){

        return this.allowDistance*this.allowDistanceStep;

    },

    /**
     *
     * @param arCoordsFrom
     * @param arCoordsTo
     * @returns {number}
     */
    getDistance: function(arCoordsFrom, arCoordsTo){

        return (ymaps.coordSystem.geo.getDistance(arCoordsFrom, arCoordsTo)*1)/1000;

    },

    /**
     *
     * @param arPoint
     * @param arPoints
     * @returns {boolean}
     */
    isPointInArray(arPoint, arPoints){

        arPoints.forEach(function(arPointItem){
            if(arPointItem[0] == arPoint[0] && arPointItem[1] == arPoint[1]){
                return true;
            }
        })

        return false;

    },

    /**
     *
     * @param _obParams
     */
    init: function(_obParams){

        this.obParams = (typeof _obParams == "object" ? _obParams : {});
        this.initDefaultParams();

    },

    /**
     *
     */
    initDefaultParams(){

        if(typeof this.obParams.MAP_CENTER != "Array")
            this.obParams.MAP_CENTER = this.arDefaultMapCenter;
        if(!this.obParams.MAP_ZOOM)
            this.obParams.MAP_ZOOM = this.iDefaultZoom;

    },

    /**
     *
     */
    initMap: function(){

        var ctx = this,
            obGeoObject = {},
            obMapWrap = document.getElementById(ctx.obParams.MAP_ID);

        ctx.arGeoObjects = [];
        console.log("loading map");
        if(!ctx.obMap){
            ctx.obMap = new ymaps.Map(ctx.obParams.MAP_ID,{
                center: ctx.obParams.MAP_CENTER,
                zoom: ctx.obParams.MAP_ZOOM
            });
        }
        else if(!ctx.debugMode){
            ctx.obMap.geoObjects.removeAll();
        }
        if(!!ctx.obCluster){
            ctx.obCluster.removeAll();
        }
        if(ctx.arDynamicMarkers.length > 0){
            ctx.arDynamicMarkers.forEach(function(obMarker){
                /*obGeoObject = ctx.getGeoObject(
                "Point",
                obMarker.list[0].position,
                {
                clusterCaption: (!!obMarker.clusterCaption ? obMarker.clusterCaption : obMarker.list[0].title),
                balloonContentBody: ctx.getGeoObjectBalloonContent(obMarker.list[0])
                }
                );*/
                obGeoObject = ctx.getGeoPlaceMark(
                    obMarker.list[0].position,
                    {
                        clusterCaption: (!!obMarker.clusterCaption ? obMarker.clusterCaption : obMarker.list[0].title),
                        balloonContentBody: ctx.getGeoObjectBalloonContent(obMarker.list[0])
                    },
                    {},
                    obMarker.list[0]
                );
                ctx.arGeoObjects.push(obGeoObject);
                ctx.obMap.geoObjects.add(obGeoObject);
            })
            if(!!ctx.obCluster){
                ctx.obCluster.add(ctx.arGeoObjects);
                ctx.obMap.geoObjects.add(ctx.obCluster);
                ctx.obMap.setBounds(ctx.obCluster.getBounds(),{
                    checkZoomRange: true
                });
            }
        }
        console.log("End loading map");

    },

    /**
     *
     */
    initCluster: function(){

        var ctx = this;

        ctx.obCluster = new ymaps.Clusterer(ctx.getClusterSettings());

    },

    /**
     *
     * @param strMarkers
     */
    setMarkers: function(strMarkers){

        this.arMarkers = this.arDynamicMarkers = JSON.parse(strMarkers);

    },

    /**
     *
     * @returns {boolean}
     */
    canCreateRoute: function(){

        return this.arMarkers.length >= 2;

    },

    /**
     *
     * @param strType
     * @param arCoords
     * @param obProps
     * @param strSettingsMethod
     * @returns {ymaps.GeoObject}
     */
    getGeoObject: function(strType, arCoords, obProps){

        return new ymaps.GeoObject(
            {
                geometry: {
                    type: strType,
                    coordinates: arCoords
                },
                properties: obProps

            },
            {}
        );

    },

    /**
     *
     * @param obProps
     * @returns {string}
     */
    getGeoObjectBalloonContent: function(obProps){

        var strReturn = '<div class="balloon-content">';
        if(!!obProps.adress){
            strReturn += '<p>' + obProps.adress + '</p>';
        }
        strReturn += '<p><a href="#" data-button data-button-size="small">Построить маршрут</a></p>';
        if(!!obProps.time){
            strReturn += '<p class="balloon-footer">' + obProps.time + '</p>';
        }
        strReturn == "</div>";

        return strReturn;

    },

    /**
     *
     * @param arCoords
     * @param obProps
     * @param obOptions
     * @param obInitialProps
     * @returns {ymaps.Placemark}
     */
    getGeoPlaceMark: function(arCoords, obProps, obOptions, obInitialProps){

        return new ymaps.Placemark(
            arCoords,
            obProps,
            Object.assign(obOptions, this.getImage(obInitialProps.isPrivate ? "PRIVATE" : "MAIN"))
        );

    },

    /**
     *
     * @returns {{preset: string, groupByCoordinates: boolean, clusterDisableClickZoom: boolean, clusterHideIconOnBalloonOpen: boolean, geoObjectHideIconOnBalloonOpen: boolean}}
     */
    getClusterSettings: function(){

        return {
            preset: "islands#invertedDarkBlueClusterIcons",
            groupByCoordinates: false,
            clusterDisableClickZoom: true,
            clusterHideIconOnBalloonOpen: false,
            geoObjectHideIconOnBalloonOpen: false,
            showInAlphabeticalOrder: true
        }

    },

    /**
     *
     * @param iParamKey
     * @returns {*}
     */
    getImage: function(iParamKey){

        if(!!this.obParams.ICON[iParamKey]){
            return {
                iconLayout: "default#image",
                iconImageHref: this.obParams.ICON[iParamKey].PATH,
                iconImageSize: Object.values(this.obParams.ICON[iParamKey].SIZE),
                iconImageOffset: Object.values(this.obParams.ICON[iParamKey].OFFSET)
            }
        }

        return {};

    },

    /**
     *
     * @param res
     * @returns {*}
     */
    getAddressCoords: function(res){

        return res.geoObjects.get(0).geometry.getCoordinates();

    },

    /**
     *
     * @param form
     */
    createFilter: function(form){

        var ctx = this,
            obFormData = $(form).serializeArray();

        ctx.arDynamicMarkers = [];
        if(obFormData.length > 0){
            obFormData.forEach(function(obItem){
                ctx.arMarkers.forEach(function(obMarker){
                    if(!!obMarker.options[obItem.name]){
                        ctx.arDynamicMarkers.push(obMarker);
                    }
                })
            })
        }
        else{
            ctx.arDynamicMarkers = ctx.arMarkers;
        }
        ctx.initMap();

    },

    /**
     *
     * @param arAddress
     */
    createMultiRoute: function(arAddress) {

        var ctx = this;

        ctx.obMultiRoute = new ymaps.multiRouter.MultiRoute(
            {
                referencePoints: arAddress,
                params:{
                    results: 1
                }
            },
            ctx.getMultiRouteSettings()
        );
        ctx.obMultiRoute.events.once("update", ctx.multiRouteUpdate());

    },

    /**
     *
     * @returns {{editorDrawOver: boolean, wayPointDraggable: boolean, viaPointDraggable: boolean, routeStrokeColor: string, routeActiveStrokeColor: string, pinIconFillColor: string, boundsAutoApply: boolean, zoomMargin: number}}
     */
    getMultiRouteSettings: function(){

        return {
            boundsAutoApply: true,
            wayPointVisible: false,
            zoomMargin: [100, 20, 20, 20],
            routeStrokeWidth: 4,
            routeStrokeColor: '#333',
            routeActiveStrokeWidth: 4,
            routeActiveStrokeColor: '#333'
        };

    },

    /**
     *
     */
    multiRouteUpdate: function(){

        var routes = ctx.obMultiRoute.getRoutes();

        for (var i = 0, l = routes.getLength(); i < l; i++) {
            var route = routes.get(i);
            if (!route.properties.get('blocked')) {
// Установим первый маршрут, у которого нет перекрытых
// участков, в качестве активного.
                ctx.obMultiRoute.setActiveRoute(route);
                break;
            }
        }

    },

    /**
     *
     * @param form
     * @param evt
     */
    createRouteStart: function(form, evt){

        evt.preventDefault();

        var ctx = this,
            obFormData = $(form).serializeArray(),
            obForm = {},
            obFromWaypoint = {},
            obToWaypoint = {};

        ctx.arDynamicMarkers = [];
        obFormData.forEach(function(obItem){
            obForm[obItem.name] = obItem.value;
        })
        ymaps.geocode(obForm.from).then(function(rsFrom){
            obFromWaypoint = {
                title: obForm.from,
                type: "wayPoint",
                point: ctx.getAddressCoords(rsFrom)
            };

            ymaps.geocode(obForm.to).then(function(rsTo){
                obToWaypoint = {
                    title: obForm.to,
                    type: "wayPoint",
                    point: ctx.getAddressCoords(rsTo)
                };
                ctx.allowDistance = parseFloat(obForm.volume)/parseFloat(obForm.volume_per_100km)*100;
                if(ctx.getDistance(obFromWaypoint.point, obToWaypoint.point) <= ctx.allowDistance){
                    ctx.initMap();
                    ctx.createRoute([obFromWaypoint, obToWaypoint]);
                }
                else{
                    ctx.createRoute([obFromWaypoint, obToWaypoint], "createViaPointsRoutePromise");
                }
            }, function(error){
                ctx.showMessage(error.message);
            })
        }, function(error){
            ctx.showMessage(error.message);
        })

    },

    /**
     *
     * @param obFrom
     * @param obTo
     * @param strPromiseFunction
     */
    createRoute: function(arPoints, strPromiseFunction, obRouteOptions){

        var ctx = this;

        ymaps.route(
            arPoints,
            ctx.getCreateRouteParams()
        ).then(function(rsRoute){
            if(typeof obRouteOptions == "object"){
                if(Object.keys(obRouteOptions).length > 0){
                    rsRoute.getPaths().options.set(obRouteOptions);
                }
            }
            if(!ctx.debugMode && !!ctx.rsLastRoute){
                ctx.obMap.geoObjects.remove(ctx.rsLastRoute);
            }
            ctx.rsLastRoute = rsRoute;
            ctx.obMap.geoObjects.add(rsRoute);
            if(typeof ctx[strPromiseFunction] == "function"){
                ctx[strPromiseFunction](rsRoute, arPoints);
            }
        }, function(error){
            ctx.showMessage(error.message);
        });

    },

    /**
     *
     * @param rsRoute
     */
    createViaPointsRoutePromise: function(rsRoute, arPoints){

        var ctx = this,
            arSegments = [],
            arCoords = [],
            arPrevCoords = [],
            arStartPoint = [];

        ctx.arRoutCoords = [];
        ctx.promiseChain = [];
        for(var iPathCounter = 0; iPathCounter < rsRoute.getPaths().getLength(); iPathCounter ++){
            arSegments = rsRoute.getPaths().get(iPathCounter).getSegments();
            for(var iSegmentsCounter = 0; iSegmentsCounter < arSegments.length; iSegmentsCounter ++){
                arCoords = arSegments[iSegmentsCounter].getCoordinates();
                for(var iCoordsCounter = 0; iCoordsCounter < arCoords.length; iCoordsCounter ++){
                    if(arStartPoint.length == 0){
                        arStartPoint = arCoords[iCoordsCounter];
                    }
                    if(ctx.getDistance(arStartPoint, arCoords[iCoordsCounter]) > ctx.getAllowDistance()){
                        ctx.searchInCircle(arPrevCoords, arStartPoint, arPoints[arPoints.length - 1]);
                        arStartPoint = arCoords[iCoordsCounter];
                    }
                    arPrevCoords = arCoords[iCoordsCounter];
                }
            }
        }
        Promise.all(ctx.promiseChain).then(function(){
            ctx.arRoutCoords.unshift(arPoints[0]);
            ctx.arRoutCoords.push(arPoints[arPoints.length - 1]);
            ctx.initMap();
            ctx.createRoute(
                ctx.arRoutCoords,
                "",
                {
                    balloonContentLayout: ymaps.templateLayoutFactory.createClass('{{ properties.humanJamsTime }}'),
                    strokeColor: '0000ffff',
                    opacity: 0.9
                }
            );
        });

    },

    /**
     *
     * @returns {{mapStateAutoApply: boolean, routingMode: string}}
     */
    getCreateRouteParams: function(){

        return {
            mapStateAutoApply: true,
            routingMode: "auto"
        }

    },

    /**
     *
     * @param obCoords
     * @param arStartPoint
     * @returns {Array}
     */
    getMaxDistance: function(obCoords, arStartPoint){

        var ctx = this,
            arMaxCoords = [],
            iMaxObjectDistance = 0,
            iObjectDistance = 0;

        obCoords.forEach(function(arCoords){
            iObjectDistance = ctx.getDistance(arStartPoint, arCoords);
            if(iObjectDistance > iMaxObjectDistance && !ctx.isPointInArray(arCoords, ctx.arRoutCoords)){
                iMaxObjectDistance = iObjectDistance;
                arMaxCoords = arCoords;
            }
        })

        return arMaxCoords;

    },

    /**
     *
     * @param arCoords
     * @param arStartPointCoords
     * @param arFinalPointCoords
     */
    searchInCircle: function(arCoords, arStartPointCoords, arFinalPointCoords){

        var ctx = this,
            obCircle = new ymaps.Circle(
                [arCoords, ctx.getAllowDistance()*1000], //перевод в м
                {},
                ctx.getCircleParams()
            );

        ctx.obMap.geoObjects.add(obCircle);
        var result = ymaps.geoQuery(ctx.arGeoObjects).searchInside(obCircle);
        result.then(function(){
            ctx.setRooutCoordsFromSearch(result, arStartPointCoords, arFinalPointCoords);
            if(!ctx.debugMode){
                ctx.obMap.geoObjects.remove(obCircle);
            }
        }, function(error){
            ctx.showMessage(error.message);

        });
        ctx.promiseChain.push(result);

    },

    /**
     *
     * @returns {{draggable: boolean, fill: boolean, strokeColor: string, strokeOpacity: number, strokeWidth: number}}
     */
    getCircleParams: function(){

        return {
            draggable: false,
            fill: false,
//fillColor: "transparent",
            strokeColor: "#990066",
            strokeOpacity: 0.9,
            strokeWidth: 1
        }

    },

    /**
     *
     * @param rsResult
     * @param arStartPointCoords
     */
    setRooutCoordsFromSearch: function(rsResult, arStartPointCoords, arFinalPointCoords){

        var arCoordsPull = [],
            ctx = this,
            allowDistance,
            arMaxCoords = [];

        arStartPointCoords = (!!arStartPointCoords.point ? arStartPointCoords.point : arStartPointCoords);
        arFinalPointCoords = (!!arFinalPointCoords.point ? arFinalPointCoords.point : arFinalPointCoords);
        allowDistance = ctx.getAllowDistance();
        if(ctx.arRoutCoords.length > 0){
            allowDistance += ctx.getDistance(arStartPointCoords, ctx.arRoutCoords[ctx.arRoutCoords.length - 1]);
        }
        if(ctx.getDistance(arStartPointCoords, arFinalPointCoords) > allowDistance){
            if(rsResult.getLength() > 0){
                for(var iSearchResultCounter = 0; iSearchResultCounter < rsResult.getLength(); iSearchResultCounter ++){
                    arCoordsPull.push(rsResult.get(iSearchResultCounter).geometry.getCoordinates());
                }
                arMaxCoords = ctx.getMaxDistance(arCoordsPull, arStartPointCoords);
                ctx.arRoutCoords.push(arMaxCoords);
                ctx.arMarkers.forEach(function(obMarker){
                    if(obMarker.list[0].position[0] == arMaxCoords[0] && obMarker.list[0].position[1] == arMaxCoords[1]){
                        ctx.arDynamicMarkers.push(obMarker);
//
//вывести шаблон с заправкой
//end
                        return;
                    }
                })
            }
            else{
                ctx.showMessage("На одном из участков маршрута не обнаружено заправочных станций");
            }
        }

    }

}