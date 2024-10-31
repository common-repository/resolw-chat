(function(exports, d) {
    function domReady(fn, context) {
        function onReady(event) {
            d.removeEventListener("DOMContentLoaded", onReady);
            fn.call(context || exports, event);
        }
        function onReadyIe(event) {
            if (d.readyState === "complete") {
                d.detachEvent("onreadystatechange", onReadyIe);
                fn.call(context || exports, event);
            }
        }
        d.addEventListener && d.addEventListener("DOMContentLoaded", onReady) ||
        d.attachEvent      && d.attachEvent("onreadystatechange", onReadyIe);
    }
    exports.domReady = domReady;
})(window, document);
domReady(function (event) {

    resolwc_get_unit_data().then(response => {
        var unit = response;

        var wrapper = document.createElement('a');
        wrapper.setAttribute('id', 'resolwc_svg-bubble');
        wrapper.setAttribute("href", 'https://app.resolw.com/r/' + unit.mainLinkText);
    	wrapper.setAttribute('target', '_blank');
    	wrapper.setAttribute('rel', 'noopener noreferrer');
        document.getElementsByTagName('body')[0].appendChild(wrapper);

        var node = document.createElement('div');
        node.setAttribute("id", "resolwc_svg-bubble-div");
        wrapper.appendChild(node);


        var backSpan = document.createElement('div');
        backSpan.setAttribute('id', 'resolwc_linkBackgroundElement');
        node.appendChild(backSpan);

        var frontSpan = document.createElement('div');
        frontSpan.setAttribute('id', 'resolwc_linkForegroundElement');
        node.appendChild(frontSpan);

        var stripesSpan = document.createElement('div');
        stripesSpan.setAttribute('id', 'resolwc_svg-stripes-span');
        node.appendChild(stripesSpan);


		const svgBackNode = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
			svgBackNode.setAttributeNS(null, 'viewBox', '0 0 100 100');
			const gBack = document.createElementNS('http://www.w3.org/2000/svg', 'g');
				gBack.setAttributeNS(null, 'id', 'resolwc_backBubble');
			const gBackPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
				gBackPath.setAttributeNS(null, 'd', 'M43.6,85.2V100l14.8-14.8h8.7c14.7,0,26.5-11.9,26.5-26.5V26.5C93.6,11.9,81.8,0,67.1,0H35 C20.3,0,8.4,11.9,8.4,26.5v32.1c0,14.7,11.9,26.5,26.5,26.5H43.6');
				gBackPath.setAttributeNS(null, 'class', 'resolwc_link-background-color');
			gBack.appendChild(gBackPath);
			svgBackNode.appendChild(gBack);
		backSpan.appendChild(svgBackNode);

		const frontNodeSpan = document.createElement('span');
		frontNodeSpan.setAttributeNS(null, 'id', 'resolwc_svg-inner-front-span');
		const svgFrontNode = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
		svgFrontNode.setAttributeNS(null, 'viewBox', '0 0 100 100');

			const gFront = document.createElementNS('http://www.w3.org/2000/svg', 'g');
			gFront.setAttributeNS(null, 'id', 'resolwc_frontBubble');
			const gFrontPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
			gFrontPath.setAttributeNS(null, 'd', 'M43.6,85.2V70.4H35c-6.5,0-11.7-5.3-11.7-11.7V26.5c0-6.5,5.3-11.7,11.7-11.7h32.1c6.5,0,11.7,5.3,11.7,11.7 v32.1c0,6.5-5.3,11.7-11.7,11.7h-8.7L43.6,85.2');
			gFrontPath.setAttributeNS(null, 'class', 'resolwc_link-foreground-color');
			gFront.appendChild(gFrontPath);
			svgFrontNode.appendChild(gFront);
		frontNodeSpan.appendChild(svgFrontNode);
		frontSpan.appendChild(frontNodeSpan);


		const stripesInnerSpan = document.createElement('span');
		stripesInnerSpan.setAttributeNS(null, 'id', 'resolwc_svg-stripes-inner-span');
		const svgNode = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
		svgNode.setAttributeNS(null, 'viewBox', '0 0 100 100');

		const gStripes = document.createElementNS('http://www.w3.org/2000/svg', 'g');
			gStripes.setAttributeNS(null, 'id', 'resolwc_stripes');
			const gG = document.createElementNS('http://www.w3.org/2000/svg', 'g');
				const gGPath1 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
				gGPath1.setAttributeNS(null, 'd', 'M64.9,35.4H37.2c-1,0-1.7-0.8-1.7-1.7v-0.2c0-1,0.8-1.7,1.7-1.7h27.7c1,0,1.7,0.8,1.7,1.7v0.2 C66.6,34.7,65.8,35.4,64.9,35.4z');
				gGPath1.setAttributeNS(null, 'class', 'resolwc_link-stripes-color');
			gG.appendChild(gGPath1);

				const gGPath2 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
				gGPath2.setAttributeNS(null, 'd', 'M64.9,45.1H37.2c-1,0-1.7-0.8-1.7-1.7v-0.2c0-1,0.8-1.7,1.7-1.7h27.7c1,0,1.7,0.8,1.7,1.7v0.2 C66.6,44.3,65.8,45.1,64.9,45.1z');
				gGPath2.setAttributeNS(null, 'class', 'resolwc_link-stripes-color');
			gG.appendChild(gGPath2);

				const gGPath3 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
				gGPath3.setAttributeNS(null, 'd', 'M59.8,54.7H37.2c-1,0-1.7-0.8-1.7-1.7v-0.2c0-1,0.8-1.7,1.7-1.7h22.6c1,0,1.7,0.8,1.7,1.7V53 C61.5,54,60.7,54.7,59.8,54.7z');
				gGPath3.setAttributeNS(null, 'class', 'resolwc_link-stripes-color');
			gG.appendChild(gGPath3);
		gStripes.appendChild(gG);
		svgNode.appendChild(gStripes);
		stripesInnerSpan.appendChild(svgNode);
		stripesSpan.appendChild(stripesInnerSpan);


    }).catch(() => {});

})
function resolwc_get_unit_data() {
    return new Promise((resolve, reject) => {
        var data = {
            'action': 'resolwc_get_selected_unit_data'
        };
        jQuery.post(ajaxurl, data, function(response){
            if (response === 'xxx'){
                reject();
            } else {
                var parsed_response = JSON.parse(response);
                resolve(parsed_response);
            }
        });
    });
}