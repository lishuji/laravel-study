<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Request;

class CategoryController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Category(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->name->tree();
            $grid->column('status', '是否启用')->display(function ($status) {
                return $status ? '启用' : '禁用';
            })->label([
                0 => 'danger',
                1 => 'success',
            ]);
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
        return Show::make($id, new Category(), function (Show $show) {
            $show->field('id');
            $show->field('parent_id');
            $show->field('name');
            $show->field('description');
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
        return Form::make(new Category(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->select('parent_id', '父级分类')
                ->options(Category::query()->where('parent_id', 0)->pluck('name', 'id'));
            $form->textarea('description', '请输入描述');
            $form->switch('status')->options(['0' => '禁用', '1' => '启用'])->default('0');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }

    public function search(Request $request): array
    {
        $keyword = $request->get('q');

        return Category::query()
            ->where('name', 'like', "%{$keyword}%")
            ->get(['id', 'name'])->toArray();
    }
}
