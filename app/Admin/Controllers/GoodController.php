<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Good;
use App\Models\Category;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class GoodController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Good(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('category_id');
            $grid->column('code');
            $grid->column('name');
            $grid->column('description');
            $grid->column('image');
            $grid->column('price');
            $grid->column('stock');
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
        return Show::make($id, new Good(), function (Show $show) {
            $show->field('id');
            $show->field('category_id');
            $show->field('code');
            $show->field('name');
            $show->field('description');
            $show->field('image');
            $show->field('price');
            $show->field('stock');
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
        return Form::make(new Good(), function (Form $form) {
            $form->display('id');
//            $form->select('category_id')->options(Category::class, 'id', 'name')->ajax('/admin/api/categories');
            $form->select('category_id')->options(Category::query()->pluck('name', 'id'));
            $form->text('code');
            $form->text('name')->required();
            $form->text('description');
            $form->text('image');
            $form->text('price')->required();
            $form->text('stock');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
