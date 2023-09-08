<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin:index-show_calendar');
    }

    public function index()
    {
        $eventData = $this->getTaskStatusByDate();

        list($events, $countPending, $countInProgress, $countFinished) = $eventData;

        $events_js_script = '<script>var events = '.json_encode($events).'</script>';

        return view('pages.calendar.index', compact('events_js_script', 'countPending', 'countInProgress', 'countFinished'));
    }


    /**
     * get all tasks by date
     *
     * retrieves all tasks by dates and categorizes them into pending, in progress, finished
     * according to the date
     *
     * @return array
     */
    protected function getTaskStatusByDate()
    {
        $pending_tasks = Task::whereNotNull('start_date')->whereNotNull('end_date')->where('start_date', '>', date("m/d/Y"));

        $tasks_in_progress = Task::whereNotNull('start_date')->whereNotNull('end_date')->where('start_date', '<=', date("m/d/Y"))->where('end_date', '>=', date("m/d/Y"));

        $finished_tasks = Task::whereNotNull('start_date')->whereNotNull('end_date')->where('end_date', '<', date("m/d/Y"));


        // if not admin user show tasks if assigned to or created by that user
        if(Auth::user()->is_admin == 0) {
            $pending_tasks->where(function ($query) {
                $query->where('assigned_user_id', Auth::user()->id)
                    ->orWhere('created_by_id', Auth::user()->id);
            });

            $tasks_in_progress->where(function ($query) {
                $query->where('assigned_user_id', Auth::user()->id)
                    ->orWhere('created_by_id', Auth::user()->id);
            });

            $finished_tasks->where(function ($query) {
                $query->where('assigned_user_id', Auth::user()->id)
                    ->orWhere('created_by_id', Auth::user()->id);
            });
        }

        $pending_tasks = $pending_tasks->get();
        $tasks_in_progress = $tasks_in_progress->get();
        $finished_tasks = $finished_tasks->get();

        $pending_events = [];

        $in_progress_events = [];

        $finished_events = [];

        foreach ($pending_tasks as $task) {

            $pending_events[] = ["title" => $task->name . " - pending",
                "start" => date("Y-m-d", strtotime($task->start_date)),
                "end" => date("Y-m-d", strtotime($task->end_date)),
                "backgroundColor" => "#3c8dbc",
                "borderColor" => "#3c8dbc",
                "className" => "pending",
                "description" => "<strong>Title:</strong> " . $task->name . "<br/>" .
                    "<strong>Start date:</strong> " . date("Y-m-d", strtotime($task->start_date)) . "<br/>" .
                    "<strong>End date:</strong> " . date("Y-m-d", strtotime($task->end_date)) . "<br/>" .
                    "<strong>Type:</strong> " . $task->type->name . "<br/>"
                ];
        }

        foreach ($tasks_in_progress as $task) {

            $in_progress_events[] = ["title" => $task->name . " - in progress",
                "start" => date("Y-m-d", strtotime($task->start_date)),
                "end" => date("Y-m-d", strtotime($task->end_date)),
                "backgroundColor" => "#f39c12",
                "borderColor" => "#f39c12",
                "className" => "in-progress",
                "description" => "<strong>Title:</strong> " . $task->name . "<br/>" .
                    "<strong>Start date:</strong> " . date("Y-m-d", strtotime($task->start_date)) . "<br/>" .
                    "<strong>End date:</strong> " . date("Y-m-d", strtotime($task->end_date)) . "<br/>" .
                    "<strong>Type:</strong> " . $task->type->name . "<br/>"
            ];
        }

        foreach ($finished_tasks as $task) {

            $finished_events[] = ["title" => $task->name . " - finished",
                "start" => date("Y-m-d", strtotime($task->start_date)),
                "end" => date("Y-m-d", strtotime($task->end_date)),
                "backgroundColor" => "#00a65a",
                "borderColor" => "#00a65a",
                "className" => "finished",
                "description" => "<strong>Title:</strong> " . $task->name . "<br/>" .
                    "<strong>Start date:</strong> " . date("Y-m-d", strtotime($task->start_date)) . "<br/>" .
                    "<strong>End date:</strong> " . date("Y-m-d", strtotime($task->end_date)) . "<br/>" .
                    "<strong>Type:</strong> " . $task->type->name . "<br/>"
            ];
        }

        return [array_merge($pending_events, $in_progress_events, $finished_events),
            count($pending_events),
            count($in_progress_events),
            count($finished_events)
            ];
    }
}
