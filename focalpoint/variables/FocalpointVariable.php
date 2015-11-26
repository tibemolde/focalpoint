<?php

namespace Craft;

class FocalpointVariable {
	
	public function getImgCrop($img, $w, $h) {
		return craft()->focalpoint_focalpoint->getCroppedImage($img, $w, $h);
	}
}
