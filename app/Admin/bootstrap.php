<?php

use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Filter;
use Dcat\Admin\Layout\Navbar;
use Dcat\Admin\Show;

/**
 * Dcat-admin - admin builder based on Laravel.
 * @author jqh <https://github.com/jqhph>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 *
 * extend custom field:
 * Dcat\Admin\Form::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Column::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Filter::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Dcat\Admin\Color::extend('orange', [
    'primary' => '#fbbd08',
    'primary-darker' => '#fbbd08',
    'link' => '#fbbd08',
]);

//Dcat\Admin\Form::extend('php', PHPEditor::class);
Dcat\Admin\Grid\Column::extend('php', \App\Models\User::class);
//Dcat\Admin\Grid\Filter::extend('php', PHPEditor::class);

Admin::asset()->alias('@my-name1', 'assets/admin1');
Admin::asset()->alias('@my-name2', 'assets/admin2');


//Admin::navbar(function (Navbar $navbar) {
//
////    $navbar->left('html...');
//
////    $navbar->right('html...');
//
//});

Grid::resolving(function (Grid $grid) {
    $grid->tableCollapse(false);
});
