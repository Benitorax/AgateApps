(function($, L, d, w){

    EsterenMap.prototype._map = null;
    EsterenMap.prototype._sidebar = {};
    EsterenMap.prototype._drawControl = {};
    EsterenMap.prototype._drawnItems = {};
    EsterenMap.prototype._tileLayer = {};

    EsterenMap.prototype.mapElements = {
        factions: true,
        routes: true,
        routesTypes: true,
        markers: true,
        markersTypes: true,
        zones: true
    };

    EsterenMap.prototype.mapOptions = {
        id: 0,
        editMode: false,
        autoResize: true,
        containerHeight: 400,
        sidebarContainer: 'sidebar',
        container: 'map',
        loadedCallback: function(){
            this.loadMarkers();
            this.loadRoutes();
            this.loadZones();
        },
        imgUrl: '/bundles/esterenmaps/img',
        apiUrls: {
            base: '/api/maps/',
            tiles: '/api/maps/tile/{id}/{z}/{x}/{y}.jpg'
        },
        loaderCallbacks: {},
        center: [0,0],
        zoom: 1,
        maxMarkerId: 1,
        maxPolylineId: 1,
        maxPolygonId: 1,
        LeafletPopupMarkerBaseContent: '',
        LeafletPopupPolygonBaseContent: '',
        LeafletPopupPolylineBaseContent: '',
        LeafletPopupBaseOptions: {
            maxWidth: 350,
            minWidth: 280
        },
        LeafletMapBaseOptions: {
            center: [0,0],
            zoom: 1,
            minZoom: 1,
            maxZoom: 1,
            attributionControl: false
        },
        LeafletLayerBaseOptions: {
            attribution: '&copy; Esteren Maps',
            minZoom: 1,
            maxZoom: 1,
            maxNativeZoom: 1,
            tileSize: 168,
            noWrap: false,
            continuousWorld: true
        }
    };

})(jQuery, L, document, window);