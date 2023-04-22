<?php

namespace App\Admin\Extensions\Tools;

use Dcat\Admin\Grid\Tools\AbstractTool;

class UserGender extends AbstractTool
{
    protected function script()
    {
        $url = request()->fullUrlWithQuery(['gender' => '_gender_']);

        return <<<JS
$("input:radio.grid-radio").change(function () {
    var url = "$url&gender=" + $(this).val();

    Dcat.reload(url);
});
}
JS;
    }

    public function render()
    {
        \Admin::script($this->script());

        $options = [
            'all' => 'All',
            'm' => 'Male',
            'f' => 'Female',
        ];

        return view('admin.tools.gender', compact('options'));
    }
}
