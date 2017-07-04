(function($, L, d, w){

    // Rajoute qqs attributs à des éléments de Leaflet et LeafletSidebar
    L.Marker.prototype._esterenMap = {};
    L.Marker.prototype._esterenMarker = {};
    L.Marker.prototype._esterenRoutesStart = null;
    L.Marker.prototype._esterenRoutesEnd = null;
    L.Marker.prototype._sidebar = null;
    L.Marker.prototype._sidebarContent = '';
    L.Marker.prototype._clickedTime = 0;

    L.Marker.prototype.showSidebar = function(){
        if (!this._sidebar) {
            return;
        }
        this._sidebar.setContent(this._sidebarContent);
        this._sidebar.show();
        return this;
    };

    L.Marker.prototype.hideSidebar = function(){
        if (!this._sidebar) {
            return;
        }
        this._sidebar.hide();
        this._sidebar.setContent('');
        return this;
    };

    L.Marker.prototype.toggleSidebar = function(){
        if (!this._sidebar) {
            return;
        }
        if (this._sidebar.isVisible()) {
            this.hideSidebar();
        } else {
            this.showSidebar();
        }
        return this;
    };

    L.Marker.prototype.bindSidebar = function(sidebar, content){
        this._sidebar = sidebar;
        this._sidebarContent = content;
        return this;
    };

    L.Marker.prototype.updateIcon = function(){
        // Change l'image de l'icône
        this._icon.src = this._esterenMarker.marker_type.web_icon;

        // Met à jour l'attribut "data" pour les filtres
        $(this._icon).attr('data-leaflet-object-type', 'markerType'+this._esterenMarker.marker_type.id);
    };

    L.Marker.prototype.disableEditMode = function() {
        this.dragging.disable();
        this._icon.classList.remove('selected');
    };

    L.Marker.prototype.refreshRoutesStart = function() {
        var routes = this._esterenMap._polylines,
            i, route;
        for (i in routes) {
            if (routes.hasOwnProperty(i)) {
                route = routes[i];
                if (route._esterenRoute.marker_start && route._esterenRoute.marker_start.id == this._esterenMarker.id) {
                    this._esterenRoutesStart[route._esterenRoute.id] = route;
                    route._esterenMarkerStart = this;
                }
            }
        }
        return this._esterenRoutesStart;
    };

    L.Marker.prototype.refreshRoutesEnd = function() {
        var routes = this._esterenMap._polylines,
            i, route;
        for (i in routes) {
            if (routes.hasOwnProperty(i)) {
                route = routes[i];
                if (route._esterenRoute.marker_end && route._esterenRoute.marker_end.id == this._esterenMarker.id) {
                    this._esterenRoutesEnd[route._esterenRoute.id] = route;
                    route._esterenMarkerEnd = this;
                }
            }
        }
        return this._esterenRoutesEnd;
    };

    L.Marker.prototype.refreshRoutes = function() {
        var routesStart, routesEnd, i, route;
        if (routesStart = this.refreshRoutesStart()) {
            for (i in routesStart) {
                if (routesStart.hasOwnProperty(i) && routesStart[i]) {
                    route = routesStart[i];
                    route.updateDetails();
                }
            }
        }
        if (routesEnd = this.refreshRoutesEnd()) {
            for (i in routesEnd) {
                if (routesEnd.hasOwnProperty(i) && routesEnd[i]) {
                    route = routesEnd[i];
                    route.updateDetails();
                }
            }
        }
    };

    L.Marker.prototype._delete = function () {
        var marker = this,
            msg = CONFIRM_DELETE || 'Supprimer ?',
            id = marker._esterenMarker ? marker._esterenMarker.id : null;
        if (marker._esterenMap._mapOptions.editMode == true && id) {
            if (confirm(msg)) {
                marker._map.removeLayer(marker);
                marker.fire('remove');
            }
        }
        return false;
    };

    L.Marker.prototype._updateEM = function() {
        var baseMarker = this,
            esterenMarker = EsterenMap.prototype.cloneObject.call(null, this._esterenMarker),
            _this = this,
            callbackMessage = '',
            callbackMessageType = 'success',
            id = esterenMarker.id || null;
        if (esterenMarker && this._map && !this.launched) {
            this.launched = true;
            esterenMarker.map = esterenMarker.map || {id: this._esterenMap._mapOptions.id };
            esterenMarker.latitude = this._latlng.lat;
            esterenMarker.longitude = this._latlng.lng;
            esterenMarker.altitude = this._latlng.alt;
            esterenMarker.faction = esterenMarker.faction || {};
            esterenMarker.marker_type = { id: esterenMarker.marker_type.id };
            this._esterenMap._load({
                url: "markers" + (id ? '/'+id : ''),
                method: id ? "POST" : "PUT", // Si on n'a pas d'ID, c'est qu'on crée un nouveau marqueur
                data: {
                    json: esterenMarker,
                    mapping: {
                        name: true,
                        description: true,
                        longitude: true,
                        latitude: true,
                        map: true,
                        marker_type: {
                            objectField: 'markerType'
                        },
                        faction: true
                    }
                },
                callback: function(response) {
                    var map = this,
                        msg,
                        marker = response.newObject;
                    if (!response.error) {
                        if (marker && marker.id) {
                            map._markers[marker.id] = baseMarker;
                            map._markers[marker.id]._esterenMarker = marker;
                            map._markers[marker.id].updateIcon();
                            callbackMessage = 'Marker: ' + marker.id + ' - ' + marker.name;
                        } else {
                            msg = 'Marker retrieved by API does not have ID.';
                            console.warn(msg);
                            callbackMessage = response.message ? response.message : msg;
                            callbackMessageType = 'warning';
                        }
                    } else {
                        msg = 'Api returned an error while attempting to '+(id?'update':'insert')+' a marker.';
                        console.error(msg);
                        callbackMessage = msg + '<br>' + (response.message ? response.message : 'Unknown error...');
                        callbackMessageType = 'danger';
                    }
                },
                callbackError: function() {
                    var msg = 'Could not make a request to '+(id?'update':'insert')+' a marker.';
                    console.error(msg);
                    callbackMessage = msg;
                    callbackMessageType = 'danger';
                },
                callbackComplete: function(){
                    _this.launched = false;
                    if (callbackMessage) {
                        _this._esterenMap.message(callbackMessage, callbackMessageType);
                    }
                }
            });
        } else if (!this.launched) {
            console.error('Tried to update an empty marker.');
        }
    };

    EsterenMap.prototype.esterenMarkerPrototype = {
        id: null,
        name: null,
        description: null,
        marker_type: null,
        faction: null,
        latitude: null,
        longitude: null
    };

    /**
     * @this {EsterenMap}
     * @param response
     */
    EsterenMap.prototype._mapOptions.loaderCallbacks.markers = function(response){
        var markers, i, marker,
            mapOptions = this._mapOptions,
            popupContent = mapOptions.LeafletPopupMarkerBaseContent,
            options = mapOptions.CustomMarkerBaseOptions,
            leafletOptions = mapOptions.LeafletMarkerBaseOptions,
            coords
        ;

        if (mapOptions.editMode === true) {
            options = this.cloneObject(options, mapOptions.CustomMarkerBaseOptionsEditMode);
            leafletOptions = this.cloneObject(leafletOptions, mapOptions.LeafletMarkerBaseOptionsEditMode);
        }

        for (i in this._markers) {
            if (this._markers.hasOwnProperty(i)) {
                this._map.removeLayer(this._markers[i]);
                this._drawnItems.removeLayer(this._markers[i]);
            }
        }

        if (response['map.'+mapOptions.id+'.markers']) {
            markers = response['map.'+mapOptions.id+'.markers'];
            for (i in markers) {
                if (markers.hasOwnProperty(i)) {
                    marker = markers[i];
                    coords = {
                        lat: marker.latitude,
                        lng: marker.longitude,
                        altitude: marker.altitude
                    };

                    options.popupContent = popupContent;
                    options.esterenMarker = marker;
                    options.markerName = marker.name;
                    options.markerType = marker.marker_type.id;
                    options.markerFaction = marker.faction ? marker.faction.id : '';

                    leafletOptions.alt = marker.id;

                    this.addMarker(coords,
                        leafletOptions,
                        options
                    );
                }//endif (marker.hasOwnProperty)
            }//endfor
        }// endif response
    };


    EsterenMap.prototype._mapOptions.LeafletMarkerBaseOptions = {
        clickable: true,
        draggable: false
    };

    EsterenMap.prototype._mapOptions.LeafletMarkerBaseOptionsEditMode = {
        draggable: false
    };

    EsterenMap.prototype._mapOptions.LeafletIconBaseOptions = {
        shadowUrl: '',
        shadowRetinaUrl: ''
    };

    EsterenMap.prototype._mapOptions.CustomMarkerBaseOptions = {
        popupIsSidebar: false,
        clickCallback: function(e){
            var marker = e.target,
                changePopupContent = false,
                esterenMarker = marker._esterenMarker
            ;

            if (marker._sidebar) {
                marker.showSidebar();
                changePopupContent =  marker._sidebar.isVisible();
            } else {
                //marker.togglePopup();
                changePopupContent = marker.getPopup()._isOpen;
            }

            if (changePopupContent) {
                d.getElementById('marker_popup_name').innerHTML = esterenMarker.name;
                d.getElementById('marker_popup_type').innerHTML = esterenMarker.marker_type.name;
                d.getElementById('marker_popup_faction').innerHTML = esterenMarker.faction ? esterenMarker.faction.name : '';
            }
        }
    };

    EsterenMap.prototype._mapOptions.CustomMarkerBaseOptionsEditMode = {
        popupIsSidebar: true,
        clickCallback: function(e){
            var marker = e.target,
                map = marker._esterenMap,
                esterenMarker = marker._esterenMarker,
                id = esterenMarker.id || marker.options.alt, clickedTime
            ;

            clickedTime = Date.now();
            if (this._clickedTime && clickedTime - this._clickedTime < 500) {
                marker._delete();
                e.stopPropagation();
            }
            this._clickedTime = clickedTime;

            map.disableEditedElements();
            marker.dragging.enable();
            marker.showSidebar();
            marker._icon.classList.add('selected');
            map._editedMarker = marker;

            if (marker._sidebar.isVisible() && esterenMarker) {
                d.getElementById('marker_popup_name').value = esterenMarker.name;
                d.getElementById('marker_popup_type').value = esterenMarker.marker_type ? esterenMarker.marker_type.id : null;
                d.getElementById('marker_popup_faction').value = esterenMarker.faction ? esterenMarker.faction.id : "";

                $('#marker_popup_name').off('keyup').on('keyup', function(){
                    map._markers[id]._esterenMarker.name = this.value;
                    if (this._timeout) { clearTimeout(this._timeout); }
                    this._timeout = setTimeout(function(){ map._markers[id]._updateEM(); }, 1000);
                    return false;
                });
                $('#marker_popup_type').off('change').on('change', function(){
                    map._markers[id]._esterenMarker.marker_type = map.refData('markersTypes', this.value);
                    map._markers[id]._updateEM();
                    return false;
                });
                $('#marker_popup_faction').off('change').on('change', function(){
                    map._markers[id]._esterenMarker.faction = map.refData('factions', this.value);
                    map._markers[id]._updateEM();
                    return false;
                });
            }

        },
        dblclickCallback: function(e){
        },
        dragCallback: function(e) {
            var marker = e.target;
            marker.refreshRoutes();
        },
        dragendCallback: function(e) {
            var marker = e.target,
                latlng = marker.getLatLng();
            if (marker._esterenMarker) {
                marker._esterenMarker.latitude = latlng.lat;
                marker._esterenMarker.longitude = latlng.lng;
            }
            marker._updateEM();
        },
        addCallback: function(e){
            var marker = e.target,
                id = marker.options.alt;
            if (marker._esterenMap.editMode == true && id) {
                if (d.getElementById('marker_'+id+'_deleted')) {
                    d.getElementById('marker_'+id+'_deleted').value = 'false';
                } else {
                    $('<input type="hidden" value="false" />')
                        .attr({
                            'id':'marker_'+id+'_deleted',
                            'name':'marker['+id+'][deleted]'
                        }).appendTo('#inputs_container');
                }
            }
        }
    };

    /**
     * Ajoute un marqueur à la carte
     * @param latLng
     * @param leafletUserOptions
     * @param customUserOptions
     * @returns {EsterenMap}
     */
    EsterenMap.prototype.addMarker = function(latLng, leafletUserOptions, customUserOptions) {
        var _this = this,
            mapOptions = this._mapOptions,
            leafletOptions = this.cloneObject(mapOptions.LeafletMarkerBaseOptions),
            iconOptions = this.cloneObject(mapOptions.LeafletIconBaseOptions),
            id,option,optionTag,icon,iconHeight,iconWidth,initialIconHeight,initialIconWidth,
            marker,popup,popupContent,popupOptions, markerType;

        // Safety to be sure arguments are at least plain objects
        //   to avoid "cannot read property ... of undefined" errors.
        leafletUserOptions = leafletUserOptions || {};
        customUserOptions = customUserOptions || {};
        latLng = latLng || L.latLng(0, 0); // Default latlng to avoid problems

        // Merge Leaflet options
        if (leafletUserOptions) {
            leafletOptions = this.cloneObject(leafletOptions, leafletUserOptions);
        }

        // Merge EsterenMaps options
        if (customUserOptions.icon) {
            iconOptions = this.cloneObject(iconOptions, customUserOptions.icon);
        }

        while (d.getElementById('marker_'+this._mapOptions.maxMarkerId+'_name')) {
            this._mapOptions.maxMarkerId ++;
        }

        // Alt should contain the markers' ID
        if (!leafletOptions.alt) {
            id = this._mapOptions.maxMarkerId;
        } else {
            id = leafletOptions.alt;
        }
        while (d.getElementById('marker_'+id+'_name')) { id ++; }

        leafletOptions.alt = id;

        marker = L.marker(latLng, leafletOptions);

        marker._esterenMap = this;
        if (customUserOptions.esterenMarker) {
            marker._esterenMarker = customUserOptions.esterenMarker;
        } else {
            // Ici on tente de créer un nouveau marqueur
            marker._esterenMarker = this.esterenMarkerPrototype;
            marker._esterenMarker.marker_type = this.refData('markersTypes', 1);
        }

        markerType = marker._esterenMarker.marker_type;

        // Création d'une popup
        popupContent = customUserOptions.popupContent;
        if (!popupContent) {
            popupContent = mapOptions.LeafletPopupMarkerBaseContent;
        }
        if (popupContent && typeof popupContent === 'string') {
            popupOptions = mapOptions.LeafletPopupBaseOptions;
            if (typeof customUserOptions.popupOptions !== 'undefined') {
                popupOptions = this.cloneObject(popupOptions, customUserOptions.popupOptions);
            }
            if (customUserOptions.popupIsSidebar == true) {
                marker.bindSidebar(this._sidebar, popupContent);
            } else {
                popup = L.popup(popupOptions);
                popup.setContent(popupContent);
                marker.bindPopup(popup);
            }
        } else if (customUserOptions.popupContent && typeof customUserOptions.popupContent !== 'string') {
            console.error('popupContent parameter must be a string.');
        }

        // Application des events listeners
        for (option in customUserOptions) {
            if (customUserOptions.hasOwnProperty(option) && option.match(/Callback$/)) {
                marker.addEventListener(option.replace('Callback',''), customUserOptions[option]);
            }
        }

        // Ajout de l'icône au cas où
        if (markerType && markerType.web_icon) {
            iconOptions.iconUrl = markerType.web_icon;

            initialIconWidth = markerType.icon_width;
            initialIconHeight = markerType.icon_height;
            iconWidth = initialIconWidth;
            iconHeight = initialIconHeight;
            if (iconWidth || iconHeight) {
                // N'applique une icône QUE si la hauteur ou la largeur sont définies

                if (!iconWidth) {
                    // Calcule la largeur de l'icône à partir du ratio largeur/largeur_icone si celle-ci n'est pas définie
                    iconWidth = parseInt(initialIconWidth / (initialIconHeight / iconHeight));
                }
                if (!iconHeight) {
                    // Calcule la hauteur de l'icône à partir du ratio largeur/largeur_icone si celle-ci n'est pas définie
                    iconHeight = parseInt(initialIconHeight / (initialIconWidth / iconWidth));
                }

                iconOptions.iconSize = [iconWidth, iconHeight];

                iconOptions.iconAnchor = [
                    markerType.icon_center_x ? markerType.icon_center_x : (iconWidth / 2),
                    markerType.icon_center_y ? markerType.icon_center_y : (iconHeight / 2)
                ];

                iconOptions.popupAnchor = [
                    0,
                    - (iconHeight / 2)
                ];

                icon = L.icon(iconOptions);
                marker.setIcon(icon);
            }
        }

        this._drawnItems.addLayer(marker);

        option = 'markerType'+(customUserOptions.markerType?customUserOptions.markerType:'1');
        if (marker._icon.dataset) {
            marker._icon.dataset.leafletObjectType = option;
        }
        marker._icon.setAttribute('data-leaflet-object-type', option);

        marker._esterenRoutesStart = {};
        marker._esterenRoutesEnd = {};

        this._markers[id] = marker;

        return this;
    };

})(jQuery, L, document, window);