$(document).ready(function() {
    ymaps.ready(function(){
        $('.js-store-map').on('click', function() {
            var $target = $($(this).attr('data-target')),
                map;

            if ($target.length === 0) return;

            //$target.html('');
            try {
                window['mapInstance'].destroy();
            } catch (e) {
                //do nothing
                console.log(e);
            }
            map = new obMap({
                mapId: $target.attr('id'),
                mapCenter: [
                    parseFloat($(this).attr('data-pgs_n')),
                    parseFloat($(this).attr('data-pgs_s'))
                ],
                mapZoom: parseInt($target.attr('data-zoom')),
                controls: [
                    new ymaps.control.ZoomControl({
                        options: {
                            position: {
                                right: 10,
                                top: 30
                            }
                        }
                    })
                ]
            });
            map.setPlacemarkOptions({
                iconLayout: "default#image",
                iconImageHref: "/local/templates/common/images/icons/marker_map.svg",
                iconImageSize: [37,45],
                iconImageOffset: [-18,-22]
            });
            map.addRoute({
                type: 'viaPoint',
                point: [
                    parseFloat($(this).attr('data-pgs_n_2')),
                    parseFloat($(this).attr('data-pgs_s_2'))
                ],
                properties: {},
                options: {}
            });
            map.addRoute({
                type: 'wayPoint',
                point: [
                    parseFloat($(this).attr('data-pgs_n')),
                    parseFloat($(this).attr('data-pgs_s'))
                ],
                properties: {
                    balloonContent: $(this).attr('data-way_point_body')
                },
                options: {
                    iconLayout: "default#image",
                    iconImageHref: "/local/templates/common/images/icons/marker_map.svg",
                    iconImageSize: [37,45],
                    iconImageOffset: [-18,-22]
                }
            });
            map.initMap();
            window['mapInstance'] = map.obMap;
        });
    });
});
