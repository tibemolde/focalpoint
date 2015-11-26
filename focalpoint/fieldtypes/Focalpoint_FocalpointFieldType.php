<?php

namespace Craft;

/**
 * Focalpoint fieldtype
 */
class Focalpoint_FocalpointFieldType extends BaseFieldType
{

    /**
     * Get the name of this fieldtype
     */
    public function getName() 
    {
        return Craft::t('Focalpoint');
    }

    /**
     * Get this fieldtype's column type.
     *
     * @return mixed
     */
    public function defineContentAttribute()
    {
        return AttributeType::Mixed;
    }

    public function prepValue($value)
    {
        return $value;
    }

    public function prepValueFromPost($value)
    {
        $value['x'] = round($value['x'] / $value['scale']);
        $value['y'] = round($value['y'] / $value['scale']);
        unset($value['scale']);
        return $value;
    }

    /**
     * Get this fieldtype's form HTML
     *
     * @param  string $name
     * @param  mixed  $value
     * @return string
     */
    public function getInputHtml($name, $value)
    {
        craft()->templates->includeCssResource('focalpoint/css/focalpoint.css');
        craft()->templates->includeJsResource('focalpoint/js/focalpoint.js');
        
        $id = craft()->templates->formatInputId($name);
        $assetId = $this->element->id;

        return craft()->templates->render('focalpoint/_fieldtypes/focalpoint', array(
            'id'        => $id,
            'name'      => $name,
            'asset_id'  => $assetId,
            'x_val'     => isset($value['x']) ? $value['x'] : false,
            'y_val'     => isset($value['y']) ? $value['y'] : false
        ));
    }
}
