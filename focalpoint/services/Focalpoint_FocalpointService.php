<?php

namespace Craft;

/** 
  * Service to get cropped image by focus point 
  * 
  * Unfortunately, at the moment this seems not to be possible by calling Crafts own image transform functions, 
  * so implemented with GD and a custom output folder under the public directory for now.
  *
  */
class Focalpoint_FocalpointService extends BaseApplicationComponent {

    private $publicDir = null;

    public function __construct(){
        $this->publicDir = CRAFT_BASE_PATH . '../public/';
    }

	// crop image relative to selected focus point
	public function getCroppedImage($image, $w, $h, $x=false, $y=false) {
		$imageUrl = $image->url;
		$imagePath = $this->publicDir . $imageUrl;

		if (!file_exists($imagePath)) {
			return false;
		}

		list($imgW, $imgH) = getimagesize($imagePath);

		if ($w == $imgW && $h == $imgH) {
			return $imageUrl;
		}

		$x = false;
		$y = false;

		if (isset($image->focalPoint)) {
			if (isset($image->focalPoint['x'])) {
				$x = $image->focalPoint['x'];
			}
			if (isset($image->focalPoint['y'])) {
				$y = $image->focalPoint['y'];
			}			
		}

		if ($x === false) {
			$x = round($imgW / 2);
		}
		if ($y === false) {
			$y = round($imgH / 2);
		}		

		$wOut = $w;
		$hOut = $h;

		$imgRatio = $imgW / $imgH;
		$cropRatio = $w / $h;

		if ($w > $h && $imgRatio < $cropRatio) { // crop as portrait
			$cropScale = $imgW / $w;

			$w *= $cropScale;
			$h *= $cropScale;

			$cropX = 0;
			$cropY = $y - $h/2;

			// focus point bounds checking
			if ($cropY < 0) {
				$cropY = 0;
			}
			if ($cropY + $h > $imgH) {
				$cropY = $imgH - $h;
			}
		}
		else {  // crop as landscape
			$cropScale = $imgH / $h;

			$w *= $cropScale;
			$h *= $cropScale;

			$cropY = 0;
			$cropX = $x - $w/2;

			// focus point bounds checking
			if ($cropX < 0) {
				$cropX = 0;
			}
			if ($cropX + $w > $imgW) {
				$cropX = $imgW - $w;
			}
		}
		return $this->cropImage($imagePath, $cropX, $cropY, $w, $h, $wOut, $hOut);	
	}

	// crop and resize image, store result
	protected function cropImage($imagePath, $x, $y, $cropW, $cropH, $outW, $outH) {
		if (!file_exists($imagePath)) return false;

		$updateTime = filemtime($imagePath);
		$imgHash = md5($imagePath . $x . $y . $cropW . $cropH . $outW . $outH . $updateTime);
		$cropOutputDir = $this->getOutputDir();
		$outputDir = $this->publicDir . $cropOutputDir;

		if (!file_exists($outputDir)) {
			mkdir($outputDir);
		}

		$fileName = $imgHash . '.jpg';

		$path = $outputDir . '/' . $fileName;

		if (!file_exists($path)) {

            $src_type = exif_imagetype( $imagePath );

            switch ( $src_type ){
                case IMAGETYPE_JPEG:
                    $src = imagecreatefromjpeg($imagePath);
                break;

                case IMAGETYPE_PNG:
                    $src = imagecreatefrompng($imagePath);
                break;
            }

			if ( !isset( $src ) || !$src)
                return false;

            $w = $cropW;
			$h = $cropH;

			$out = imagecreatetruecolor($outW, $outH);

			imagecopyresampled($out, $src, 0, 0, $x, $y, $outW, $outH, $w, $h);

			imagejpeg($out, $path, $this->getJpgQuality());
			imagedestroy($out);
			imagedestroy($src);
		}
		if (!file_exists($path)) {
			return false;
		}
		return $cropOutputDir . '/' . $fileName;
	}

	public function getOutputDir() {
		$plugin = craft()->plugins->getPlugin('focalpoint');
		$settings = $plugin->getSettings();
		return $settings->storageFolder;
	}

	public function getJpgQuality() {
		$plugin = craft()->plugins->getPlugin('focalpoint');
		$settings = $plugin->getSettings();
		return $settings->jpgQuality;
	}		

}