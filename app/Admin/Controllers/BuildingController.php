<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Building;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Building(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('community_code');
            $grid->column('code');
            $grid->column('name');
            $grid->column('house');
            $grid->column('lift');
            $grid->column('desc');
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
        return Show::make($id, new Building(), function (Show $show) {
            $show->field('id');
            $show->field('community_code');
            $show->field('code');
            $show->field('name');
            $show->field('house');
            $show->field('lift');
            $show->field('desc');
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
        return Form::make(new Building(), function (Form $form) {
            $form->display('id');
            $form->select('community_code')->display(true)->options(\App\Models\Community::all()->pluck('name', 'code'))->required();
            $form->text('code')->required();
            $form->text('name')->required();
            $form->text('house');
            $form->text('lift');
            $form->text('desc');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }


    public function search(Request $request): \Illuminate\Database\Eloquent\Collection|array
    {
        $community_code = $request->get('q');

        return \App\Models\Building::query()
            ->where('community_code', $community_code)
            ->get(['code', DB::raw('name as text')]);
    }
}
