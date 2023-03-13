<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\AttrsTrait;
use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\Traits\VueTrait;

class Wysiwyg extends BaseInput {

    use VueTrait;
    use AttrsTrait;
    use CssClassesTrait;

    protected $vue = 'snap-wysiwyg-input';
    protected $view = 'form::inputs.textarea';
    protected $scripts = [
        'assets/snap/vendor/redactor/redactor.js',
        //'assets/snap/vendor/redactor/_plugins/filemanager/filemanager.js',
        'assets/snap/vendor/redactor/_plugins/counter/counter.js',
        'assets/snap/vendor/redactor/_plugins/definedlinks/definedlinks.js',
        'assets/snap/vendor/redactor/_plugins/limiter/limiter.js',
        'assets/snap/vendor/redactor/_plugins/imagemanager/imagemanager.js',
        'assets/snap/vendor/redactor/_plugins/textexpander/textexpander.js',
        'assets/snap/vendor/redactor/_plugins/fullscreen/fullscreen.js',
        'assets/snap/vendor/redactor/_plugins/video/video.js',
        'assets/snap/vendor/redactor/_plugins/limiter/limiter.js',
        'assets/snap/vendor/redactor/_plugins/variable/variable.js',

        'assets/snap/js/components/form/WysiwygInput.js',
    ];

    protected $styles = [
        'assets/snap/vendor/redactor/redactor.css',
        'assets/snap/vendor/redactor/plugins/filemanager/filemanager.css',
        'assets/snap/js/components/form/redactor/variable.css',
    ];

    protected $options = [
        'minHeight' => '200px',
        'maxHeight' => '800px',
        'plugins' => [
            'imagemanager',
            'definedlinks',
            'limiter',
            'textexpander',
            'fullscreen',
            'video',
            'counter',
            //'variable',
        ],
        'imageResizable' => true,
        'imagePosition' => true,
        'linkTarget' => true,
        //'fileUpload' => '/admin/media/upload',
        //'fileManagerJson' => '/admin/media/{id}/files.json',
        'imageUpload' => '/media/{id}/upload',
        'imageManagerJson' => '/media/images.json',
        'definedlinks' => '/page/urls.json',
        //'definedlinks' => [
        //       ['name' => 'Select...', 'url' => false],
        //]
        'buttons' => ['html', 'format', 'bold', 'italic', 'ul', 'ol', 'link', 'image'],

        // default set of buttons
        //'html', 'format', 'bold', 'italic', 'deleted', 'lists', 'image', 'file', 'link'

        // additional set
        //'line', 'redo', 'undo', 'underline', 'ol', 'ul', 'indent', 'outdent', 'sup', 'sub'
    ];

    public function initialize()
    {
        //$this->options['fileUpload'] = url($this->options['fileUpload']);
        //$this->options['fileManagerJson'] = url($this->options['fileManagerJson']);
        $this->options['imageUpload'] = url(config('snap.admin.route.prefix').$this->options['imageUpload']);
        $this->options['imageManagerJson'] = url(config('snap.admin.route.prefix').$this->options['imageManagerJson']);
        $this->options['definedlinks'] = url(config('snap.admin.route.prefix').$this->options['definedlinks']);
        //$this->setPostProcess(function($value, $input, $request, $resource){
        //    $this->postProcessMedia($value);
        //}, 'afterSave');
    }

    public function setOptions($options)
    {
        //if (is_array($options)) {
        //    $options = json_encode($options);
        //}

        if (is_array($this->options)) {
            $options = array_merge($this->options, $options);
        }

        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setMediaCollection($collection)
    {
        $this->setAttr('collection', $collection);
    }

    public function getMediaCollection()
    {
        return $this->getAttr('collection');
    }

    public function setValue($value)
    {
        return parent::setValue($value);
    }

    protected function _render()
    {
        $this->data['value'] = $this->getValue();

        $this->setAttrs(
            [
                ':options'  => $this->getOptions(),
                'name'  => $this->getName(),
                'id'    => $this->getId(),
                'class' => ! is_null($this->getAttr('class')) ? $this->getAttr('class').' form-control' : 'form-control',
                //'value' => $this->getValue(), // For Vue.js component
            ]
        );

        return parent::_render();
    }

    /* public function getConfigAsJson()
     {
         foreach (array_filter($this->config) as $key => $val) {
             $props[camel_case($key)] = $val;
         }

         $props['snaplinks'] = $this->getSnapLinks();

         $props['snapfiles'] = $this->getSnapFiles();

         return json_encode($props);
     }

     public function getSnapLinks()
     {
         $contentModule = Module::get('content');
         $routeKey = $contentModule->routeKey('links');
         return route($routeKey, $this->config['link_content_types']);
     }

     public function getSnapFiles()
     {
         return route('snap.filemanager', $this->config['filemanager']);
     }

     public function preProcess($value, Request $request = null)
     {
         $value = $this->preProcessUrl($value);
         $value = $this->preProcessFile($value);

         return $value;
     }

     protected function preProcessUrl($value)
     {
         $contentTemplateObject = resolve(ContentTemplateObject::class);

         // {{ snap.content.id(4)->url }}
         $value = preg_replace_callback('%\{\{\s*snap.content.id\((\d+)\).url\s*\}\}%iU',
             function($matches) use ($contentTemplateObject) {
                 if ( isset($matches[1])) {
                     $content = $contentTemplateObject->id($matches[1]);
                     return $content->ref_url;
                 }
             }
             , $value);

         return $value;
     }

     protected function preProcessFile($value)
     {
         $filesTemplateObject = resolve(FilesTemplateObject::class);

         // {{ snap.files.id(4)->url }}
         $value = preg_replace_callback('%\{\{\s*snap.files.id\((\d+)\).url\s*\}\}%iU',
             function($matches) use ($filesTemplateObject) {
                 if ( isset($matches[1])) {
                     $content = $filesTemplateObject->id($matches[1]);
                     return $content->ref_url;
                 }
             }
             , $value);

         return $value;
     }

     public function postProcess($value, Request $request = null)
     {
         $value = $this->postProcessUrl($value);
         $value = $this->postProcessFile($value);

         return $value;
     }

     protected function postProcessUrl($value)
     {
         $value = preg_replace_callback('%(http(s)?:)?//.+#content:(\d+):url%iU',
             function($matches){
                 if ( isset($matches[3])) {
                     return '{{ snap.content.id(' . $matches[3] . ').url }}';
                 }
             }
             , $value);

         return $value;
     }

     protected function postProcessFile($value)
     {
         $value = preg_replace_callback('%(http(s)?:)?//.+#files:(\d+):url%iU',
             function($matches){
                 if ( isset($matches[3])) {
                     return '{{ snap.files.id(' . $matches[3] . ').url }}';
                 }
             }
             , $value);

         return $value;
     }*/
}