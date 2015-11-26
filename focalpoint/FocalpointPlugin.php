<?php

namespace Craft;

class FocalpointPlugin extends BasePlugin {

    public function getName() {
        return Craft::t('Focal point');
    }

    public function getVersion() {
        return '0.1';
    }

    public function getDeveloper() {
        return 'Kristian Johannessen';
    }

    public function getDeveloperUrl() {
        return 'https://github.com/tibemolde';
    }

    public function hasCpSection() {
        return false;
    }

    protected function defineSettings() {
        return array(
            'jpgQuality' => array(AttributeType::Number, 'default' => 85),
            'storageFolder' => array(AttributeType::String, 'default' => 'uploads/focuscropped')
        );
    }

    public function getSettingsHtml() {
        return craft()->templates->render('focalpoint/_settings', array(
            'settings' => $this->getSettings()
        ));
    }
}
