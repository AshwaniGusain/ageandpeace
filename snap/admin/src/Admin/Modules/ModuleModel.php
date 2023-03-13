<?php 

namespace Snap\Admin\Modules;

use Snap\Database\Model\Model;
use Snap\Database\Model\Traits\HasDisplayName;

class ModuleModel extends Model 
{
    use HasDisplayName;

    protected $sanitization = [
        '*' => 'clean_html',
    ];

    public function getRouteKeyName()
    {
        // id is needed for the admin and slug for the frontend so we check the
        // request to see if the module parameter was injected on the request which
        // only occurs in the admin.
        if (method_exists($this, 'getSlugOptions')) {
            $actions = request()->route()->getAction();
            if (isset($actions['module'])) {
                return $this->getKeyName();
            }

            return 'slug';
        } else {
            return $this->getKeyName();
        }
    }

}