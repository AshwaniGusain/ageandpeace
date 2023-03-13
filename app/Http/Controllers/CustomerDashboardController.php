<?php

namespace App\Http\Controllers;

use App\Models\ProviderType;
use DB;
use App\Models\Category;
use App\Models\CustomerTask;
use App\Models\ProviderTypeGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Snap\Media\Models\Media;

class CustomerDashboardController extends Controller
{
    protected $withFields = [
        'task',
        //'task:id',
        //'task:category',
        //'task:title',
        //'task:description',
        //'task.file',
    ];

    protected $defaultOrder = 'ISNULL(due_date), due_date ASC';

    public function index()
    {
        $customer = Auth::user()->customer;
        $progress = [];
        $hasProgress = false;
        foreach(\App\Models\Category::topLevel()->get() as $category) {
            $tasks = $customer->tasks()->ofCategory($category->slug)->get();
            $totalTasks = $tasks->count();
            $completedTasks = $tasks->where('completed', 1)->count();
            if ($completedTasks) {
                $hasProgress = true;
            }
            $progress[$category->slug] = [
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'name' => $category->name,
                'slug' => $category->slug,
                'progress' => round(($completedTasks / $totalTasks), 2)
            ];
        }

        $upcoming = \App\Models\CustomerTask::upcoming()->orderByRaw($this->defaultOrder)
            ->with($this->withFields)
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('upcoming', 'progress', 'hasProgress'));
    }

    public function category(Category $category)
    {
        $tasks = Auth::user()->customer->tasks()->ofCategory($category->slug)->orderByRaw($this->defaultOrder)
            ->with($this->withFields)
            ->get();

        return view('dashboard.category', compact('tasks', 'category'));
    }

    public function upcoming()
    {
        $title = 'Upcoming Tasks';
        $upcoming = true;
        $providerTypes = $this->getProviderTypeGroups();

        $tasks = Auth::user()->customer->tasks()->upcoming()->orderByRaw($this->defaultOrder)
            ->with($this->withFields)
            ->get();

        return view('dashboard.all', compact('tasks', 'title', 'upcoming', 'providerTypes'));
    }

    public function all(Request $request)
    {
        $title = null;

        $providerTypes = $this->getProviderTypeGroups();

        $tasksQuery = Auth::user()->customer->tasks();

        $providerTypeSlug = $request->input('type');
        if ($providerTypeSlug) {
            $providerType = ProviderType::where('slug', $providerTypeSlug)->first();
            if ($providerType) {
                $title = '"'.$providerType->name.'" Provider Tasks';
                $tasksQuery->ofProviderType($providerType);
            }
        }
        $tasks = $tasksQuery->orderByRaw($this->defaultOrder)
            ->with([
                'task' => function($q){
                    $q->orderBy('category_id', 'ASC');
                }
            ])
            ->get();



        // This allows us to order outside of the relationship.
        //$tasks = DB::table('customer_tasks')
        //->where('customer_id', Auth::user()
        //->customer->id)
        //->leftJoin('tasks', 'tasks.id', '=', 'customer_tasks.task_id')
        //->orderByRaw('ISNULL(due_date), due_date ASC')

        return view('dashboard.all', compact('tasks', 'providerTypes', 'title'));
    }

    protected function getProviderTypeGroups()
    {
        $providerTypeGroups = ProviderTypeGroup::all();
        $providerTypes = collect([]);
        foreach ($providerTypeGroups as $group) {
            $type = ['label' => $group->name];
            foreach ($group->providerTypes as $providerType) {
                $type['categories'][] = $providerType->categories->pluck('id')->flatten();
            }

            $providerTypes->push($type);
        }

        return $providerTypes;
    }

    public function printList(Category $category = null, Request $request)
    {
        $tasks = Auth::user()->customer->tasks();
        $title = 'Tasks';

        if (!is_null($category)) {
            $tasks = $tasks->ofCategory($category->slug);
            $title = $category->name . ' Tasks';
        }

        if ($request->get('upcoming')) {
            $tasks = $tasks->upcoming();
            $title = 'Upcoming ' . $title;
        }

        if (!is_null($request->get('completed'))) {
            $tasks = $tasks->where('completed', $request->get('completed'));
        }

        $tasks = $tasks->orderByRaw($this->defaultOrder)
                   ->with([
                       'task' => function($q){
                           $q->orderBy('category_id', 'ASC');
                       }
                   ])
                   ->get();

        return view('dashboard.print-task-list', compact('tasks', 'title'));
    }

    public function updateTask(Request $request, CustomerTask $task)
    {
        $completed = $request->get('completed') == true ? 1 : 0;
        $task->completed = $completed;
        if ($completed === 1) {
            $task->completed_date = date("Y-m-d");
            $task->due_date = null;
        } else {
            $task->completed_date = null;
        }
        $task->save();

        return response()->json($task->completed);
    }

    public function updateDueDate(Request $request, CustomerTask $task)
    {
        $date = ($request->get('due_date')) ? Carbon::parse($request->get('due_date')) : null;
        $task->due_date = $date;
        $task->save();

        return ($date) ? response()->json($task->due_date) : null;
    }

    public function relatedTaskDetails($category)
    {
        $category = Category::find($category);

        return response()->json([
            'providers' => ($category->providers) ? $category->providers->take(5) : [],
            'articles' => \App\Models\Post::where('category_id', $category->id)->limit(3)->get(),
            //'providerTypes' => \App\Models\ProviderType::whereHas('categories', function ($query) use ($category) {
            //    $query->where('category_id', $category->id);
            //})->get()
            'providerTypes' => $category->providerTypes()->get()
        ]);
    }

    public function taskFile(Media $file)
    {
        return response()->download($file->getPath(), $file->name);
    }
}
