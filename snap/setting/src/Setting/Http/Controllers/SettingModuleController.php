<?php

namespace Snap\Setting\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ModuleController;
use Snap\Setting\Models\Setting;

class SettingModuleController extends ModuleController
{

    public function index()
    {
        return $this->module->ui('settings');
    }

    public function update(Request $request)
    {
        $form = $this->module->ui('settings')->form;

        if (!$form->validate()) {
            return redirect()->back()->with('errors', $form->getErrors());
        }

        // We'll use transactions here to make sure everything is glorious among the data.
        DB::beginTransaction();

        foreach ($request->all() as $key => $val) {
            $input = $form->get($key);
            if ($input) {
                $input->setValue($val)->runPostProcessor(['beforeValidation', 'beforeSave', 'afterSave'], $request, null);
            }

            // We need to convert the "-" back to "." so that the saved and retrieved matching the corresponding config values.
            $setting = Setting::firstOrNew(['key' => str_replace('-', '.', $key)]);

            // Must use $request->request because that's where the data was manipulated on the requests.
            //dump($request->input('settings.'.$key));
            // The first one is used if a postProcessor is run.
            // The second one is used if there isn't any value from the postProcessor
            $setting->value = $request->get($key) ?? $request->input($key);


            //$validationRules = $
            if (!$setting->save()) {
                DB::rollback();
                //return redirect()->back()->with('errors', trans('admin::resources.save_error'));
                return redirect()->back()->with('errors', $setting->getErrors());
            }
        }

        // If everything is glorious, we'll commit the transaction.
        DB::commit();
        return redirect()->back()->with('success', trans('admin::resources.save_success'));
    }

}