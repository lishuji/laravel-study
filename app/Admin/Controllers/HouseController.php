<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\House;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class HouseController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new House(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('community_code');
            $grid->column('building_code');
            $grid->column('code');
            $grid->column('name');
            $grid->column('owner_name');
            $grid->column('owner_tel');
            $grid->column('rooms');
            $grid->column('unit');
            $grid->column('floor');
            $grid->column('desc');
            $grid->column('enter_time');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new House(), function (Show $show) {
            $show->field('id');
            $show->field('community_code');
            $show->field('building_code');
            $show->field('code');
            $show->field('name');
            $show->field('owner_name');
            $show->field('owner_tel');
            $show->field('rooms');
            $show->field('unit');
            $show->field('floor');
            $show->field('desc');
            $show->field('enter_time');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new House(), function (Form $form) {
            $form->display('id');
            $form->select('community_code')->options(\App\Models\Community::all()->pluck('name', 'code'))->load('building_code', '/search');
            $form->select('building_code')->required();
            $form->text('code')->rules('required')->help('房屋编号必须唯一,建议HS开头');
            $form->text('name')->rules('required');
            $form->text('owner_name')->required();
//            $form->text('owner_tel')->rules('required|regex:/^1[3456789][0-9]{9}$/', [
//                'required' => '手机号不能为空',
//                'regex' => '手机号格式不正确',
//            ]);

            $form->mobile('owner_tel')->required()->options(['mask' => '999 9999 9999']);
            $form->number('rooms')->required();
            $form->number('unit')->required();
            $form->number('floor')->required();
            $form->markdown('desc')->languageUrl(admin_asset('@admin/dcat/plugins/editor-md/languages/zh-tw.js'));
            $form->datetime('enter_time');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
