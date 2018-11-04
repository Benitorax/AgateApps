(function($, L, d, w) {

    EsterenMap.prototype.initDirections = function(){
        var control, _this = this;

        control = L.control.directions({}, this);

        this._directionsControl = control;

        control.addTo(this._map);

        return this;
    };

    L.Control.Directions = L.Control.extend({
        options: {
            position: 'topleft'
        },

        _pathColor: '#4444dd',

        _esterenMap: null,
        _controlDiv: null,
        _controlContent: null,
        _controlLink: null,
        _steps: [],

        _cntSet: false,
        _lstnSet: false,

        initialize: function (options, map) {
            // Constructeur
            if (!(map instanceof EsterenMap)) {
                console.error('An "EsterenMap" is required to initialize directions control.');
                return false;
            }
            L.setOptions(this, options);
            L.Control.prototype.initialize.call(this, options);
            this.map(map);

            return this;
        },

        map: function(map){
            if (map) {
                if (!(map instanceof EsterenMap)) {
                    console.error('You can only set an "EsterenMap" as map for directions control.');
                    return false;
                }
                this._esterenMap = map;

                return this;
            }

            return this._esterenMap;
        },

        highlightPath: function(){
            var path = this._steps,
                map = this._esterenMap,
                markers = map._markers,
                routes = map._polylines,
                i, l, step, marker, route, routeElement
            ;

            for (i = 0, l = path.length; i < l; i++) {
                step = path[i];
                marker = markers[step.id];
                route = step.route ? routes[step.route.id] : null;
                marker._icon.classList.add('selected');
                if (route) {
                    routeElement = route._path;
                    if (!routeElement.classList.contains('highlighted')) {
                        routeElement.setAttribute('stroke-width', parseInt(routeElement.getAttribute('stroke-width')||1) * 2);
                        route._oldColor = routeElement.getAttribute('stroke');
                        routeElement.setAttribute('stroke', this._pathColor);
                        routeElement.classList.add('highlighted');
                    }
                }
            }
        },

        cleanDirections: function(){
            var path = this._steps,
                map = this._esterenMap,
                markers = map._markers,
                routes = map._polylines,
                i, l, step, marker, route, routeElement
            ;

            if (path && path.length) {
                for (i = 0, l = path.length; i < l; i++) {
                    step = path[i];
                    marker = markers[step.id];
                    route = step.route ? routes[step.route.id] : null;
                    marker._icon.classList.remove('selected');
                    if (route) {
                        routeElement = route._path;
                        if (routeElement.classList.contains('highlighted')) {
                            routeElement.setAttribute('stroke-width', parseInt(routeElement.getAttribute('stroke-width')||2) / 2);
                            routeElement.setAttribute('stroke', route._oldColor);
                            routeElement.classList.remove('highlighted');
                        }
                    }
                }
            }
            this._steps = [];
        },

        createContent: function(){
            if (this._cntSet) {
                console.error('Content has already been set for this direction panel.');
                return false;
            }

            var content,
                map = this._esterenMap,
                msgSend = typeof FORM_SUBMIT !== 'undefined' ? FORM_SUBMIT : 'MSG_CONTROL_DIRECTIONS_TITLE',
                directionsMsgTitle = typeof MSG_CONTROL_DIRECTIONS_TITLE !== 'undefined' ? MSG_CONTROL_DIRECTIONS_TITLE : 'MSG_CONTROL_DIRECTIONS_TITLE',
                directionsMsgStart = typeof MSG_CONTROL_DIRECTIONS_START !== 'undefined' ? MSG_CONTROL_DIRECTIONS_START : 'MSG_CONTROL_DIRECTIONS_START',
                directionsMsgEnd = typeof MSG_CONTROL_DIRECTIONS_END !== 'undefined' ? MSG_CONTROL_DIRECTIONS_END : 'MSG_CONTROL_DIRECTIONS_END',
                directionsMsgTransport = typeof MSG_CONTROL_TRANSPORTS !== 'undefined' ? MSG_CONTROL_TRANSPORTS : 'MSG_CONTROL_TRANSPORTS',
                directionsMsgNotFound = typeof MSG_CONTROL_DIRECTIONS_MARKER_NOT_FOUND !== 'undefined' ? MSG_CONTROL_DIRECTIONS_MARKER_NOT_FOUND : 'MSG_CONTROL_DIRECTIONS_MARKER_NOT_FOUND',
                $controlContent = $(this._controlContent)
            ;

            // Ajout des différents noeuds à l'objet Content
            // On persiste ici à utiliser le concept objet
            // pour être sûr que des listeners ne sont pas "perdus" en route

            content =
            '<div>' +
                '<div id="directions_wait_overlay"></div>' +
                '<h3 class="text-xxl">' + directionsMsgTitle + '</h3>' +
                '<form action="#" id="directions_form">' +
                    '<div class="input-field">' +
                        '<input type="text" name="start" id="directions_start" />' +
                        '<label for="directions_start">' + directionsMsgStart + '</label>' +
                        '<div class="directions_helper"></div>' +
                    '</div>' +
                    '<div class="input-field">' +
                        '<input type="text" name="end" id="directions_end" />' +
                        '<label for="directions_end">' + directionsMsgEnd + '</label>' +
                        '<div class="directions_helper"></div>' +
                    '</div>' +
                    '<div>' +
                        '<h3 class="text-xxl mt10">' + directionsMsgTransport + '</h3>' +
                        '<div id="directions_transport"></div>' +
                    '</div>' +
                    '<button class="btn" type="submit">' + msgSend + '</button>' +
                    '<div id="directions_message"></div>' +
                '</div>' +
                '</form>' +
            '</div>'
            ;

            $controlContent.html('').append(content);

            $controlContent.find('.directions_helper').on('click', function(e){
                var input, directionsHelper, li = e.target;
                if (li.tagName.toLowerCase() === 'li') {
                    directionsHelper = li.parentElement.parentElement;
                    if (!directionsHelper.classList.contains('directions_helper')) {
                        throw 'Invalid element name retrieved from the directions helper element.';
                    }
                    input = directionsHelper.parentElement.querySelector('input');
                    input.value = li.innerHTML;
                    directionsHelper.innerHTML = '';
                    L.DomEvent.stop(e);
                }
            });

            var transportsOptions = '';
            $.each(map._mapOptions.data.references.transports, function(i, e){
                transportsOptions +=
                    '<div>' +

                        '<label for="directions_transport_' + e.id + '">' +
                            '<input name="directions_transport" type="radio" id="directions_transport_' + e.id + '" value="' + e.id + '">' +
                                '<span>' + e.name + '</span>' +
                            '</label>' +
                    '</div>'
                ;
            });

            $controlContent.find('#directions_transport').html(transportsOptions);

            $controlContent.find('#directions_start,#directions_end')
                .on('keyup', function(){
                    var $this = $(this),
                        html = '',
                        value = this.value.trim(),
                        directionsHelper = $this.parent().find('.directions_helper'),
                        marker;

                    // If user entered a value, we create a list based on all markers.
                    // We are matching markers only with a regexp.
                    // Regexp curious users will be pleased to test this themselves... :D
                    if (value) {
                        for (marker in map._markers) {
                            if (!map._markers.hasOwnProperty(marker)) { continue; }
                            marker = map._markers[marker]._esterenMarker;
                            if (marker.name.match(new RegExp(value, 'gi'))) {
                                html += '<li data-marker-id="'+marker.id+'">'+marker.name+'</li>';
                            }
                        }
                    }

                    // And finally add the list to the directions helper.
                    if (html) {
                        html = '<ul class="list-unstyled">'+html+'</ul>';
                    }

                    directionsHelper.html(html);
                })
                .on('keypress', function(event){
                    var keyCode = event.keyCode ? event.keyCode : event.which,
                        directionsHelper = $(this).parent().find('.directions_helper')
                    ;

                    // If pressed "enter", we click on the first element.
                    if (keyCode === 13) {
                        directionsHelper.find('ul > li').first().click();

                        // Avoid submitting the form at the same time, most of the time it triggers errors.
                        L.DomEvent.preventDefault(event);
                        return false;
                    }
                })
            ;

            $controlContent[0].querySelector('#directions_form').addEventListener('submit', function(e){
                var data = $(this).serializeArray(),
                    markers = map._markers,
                    control = map._directionsControl,
                    submitButton = this.querySelector('[type="submit"]'),
                    messageBox = d.getElementById('directions_message'),
                    overlay = d.getElementById('directions_wait_overlay'),
                    start = data.filter(function(e){return e.name==='start';})[0].value,
                    end = data.filter(function(e){return e.name==='end';})[0].value,
                    transport = data.filter(function(e){return e && e.name==='directions_transport';})[0],
                    markerStart, markerEnd, message
                ;

                if (transport) {
                    transport = transport.value || 1;
                } else {
                    transport = 1;
                }

                // Prevents submitting this form right away
                L.DomEvent.stop(e);

                if (submitButton.hasAttribute('disabled')) {
                    return false;
                }

                submitButton.setAttribute('disabled', 'disabled');
                control.cleanDirections();

                for (var marker in markers) {
                    if (!markers.hasOwnProperty(marker)) { continue; }
                    marker = markers[marker]._esterenMarker;
                    if (!markerStart && marker.name === start) {
                        markerStart = marker.id;
                    }
                    if (!markerEnd && marker.name === end) {
                        markerEnd = marker.id;
                    }
                    if (markerStart && markerEnd) {
                        break;
                    }
                }

                if (markerStart && markerEnd) {
                    overlay.style.display = "block";
                    map._load({
                        url: map._mapOptions.apiUrls.directions.replace('{from}', markerStart).replace('{to}', markerEnd),
                        xhr_name: 'directions_calculate',
                        data: {
                            'transport': transport
                        },
                        callback: function(response) {
                            if (response.error && response.message) {
                                messageBox.innerHTML = response.message;
                                setTimeout(function(){$('#directions_message').text('');}, 3000);
                            } else if (response.path && response.path.length) {
                                control._steps = response.path;
                                control.highlightPath();
                                map._map.fitBounds(L.latLngBounds(response.bounds.northEast, response.bounds.southWest));
                                messageBox.innerHTML = response.path_view;
                            } else if (response.path_view) {
                                messageBox.innerHTML = response.path_view;
                            }
                        },
                        callbackComplete: function() {
                            submitButton.removeAttribute('disabled');
                            overlay.style.display = '';
                        },
                        callbackError: function() {
                            submitButton.removeAttribute('disabled');
                            overlay.style.display = '';
                        }
                    });

                    return;
                } else if (markerStart || markerEnd || start || end) {
                    message = '';
                    if (!markerStart && start) {
                        message += start;
                    }
                    if (!markerEnd && end) {
                        message += (message?', ':'') + end;
                    }

                    messageBox.innerHTML = directionsMsgNotFound + ' ' + message;
                }

                submitButton.removeAttribute('disabled');
                overlay.style.display = '';

                return false;
            });

            this._cntSet = true;

            return this;
        },

        show: function(){
            this._controlContent.style.display = 'block';
            this._controlContent.parentElement.classList.add('expanded');
        },

        hide: function(){
            this._controlContent.style.display = 'none';
            this._controlContent.parentElement.classList.remove('expanded');
        },

        toggle: function(){
            var controlContent = this._controlContent;
            if ($(controlContent).is(':visible')) {
                this.hide();
            } else {
                this.show();
            }
        },

        onAdd: function () {
            var controlDiv, link, textTitle, controlContent, _this = this;

            if (!(this._esterenMap instanceof EsterenMap)) {
                console.error('Directions control can only be added to a LeafletMap with an EsterenMap. Have you forgotten the second argument to the constructor ?');
                return false;
            }

            textTitle = (typeof(MSG_CONTROL_DIRECTIONS_TITLE) !== 'undefined') ? MSG_CONTROL_DIRECTIONS_TITLE : 'Calcul d\'itinéraire';

            controlDiv = L.DomUtil.create('div', 'leaflet-draw-section leaflet-directions-control');
            controlDiv.id = "leaflet-directions-control";

            controlContent = L.DomUtil.create('div', 'leaflet-directions-control-content', controlDiv);
            controlContent.id = 'directions_control_content';

            // Event that will remove directions search results when we click on the directions block
            controlContent.addEventListener('click', function (e) {
                var list, i, l;

                // Don't trigger on "<li>", they're only used to display direction search results.
                if (e.target.tagName.toLowerCase() !== 'li') {
                    list = controlContent.querySelectorAll('.directions_helper');

                    for (i = 0, l = list.length; i < l; i++) {
                        list[i].innerHTML = '';
                    }
                }
            });

            link = L.DomUtil.create('a', 'map-control-toggle', controlDiv);
            link.id = 'leaflet-directions-toggle';
            link.style.backgroundImage = 'none';
            link.href = "#";
            link.innerHTML = '<i class="fa fa-location-arrow" style="font-size: 16px;"></i>';
            link.setAttribute('data-tooltip', textTitle);
            $(link).tooltip({
                "delay": 25,
                "position" : "right",
                "html": "true"
            });

            // Listener DirectionsControl
            L.DomEvent
                .addListener(link, 'click', function () {
                    _this.toggle();

                    return false;
                })
                .addListener(link, 'click', L.DomEvent.stopPropagation)
            ;

            // Listeners DirectionsControl disable
            this._esterenMap._map.on('click', function(){
                _this.hide();
            });

            this._controlDiv = controlDiv;
            this._controlLink = link;
            this._controlContent = controlContent;

            this.createContent();

            L.DomEvent
                .on(this._controlDiv, 'click', L.DomEvent.stopPropagation)
                .on(this._controlDiv, 'mousedown', L.DomEvent.stopPropagation)
                .on(this._controlDiv, 'touchstart', L.DomEvent.stopPropagation)
                .on(this._controlDiv, 'dblclick', L.DomEvent.stopPropagation)
                .on(this._controlDiv, 'mousewheel', L.DomEvent.stopPropagation)
                .on(this._controlDiv, 'MozMousePixelScroll', L.DomEvent.stopPropagation)
            ;

            return controlDiv;
        }
    });

    /**
     * @param {object} options
     * @param {EsterenMap} map
     * @returns {L.Control.Directions}
     */
    L.control.directions = function (options, map) {
        return new L.Control.Directions(options, map);
    };

})(jQuery, L, document, window);
