(function(b){var a=document.getElementById("map_add_zone");var c=a.getAttribute("data-map-container")?a.getAttribute("data-map-container"):"map_container";document.addZonePinDraggableObject={start:function(h,g){document.addZoneMapContainerOffset=b("#"+c).offset();g.helper.css("position","absolute");var d=parseInt(h.clientX-document.addZoneMapContainerOffset.left+window.pageXOffset);var i=parseInt(h.clientY-document.addZoneMapContainerOffset.top+window.pageYOffset);var f={};document.addZoneMovingPinIndex=b('[data-target-polygon="'+g.helper.attr("data-target-polygon")+'"]').index(this);document.addZonePolygon=document.getElementById(g.helper.attr("data-target-polygon"));document.addZoneCoordinates=document.addZonePolygon.getAttribute("points").split(" ");f.left=d-document.addZoneCoordinates[document.addZoneMovingPinIndex].split(",")[0];f.top=i-document.addZoneCoordinates[document.addZoneMovingPinIndex].split(",")[1];document.addZoneMovingPinOffset=f},drag:function(h,g){document.addZoneMapContainerOffset=b("#"+c).offset();g.helper.css("position","absolute");var j=document.addZoneCoordinates.concat([]);var f=document.addZoneMovingPinIndex;var d=parseInt(h.clientX-document.addZoneMapContainerOffset.left+window.pageXOffset-document.addZoneMovingPinOffset.left);var i=parseInt(h.clientY-document.addZoneMapContainerOffset.top+window.pageYOffset-document.addZoneMovingPinOffset.top);j[f]=d+","+i;document.addZonePolygon.setAttribute("points",j.join(" "))},stop:function(f,d){document.addZoneMapContainerOffset=b("#"+c).offset();d.helper.css("position","absolute");b(document.getElementById("input_"+document.addZonePolygon.id)).val(document.addZonePolygon.getAttribute("points"));document.addZoneMovingPinIndex=null;document.addZonePolygon=null;document.addZoneCoordinates=null;document.addZoneMovingPinOffset=null}};b(".map-icon-target").draggable(document.addZonePinDraggableObject);if(document.getElementById("map_dont_move")){document.getElementById("map_dont_move").onclick=function(){var d=this.getAttribute("data-active");if(d==="true"){d="false";document.corahn_rin_map.allowMove(true);this.parentNode.classList.remove("active");this.children[0].classList.remove("text-danger");this.children[0].classList.add("text-success")}else{d="true";document.corahn_rin_map.allowMove(false);this.parentNode.classList.add("active");this.children[0].classList.add("text-danger");this.children[0].classList.remove("text-success")}this.setAttribute("data-active",d);return false}}if(document.getElementById("map_add_zone")){document.getElementById("map_add_zone").onclick=function(){var j=this,o,h,e,n,f=document.getElementById(this.getAttribute("data-zones-container")?this.getAttribute("data-zones-container"):"map_zones"),g=document.getElementById(this.getAttribute("data-map-container")?this.getAttribute("data-map-container"):"map_container"),m=document.addZonePolygonId?document.addZonePolygonId:0,d=document.addZonePolygonIdFull?document.addZonePolygonIdFull:"",k=this.getAttribute("data-active");if(!document.addZonePinIcon){h=document.createElement("span");h.classList.add("glyphicon");h.classList.add("icon-screenshot");h.classList.add("map-icon-target");h.style.position="absolute";document.addZonePinIcon=h;h=null}if(k==="true"){k="false";this.parentNode.classList.remove("active");if(document.addZoneCoordinates.length>2&&document.addZonePolygon){document.addZonePolygon.setAttribute("points",document.addZoneCoordinates.join(" "));var h=document.createElement("input");h.id="input_"+document.addZonePolygonIdFull;h.type="hidden";h.name=document.addZonePolygonIdFull;h.value=document.addZoneCoordinates.join(" ");b(g).parents("form").append(h);h=null}else{document.addZonePolygon.parentNode.removeChild(document.addZonePolygon)}document.addZoneCoordinates=[];document.addZonePolygon=null;e=document.querySelectorAll("polygon:not([points])");n=e.length;for(h=0;h<n;h++){e[h].parentNode.removeChild(e[h])}}else{k="true";this.parentNode.classList.add("active");while(document.getElementById("map_add_zone_polygon["+m+"]")){m++}d="map_add_zone_polygon["+m+"]";o=document.createElementNS("http://www.w3.org/2000/svg","polygon");o.id=d;f.appendChild(o);document.addZoneCoordinates=[];document.addZonePolygon=o;document.addZonePolygonIdFull=d;document.addZoneMapContainerOffset=b(g).offset()}this.setAttribute("data-active",k);g.setAttribute("data-add-zone",k);b(f).height(b(g).height());g.onmousedown=k==="false"?null:function(i){g=this;if(g.getAttribute("data-add-zone")==="true"){document.addZoneMapContainerOffset=b(g).offset();g.base_coords=i.clientX+","+i.clientY;document.addZoneBaseTarget=i.target}};g.onmousemove=k==="false"?null:function(l){g=document.corahn_rin_map.container();if(this.getAttribute("data-add-zone")==="true"){if(this.getAttribute("data-add-zone")==="true"&&document.addZoneCoordinates.length){document.addZoneMapContainerOffset=b(this).offset();var i=parseInt(l.clientX-document.addZoneMapContainerOffset.left+window.pageXOffset)-1;var q=parseInt(l.clientY-document.addZoneMapContainerOffset.top+window.pageYOffset)-1;var p=document.addZoneCoordinates.concat([i+","+q]);document.addZonePolygon.setAttribute("points",p.join(" "))}}};g.onmouseup=k==="false"?null:function(q){g=this;if(g.getAttribute("data-add-zone")==="true"){var l=q.clientX+","+q.clientY;if(l===g.base_coords){document.addZoneMapContainerOffset=b(g).offset();console.info(document.addZoneBaseTarget);if(document.addZoneBaseTarget.classList.contains("map-icon-target")){var i=b(document.addZoneBaseTarget).position().left;var r=b(document.addZoneBaseTarget).position().top;console.info(i,r)}else{var i=parseInt(q.clientX-document.addZoneMapContainerOffset.left+window.pageXOffset);var r=parseInt(q.clientY-document.addZoneMapContainerOffset.top+window.pageYOffset)}var p=b(document.addZonePinIcon).clone()[0];p.setAttribute("data-target-polygon",document.addZonePolygonIdFull);p.style.left=i+"px";p.style.top=r+"px";g.appendChild(p);b(p).draggable(document.addZonePinDraggableObject);if(isNaN(i)||isNaN(r)){console.error("Error with points "+(isNaN(i)?"x":"y")+"\n event : ",q);return false}document.addZoneCoordinates.push(i+","+r);document.addZonePolygon.setAttribute("points",document.addZoneCoordinates.join(" "))}}};return false}}})(jQuery);