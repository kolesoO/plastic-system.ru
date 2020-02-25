$(document).ready(function() {
    ymaps.ready(function(){
        $('.js-store-map').on('click', function() {
            var $target = $($(this).attr('data-target')),
                map;

            if ($target.length === 0) return;

            $target.html('');
            map = new obMap({
                mapId: $target.attr('id'),
                mapCenter: [
                    parseFloat($(this).attr('data-pgs_n')),
                    parseFloat($(this).attr('data-pgs_s'))
                ],
                mapZoom: parseInt($target.attr('data-zoom')),
                controls: ['routePanelControl']
            });
            map.setPlacemarkOptions({
                iconLayout: "default#image",
                iconImageHref: "/local/templates/common/images/icons/marker_map.svg",
                iconImageSize: [37,45],
                iconImageOffset: [-18,-22]
            });
            map.addRoute({
                from: [
                    parseFloat($(this).attr('data-pgs_n_2')),
                    parseFloat($(this).attr('data-pgs_s_2'))
                ],
                to: [
                    parseFloat($(this).attr('data-pgs_n')),
                    parseFloat($(this).attr('data-pgs_s'))
                ],
                toEnabled: false
            });
            map.initMap();
        });
    });
});
