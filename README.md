Focalpoint for Craft - public beta
=====

[Squarespace style focal points](https://support.squarespace.com/hc/en-us/articles/205826028-Using-focal-points-to-crop-and-center-images) for Craft assets to get natural cropping for images to various formats.

![Focal point editing](https://cloud.githubusercontent.com/assets/209020/11421448/459457c8-9435-11e5-965a-aaef7cf69249.png "Focal point editing")

The image processing and the api is still a bit rough around the edges (GD only, no way to clear generated crop cache, should be a way to get an array of image sizes for srcset, etc... ), improvements are coming. Feel free to submit issues and/or pull requests.

Installation
---

- Install the plugin (the focalpoint folder)
- Setup the output folder in plugin settings, create this directory and make it writable by the web server if needed
- Add a focalpoint field with the handle **focalPoint** to your the fields of the asset source you want to get cropped images for.

Usage
---

To get the url to the cropped image, use the craft.focalpoint.getImgCrop method:

	{% set image = craft.assets().limit(1).first() %}
	{% set croppedImage = craft.focalpoint.getImgCrop(image, 720, 360) %}
	<img src="{{ transformedImage.url }}">

