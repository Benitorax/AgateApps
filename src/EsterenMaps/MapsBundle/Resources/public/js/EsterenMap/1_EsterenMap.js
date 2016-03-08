(function($, L, d, w){

    /**
     * @param {object} user_mapOptions
     * @returns {EsterenMap}
     * @this {EsterenMap}
     * @constructor
     */
    var EsterenMap = function (user_mapOptions) {

        // Données utilisées dans le scope de la classe
        var _this = this, ajaxD;

        // Force CANVAS
        w.L_PREFER_CANVAS = true;

        if (!user_mapOptions.id) {
            console.error('Map id must be defined');
            return _this;
        }

        // Force le clonage des options pour ne pas altérer le prototype
        this._mapOptions = this.options();

        if ( !(this instanceof EsterenMap) ) {
            console.error('Wrong scope check, incorrect instance.');
            w.wrongInstance = this;
            return _this;
        }

        if (!L) {
            console.error('Leaflet must be activated.');
            return this;
        }

        // Merge des options de base
        if (user_mapOptions){
            this._mapOptions = mergeRecursive(this._mapOptions, user_mapOptions);
        }

        if (!d.getElementById(this._mapOptions.container)) {
            console.error('Map could not initialize : wrong container id');
            return this;
        }

        this.loadSettings();

        return this;
    };

    /**
     * To be called ONLY after having loaded the settings.
     *
     * @returns {boolean}
     * @private
     */
    EsterenMap.prototype._initialize = function() {

        var drawnItems,sidebar, _this = this, mapOptions;

        if (this.initialized === true || d.initializedEsterenMap === true) {
            console.error('Map already set.');
            return false;
        }
        this.initialized = true;
        d.initializedEsterenMap = true;

        // Formatage de l'url d'API qui doit utiliser l'ID de la map
        this._mapOptions.apiUrls.tiles = this.options().apiUrls.tiles.replace('{id}', ''+this.options().id);
        mapOptions = this.options();

        // Reset du wrapper avant création de la map
        // Force la redimension du wrapper lors de la redimension de la page
        if (mapOptions.autoResize) {
            this.resetHeight();
        } else {
            this.resetHeight(mapOptions.containerHeight);
        }


        if (mapOptions.messageElementId) {
            this._messageElement = d.getElementById(mapOptions.messageElementId);
        }

        if (mapOptions.crs && !mapOptions.LeafletMapBaseOptions.crs && L.CRS[mapOptions.crs]) {
            mapOptions.LeafletMapBaseOptions.crs = L.CRS[mapOptions.crs];
        } else if (mapOptions.crs && !L.CRS[mapOptions.crs]) {
            console.warn('Could not find CRS "'+mapOptions.crs+'".');
        }

        // Création de la map
        this._map = L.map(mapOptions.container, mapOptions.LeafletMapBaseOptions);

        // Création du calque des tuiles
        //this._tileLayer = L.tileLayer(mapOptions.apiUrls.tiles, mapOptions.LeafletLayerBaseOptions);
        //this._map.addLayer(this._tileLayer);
        this._tileLayer = L.tileLayer.canvas(mapOptions.LeafletLayerBaseOptions);
        this._tileLayer.drawTile = function(canvas, tilePoint, zoom) {
            var context = canvas.getContext('2d'),
                img = document.createElement('img'),
                imgUrl = mapOptions.apiUrls.tiles,
                tileSize = mapOptions.LeafletLayerBaseOptions.tileSize,
                x = tilePoint.x,
                y = tilePoint.y
            ;
            imgUrl = imgUrl.replace('{x}', x);
            imgUrl = imgUrl.replace('{y}', y);
            imgUrl = imgUrl.replace('{z}', _this._map.getZoom());
            img.src = imgUrl;
            img.onload = function() {
                context.drawImage(img, 0, 0, tileSize, tileSize, 0, 0, tileSize, tileSize);
            };
        };
        this._map.addLayer(this._tileLayer);

        L.Icon.Default.imagePath = mapOptions.imgUrl.replace(/\/$/gi, '');

        // Ajout de la sidebar
        if (mapOptions.sidebarContainer && d.getElementById(mapOptions.sidebarContainer)) {
            sidebar = L.control.sidebar(mapOptions.sidebarContainer, {
                position: 'right',
                closeButton: true,
                autoPan: false
            });
            this._map.addControl(sidebar);
            this._map.on('click', function(){
                sidebar.hide();
            });
            this._sidebar = sidebar;
        }

        // Initialisation des filtres si demandé
        if (mapOptions.showFilters === true) {
            this.initFilters();
        }

        // Initialisation du calcul d'itinéraire si demandé
        if (mapOptions.showDirections === true) {
            this.initDirections();
        }

        // Initialize search engine
        if (mapOptions.showSearchEngine === true) {
            this.initSearch();
        }

        ////////////////////////////////
        ////////// Mode édition ////////
        ////////////////////////////////
        if (mapOptions.editMode == true) {
            this.activateLeafletDraw();
            this._map.on('click', function(){
                _this.disableEditedElements();
            });
        } else {
            // Doit contenir les nouveaux éléments ajoutés à la carte
            drawnItems = new L.LayerGroup();
            this._map.addLayer(drawnItems);
            this._drawnItems = drawnItems;
        }

        // Force le resize à chaque redimension de la page
        if (mapOptions.autoResize) {
            $(w).resize(function(){_this.resetHeight();});
        }

        if (mapOptions.loadedCallback) {
            mapOptions.loadedCallback.call(this);
        }

    };

    EsterenMap.prototype.message = function(message, type, messageElement, disappearTimeout) {
        var element;

        disappearTimeout = disappearTimeout || 4000;

        if (!messageElement) {
            if (this._messageElement) {
                messageElement = this._messageElement;
            } else {
                console.error('No correct element could be used to show a message.');
                return;
            }
        }

        element = d.createElement('div');
        element.className = 'alert alert-sm ib h';
        if (type) {
            element.className += ' alert-'+type;
        }

        element.innerHTML = message;

        messageElement.appendChild(element);

        setTimeout(function(){
            // Remove the "hiding" class so the element appears smoothly with css
            element.classList.remove('h');
        }, 10);
        setTimeout(function() {
            // Hide smoothly the element with css transitions
            element.className += ' h';
        }, disappearTimeout);
        setTimeout(function() {
            // Definitely remove the element from the flow
            messageElement.removeChild(element);
        }, disappearTimeout + 1000);
    };

    EsterenMap.prototype.disableEditedElements = function(){

        if (this._editedPolygon) {
            this._editedPolygon.disableEditMode();
        }
        if (this._editedPolyline) {
            this._editedPolyline.disableEditMode();
        }
        if (this._editedMarker) {
            this._editedMarker.disableEditMode();
        }

        this._editedPolygon = null;
        this._editedPolyline = null;
        this._editedMarker = null;
    };

    EsterenMap.prototype.refDatas = function(name, id) {
        var datas = this.cloneObject((this._refDatas['ref-datas'] ? this._refDatas['ref-datas'] : this._refDatas)), grep;
        if (name) {
            if (datas[name]) {
                if (!isNaN(id)) {
                    grep = $.grep(datas[name], function(element){return element.id == id;});
                    if (grep.length) {
                        datas = grep[0];
                    } else {
                        console.warn('No ref data with id "'+id+'" in "'+name+'"');
                        datas = {};
                    }
                } else {
                    datas = datas[name];
                }
            } else {
                console.warn('No ref data with name "'+name+'"');
                datas = null;
            }
        }
        return datas;
    };

    /**
     * Exécute une requête AJAX dans le but de récupérer des éléments liés à la map
     * via l'API
     *
     * @param {object|string} name Le type d'élément à récupérer. Si plusieurs éléments sont indiqués dans un tableau, chacun sera validé à partir des options "allowedElement", cela créera une requête plus précise. Exemple : ["maps","routes"]. Des nombres sont autorisés pour récupérer un ID.
     * @param {object|null} [datas] les options à envoyer à l'objet AJAX
     * @param {string|null} [method] La méthode HTTP. GET par défaut.
     * @param {function|null} [callback] Une fonction de callback à envoyer à la méthode "success" de l'objet Ajax.
     * @param {function|null} [callbackComplete] Idem que "callback" mais pour la méthode "complete"
     * @param {function|null} [callbackError] Idem que "callback" mais pour la méthode "error"
     * @returns {*}
     */
    EsterenMap.prototype._load = function(name, datas, method, callback, callbackComplete, callbackError) {
        var url, ajaxObject, i, c, xhr_name, xhr_object,
            otherParams = {},
            _this = this,
            mapOptions = this.options(),
            allowedMethods = ["GET", "POST", "PUT"]
        ;

        if ($.isPlainObject(name)) {
            xhr_name = name.xhr_name || null;
            datas = name.datas || name.data || datas;
            method = name.method || method;
            callback = name.callback || callback;
            callbackComplete = name.callbackComplete || callbackComplete;
            callbackError = name.callbackError || callbackError;
            name = name.uri || name ;
        }

        method = method ? method.toUpperCase() : "GET";
        if (allowedMethods.indexOf(method) === -1) {
            method = "GET";
            console.error('Wrong HTTP method for _load() method. Allowed : '+allowedMethods.join(', '));
            return false;
        }

        if (!datas) {
            datas = {};
        }

        // D'abord, on autorise les chaînes de caractères avec des "/".
        // Cela permet d'écrire des requêtes de ce type : EsterenMap._load('maps/1/markers')
        // On le vérifiera comme un tableau.
        if (typeof name === 'string' && name.indexOf('/') !== -1) {
            name = name.split('/');
        }

        if (name && typeof name === 'string') {
            // Dans le cas ou name est une chaîne de caractères,
            // elle doit exister dans mapAllowedElements en tant que clé,
            // et être passée à true (on peut la passer à "false" pour désactiver le chargement de certaines données)
            name = name.toLowerCase();
            if (this.mapAllowedElements[name] !== true) {
                console.error('Éléments à charger incorrects.');
                return false;
            }
        } else if (name && Object.prototype.toString.call( name ) === '[object Array]') {
            // Sinon, name doit être obligatoirement un array.
            if (this.mapAllowedElements[name[0]] !== true) {
                console.error('Éléments à charger incorrects.');
                return false;
            }
            name = name.join('/');
        } else if (!name) {
            console.error('Wrong uri sent to EsterenMap dynamic loader.');
            return false;
        }

        // On s'assure d'une sécurité maximale
        name = name.toString();

        url = mapOptions.apiUrls.base.replace(/\/$/gi, '') + '/' + name;

        ajaxObject = {
            url: url,
            type: method,
            dataType: 'json',
            xhrFields: {
                 withCredentials: true
            },
            crossDomain: true,
            contentType: method === 'GET' ? "application/x-www-form-urlencoded" : "application/json",
            jsonp: false,
            data: method === 'GET' ? datas : JSON.stringify(datas ? datas : {})
        };
        if (typeof(callback) === 'function') {
            ajaxObject.success = function(response) {
                callback.call(_this, response);
            }
        }
        if (typeof(callbackComplete) === 'function') {
            ajaxObject.complete = function(){
                callbackComplete.call(_this);
            }
        }
        if (typeof(callbackError) === 'function') {
            ajaxObject.callbackError = function(){
                callbackError.call(_this);
            }
        }

        if (xhr_name && this._xhr_saves[xhr_name]) {
            this._xhr_saves[xhr_name].abort();
        }

        xhr_object = $.ajax(ajaxObject);

        if (xhr_name) {
            this._xhr_saves[xhr_name] = xhr_object;
        }

        return this;
    };

    EsterenMap.prototype.loadSettings = function(){
        var ajaxD = {}, _this = this;

        if (this._mapOptions.editMode == true) {
            ajaxD.editMode = true;
        }

        return this._load(
            ["maps","settings",this.options().id],
            ajaxD,
            "GET",
            function(response){
                //callback "success"
                _this.mapAllowedElements.settings = false;// Désactive les settings une fois chargés
                if (response.settings) {
                    _this._mapOptions = mergeRecursive(_this._mapOptions, response.settings);
                    _this._initialize();
                } else {
                    console.error('Map couldn\'t initialize because settings response was not correct.');
                }
            },
            null, //callback "Complete"
            function(){
                //callback "Error"
                console.error('Error while loading settings');
            }
        );
    };

    EsterenMap.prototype.loadMarkers = function(){
        var mapOptions = this.options();
        return this._load(["maps",mapOptions.id,"markers"], null, null, mapOptions.loaderCallbacks.markers);
    };

    EsterenMap.prototype.loadRoutes = function(){
        var mapOptions = this.options();
        return this._load(["maps",mapOptions.id,"routes"], null, null, mapOptions.loaderCallbacks.routes);
    };

    EsterenMap.prototype.loadZones = function(){
        var mapOptions = this.options();
        return this._load(["maps",mapOptions.id,"zones"], null, null, mapOptions.loaderCallbacks.zones);
    };

    EsterenMap.prototype.loadTransports = function(callback){
        var _this = this;
        if  (this._transports) {
            if (callback) {
                callback({"transports": this._refDatas['transports']});
            }
            return this._transports;
        }
        return this._load({
            uri: "transports",
            type: "GET",
            callback: function(response){
                if (response.transports) {
                    _this._transports = response.transports;
                } else {
                    console.warn('No transports could be loaded...');
                }
                if (callback) {
                    callback(response);
                }
            }
        });
    };

    EsterenMap.prototype.loadRefDatas = function(callback){
        var _this = this,
            refDatasService = "ref-datas",
            finalCallback;

        if (this._refDatas) {
            // Si les données ont déjà été chargées, on va simplement exécuter callback
            // Avec un tableau similaire
            var d = {};
            d[refDatasService] = this.refDatas();
            callback.call(this, d);
            return this;
        }

        // Ici, on force la surcharge de l'argument "callback"
        // Cela dans le but de permettre de définir les données de référence dans l'objet EsterenMap
        // à partir du moment où elles l'ont été au moins une fois.
        // Elles seront de facto rechargées, sans requête AJAX
        finalCallback = function(response) {
            _this._refDatas = response;
            _this._markersTypes = response[refDatasService].markersTypes;
            _this._routesTypes = response[refDatasService].routesTypes;
            _this._zonesTypes = response[refDatasService].zonesTypes;
            callback.call(_this, response);
        };
        return this._load(["maps",refDatasService], null, "GET", finalCallback);
    };

    /**
     * Récupère les options de l'objet EsterenMap
     * @this {EsterenMap}
     * @returns {Object} [this.mapOptions]
     */
    EsterenMap.prototype.options = function() {
        return this.cloneObject(this._mapOptions);
    };

    /**
     * Renvoie un clone d'un objet passé en paramètre
     * Si obj2 est spécifié, obj2 remplacera les données d'obj1
     *
     * @param {object} obj1 Le premier objet
     * @param {object} [obj2] Le deuxième objet
     * @returns {object}
     */
    EsterenMap.prototype.cloneObject = function(obj1, obj2){
        var newObject;

        // Crée un nouvel objet
        newObject = $.extend(true, {}, obj1);

        if (obj2) {
            // Fusionne le deuxième avec le premier objet
            newObject = $.extend(true, {}, newObject, obj2);
        }

        return newObject;
    };

    /**
     * Redimensionne le conteneur de la carte en fonction de certaines données du layout
     * Si une hauteur est envoyée en paramètre, elle est directement affectée.
     * Sinon, c'est une hauteur estimée selon le contenant et le reste de la page
     * @param height La hauteur en pixels
     * @returns {EsterenMap}
     */
    EsterenMap.prototype.resetHeight = function(height) {
        // Remet la valeur de la hauteur de façon correcte par rapport au navigateur.
        if (height) {
            $(d.getElementById(this.options().container)).height(height);
        } else {
            $(d.getElementById(this.options().container)).height(
                  $(w).height()
                - $('#footer').outerHeight(true)
                - $('#navigation').outerHeight(true)
                - $('#map_edit_menu').outerHeight(true)
                //- 40
            );
        }
        return this;
    };

    w.EsterenMap = EsterenMap;

})(jQuery, L, document, window);
