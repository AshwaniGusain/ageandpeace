<?php

namespace Snap\Form\Inputs;

use Grimzy\LaravelMysqlSpatial\Types\Point;
use Snap\Support\Helpers\GoogleHelper;
use Snap\Ui\Traits\AttrsTrait;
use Snap\Ui\Traits\VueTrait;

class Coordinates extends BaseInput
{
    use VueTrait;
    use AttrsTrait;

    protected $view = 'form::inputs.coordinates';

    protected $scripts = [
        'assets/snap/js/components/form/CoordinatesInput.js',
        'assets/snap/vendor/leaflet/leaflet.js',
    ];

    protected $styles = [
        'assets/snap/vendor/leaflet/leaflet.css',
    ];

    protected $vue = 'snap-coordinates-input';

    protected $geoLocationCallback = null;

    protected $data = [
        'attrs' => [
            ':show-map'      => 'true',
            'latitude-name'  => 'latitude',
            'longitude-name' => 'longitude',
            ':latitude'      => null,
            ':longitude'     => null,
            ':map-zoom'      => 13,
            'map-width'     => '100%',
            'map-height'    => '200px',
            // http://leaflet-extras.github.io/leaflet-providers/preview/index.html
            // 'map-tiles-url' => 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            'map-tiles-url'         => 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png',
            ':map-options'    => [
                'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            ],
        ],
    ];

    public function initialize()
    {
        // This isn't necessary since we have a hidden field taking care of the true value, but will leave here as a reference.
        $this->setPostProcess(function ($value, $input, $request) {
            if ($request->input($this->key)) {
                $this->postProcess($request);
            }
        });
    }

    public function postProcess($request)
    {
        $lat = (float) $request->get($this->getAttr('latitude-name'));
        $lng = (float) $request->get($this->getAttr('longitude-name'));

        if (empty($lat) || empty($lng)) {
            list($lat, $lng) = $this->attemptToGeoLocate();
        }

        if ($lat && $lng) {
            $request->request->set($this->key, new Point($lat, $lng));
        }
    }

    public function setGeoLocateAddress(\Closure $callback)
    {
        $this->geoLocationCallback = $callback;

        return $this;
    }

    protected function attemptToGeoLocate()
    {
        $callback = $this->geoLocationCallback;

        if ($callback) {
            $address = $callback(request());
            $coords = GoogleHelper::geoLocate($address);
            return [$coords['latitude'], $coords['longitude']];
        }

        return [0, 0];
    }

    protected function _render()
    {
        //$this->setToVueLiteral([
        //    'show_map',
        //    'latitude',
        //    'longitude',
        //    'zoom',
        //    'map_width',
        //    'map_height',
        //]);

        $this->setAttrs([
            'id'    => $this->getId(),
            'name'  => $this->getName(),
            'value' => $this->getValue(),
        ]);

        $this->with(['displayValue' => $this->getDisplayValue()]);

        return parent::_render();
    }

    public function setValue($value)
    {
        if (is_array($value)) {
            $this->setLatitude(current($value));
            $this->setLongitude(end($value));
        } elseif ($value instanceof Point) {
            $this->setLatitude($value->getLat());
            $this->setLongitude($value->getLng());
        } elseif (is_string($value)) {
            $parts = preg_split('#\s*,\s*#', $value);
            $this->setLatitude($parts[0]);
            if (isset($parts[1])) {
                $this->setLongitude($parts[1]);
            }
        }
    }

    public function setLatitudeMap($latitudeName)
    {
        $this->setAttr('latitude-name', $latitudeName);

        return $this;
    }

    public function getLatitudeMap()
    {
        return $this->getAttr('latitude-name');
    }

    public function setLongitudeMap($longitudeName)
    {
        $this->setAttr('longitude-name', $longitudeName);

        return $this;
    }

    public function getLongitudeMap()
    {
        return $this->getAttr('longitude-name');
    }

    public function setShowMap($show)
    {
        $this->setAttr(':show-map', ($show) ? 'true' : 'false');

        return $this;
    }

    public function getShowMap()
    {
        return $this->getAttr(':show-map') == 'true' ? true : false;
    }

    public function setLatitude($latitude)
    {
        $this->setAttr(':latitude', $latitude);

        return $this;
    }

    public function getLatitude()
    {
        return $this->getAttr(':latitude');
    }

    public function setLongitude($longitude)
    {
        $this->setAttr(':longitude', $longitude);

        return $this;
    }

    public function getLongitude()
    {
        return $this->getAttr(':longitude');
    }

    public function getZoom()
    {
        return $this->getAttr(':map-zoom');
    }

    public function setZoom($zoom)
    {
        $this->setAttr(':map-zoom', $zoom);

        return $this;
    }

    public function getMapWidth()
    {
        return $this->getAttr('map-width');
    }

    public function setMapWidth($width)
    {
        if (is_numeric($width)) {
            $width = $width.'px';
        }
        $this->setAttr('map-width', $width);

        return $this;
    }

    public function getMapHeight()
    {
        return $this->getAttr('map-height');
    }

    public function setMapHeight($height)
    {
        if (is_numeric($height)) {
            $height = $height.'px';
        }

        $this->setAttr('map-height', $height);

        return $this;
    }

    public function setMapOptions($options)
    {
        if (is_array($options)) {
            $options = json_encode($options);
        }

        $this->setAttr(':map-options', $options);

        return $this;
    }

    public function getMapOptions()
    {
        return $this->getAttr(':map-options');
    }
}