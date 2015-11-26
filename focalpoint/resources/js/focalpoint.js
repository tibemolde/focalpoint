var ImgFocusPicker = function(prefix) {
	var picker = document.getElementById(prefix + 'focus_pick');
	var container = document.getElementById(prefix + 'focus_picker');

	var width = jQuery(container).width();
	var height = jQuery(container).height();

	var pickWidth = 18;

	var bound = {
		xmin: 0,
		ymin: 0,
		xmax: width-pickWidth,
		ymax: height-pickWidth
	};

	var setPosition = function(x, y) {
		document.getElementById(prefix + 'focal_x_val').value = x + pickWidth/2;
		document.getElementById(prefix + 'focal_y_val').value = y + pickWidth/2;

		picker.style.top = y + 'px';
		picker.style.left = x + 'px';
	};

	var getPosition = function() {
		var x = document.getElementById(prefix + 'focal_x_val').value;
		var y = document.getElementById(prefix + 'focal_y_val').value;

		picker.style.left = (x - pickWidth/2) + 'px';
		picker.style.top = (y - pickWidth/2) + 'px';
	};

	var dragging = false;
	var dragStart = function() {
		dragging = true;
		window.addEventListener('mousemove', dragMove);
		window.addEventListener('mouseup', dragEnd);
	};
	var dragMove = function(e) {
		if (dragging) {
			var boxBounds = container.getBoundingClientRect();

			var offset = {
				x: boxBounds.left,
				y: boxBounds.top
			};			
			var x = e.clientX - (pickWidth/2) - offset.x + container.scrollLeft;
			var y = e.clientY - (pickWidth/2) - offset.y + container.scrollTop;

			if (y < bound.ymin) {
				y = bound.ymin;
			}
			if (x < bound.xmin) {
				x = bound.xmin;
			}
			if (y > bound.ymax) {
				y = bound.ymax;
			}
			if (x > bound.xmax) {
				x = bound.xmax;
			}
			setPosition(x, y);
		}
	};
	var dragEnd = function() {
		dragging = false;
		window.removeEventListener('mousemove', dragMove);
		window.removeEventListener('mouseup', dragEnd);
	};
	picker.addEventListener('mousedown', dragStart);

	document.getElementById(prefix + 'focus_img').addEventListener('mousemove', function(e) {
		e.preventDefault();
	});

	getPosition();
	picker.style.display = 'block';

};