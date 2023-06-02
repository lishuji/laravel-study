<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Community;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class CommunityController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Community(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('code');
            $grid->column('name');
            $grid->column('introduction');
            $grid->column('thumb');
            $grid->column('address');
            $grid->column('area');
            $grid->column('developer');
            $grid->column('estate');
            $grid->column('greening_rate');
            $grid->column('total_building');
            $grid->column('total_owner');
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
        return Show::make($id, new Community(), function (Show $show) {
            $show->field('id');
            $show->field('code');
            $show->field('name');
            $show->field('introduction');
            $show->field('thumb');
            $show->field('address');
            $show->field('area');
            $show->field('developer');
            $show->field('estate');
            $show->field('greening_rate');
            $show->field('total_building');
            $show->field('total_owner');
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
        return Form::make(new Community(), function (Form $form) {
            $form->display('id');
            $form->text('code', '小区编号')->help('小区编号，建议CM开头')->required();
            $form->text('name')->rules('required');
            $form->text('introduction');
            $form->file('thumb');
            $form->text('address')->rules('required');
            $form->map('latitude', 'longitude', '地图')->help('请在地图上选择小区位置')->required();
            $form->text('area');
            $form->text('developer');
            $form->text('estate');
            $form->text('greening_rate');
            $form->text('total_building');
            $form->text('total_owner');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
