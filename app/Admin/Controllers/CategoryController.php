<?php

namespace App\Admin\Controllers;

use App\Category;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CategoryController extends Controller
{
    use ModelForm;

    const ADMIN_TITLE = 'Category';

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(self::ADMIN_TITLE);
            $content->description('list');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(self::ADMIN_TITLE);
            $content->description('edit');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(self::ADMIN_TITLE);
            $content->description('create');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Category::class, function (Grid $grid) {
            $grid->disablePagination();
            $grid->disableFilter();
            $grid->disableExport();
            $grid->disableRowSelector();

            $grid->image_path('Image')->image('', 100);
            $grid->id('ID')->sortable();
            $grid->parent_id('隸屬大分類')->sortable()->display(function ($pid) {
                return $pid ? Category::find($pid)->title : '無';
            });
            $grid->column('title', '分類名稱'); // laravel-admin Grid defined 'title' method, call 'column' method to replace 'title'
            $grid->created_at('建立時間')->display(function ($ts) {
                return date("Y-m-d H:i:s", $ts);
            });
            $grid->updated_at('最後更新時間')->display(function ($ts) {
                return date("Y-m-d H:i:s", $ts);
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        // get associative array from Illuminate Collection
        $parent_ids_opt = Category::query()
            ->select(['id', 'title'])
            ->whereNull('parent_id')
            ->get()
            ->pluck('title', 'id');
        return Admin::form(Category::class, function (Form $form) use ($parent_ids_opt) {

            $form->display('id', 'ID');
            $form->select('parent_id', 'parent ID')->options($parent_ids_opt);
            $form->text('title');
            $form->text('description');
            $form->image('image_path');

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
