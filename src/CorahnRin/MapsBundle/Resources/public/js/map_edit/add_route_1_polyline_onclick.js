document.addRoutePolylineOnClick = function () {
    if (this.classList.contains('active')) {
        // Aucun effet si le polyline courant est déjà sélectionné
        // Aucun effet non plus si le polyline n'a pas pour parent "map_routes"
        return false;
    }

    // Suppression de la classe "active" des autres polylines
    var list = document.getElementsByTagNameNS('http://www.w3.org/2000/svg', 'polyline'), len = list.length;
    for (var i = 0; i < len; i++){
        if (list[i].id !== this.id) {
            list[i].classList.remove('active');
        }
    }

    // Ajout de la classe "active" au polyline courant
    this.classList.add('active');

    console.info(this);

    var inputNameId = "input_"+this.id.replace('_polyline', '_name'),
        inputTarget = document.getElementById(inputNameId),
        inputChange = document.getElementById('map_input_change');

    // Récupération de la valeur correcte dans l'input
    inputChange.setAttribute('data-input-id', inputNameId);
    inputChange.removeAttribute('disabled');
    inputChange.value = inputTarget.value;

    document.getElementById('map_delete_element').removeAttribute('disabled');
    document.getElementById('map_delete_element').setAttribute('data-element-id', this.id);
    inputChange.focus();
};


(function(){
    // Applique la fonction ci-dessus à tous les polylines
    var list = document.getElementsByTagNameNS('http://www.w3.org/2000/svg', 'polyline'), len = list.length;
    for (var i = 0; i < len; i++){
        list[i].onclick = document.addRoutePolylineOnClick;
    }
})();