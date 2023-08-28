@extends('layout.app')

@section('title', ' | Dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">

        @if(\Auth::user()->is_admin == 1)
            <!-- Small boxes (Stat box) -->
            <div class="row">

                @if(count(getContacts()) > 0)
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{ count(getContacts()) }}</h3>

                                <p>Contacts</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ url('admin/contacts') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                @endif

                @if(count(getContacts('Lead')) > 0)
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>{{count(getContacts('Lead'))}}</h3>

                                <p>Leads</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ url('admin/contacts?status_name=Lead') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                @endif

                @if(count(getContacts('Opportunity')) > 0)
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{count(getContacts('Opportunity'))}}</h3>

                                <p>Opportunities</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ url('admin/contacts?status_name=Opportunity') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                @endif

                @if(count(getContacts('Customer')) > 0)
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>{{count(getContacts('Customer'))}}</h3>

                                <p>Potential Customers</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ url('admin/contacts?status_name=Customer') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                @endif
            </div>

            <div class="row">
                <section class="col-lg-12 connectedSortable ui-sortable">
                    <!-- TO DO List -->
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="ion ion-clipboard"></i>

                            <h3 class="box-title">My Team</h3>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Position</th>
                                        <th>Contacts</th>
                                        <th>Tasks</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(getUsers() as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->position_title }}</td>
                                            <td><a href="{{ url('admin/contacts?assigned_user_id=' . $user->id) }}">{{ $user->contacts->count() }}</a></td>
                                            <td><a href="{{ url('admin/tasks?assigned_user_id=' . $user->id) }}">{{ $user->tasks->count() }}</a></td>
                                            <td><a href="{{ url('admin/users/' . $user->id) }}" data-toggle="tooltip" title="view details"><i class="fa fa-camera"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- /.box -->
                </section>
            </div>

        @else

            <div class="row">
                @if(user_can("list_contacts"))

                    @if(\Auth::user()->contacts->count() > 0)
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>{{ \Auth::user()->contacts->count() }}</h3>

                                    <p>My Contacts</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{ url('admin/contacts') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    @endif

                    @if(\Auth::user()->leads->count() > 0)
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>{{ \Auth::user()->leads->count() }}</h3>

                                    <p>My Leads</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{ url('admin/contacts?status_name=Lead') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    @endif

                    @if(\Auth::user()->opportunities->count() > 0)
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>{{ \Auth::user()->opportunities->count() }}</h3>

                                    <p>My Opportunities</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="{{ url('admin/contacts?status_name=Opportunity') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    @endif

                    @if(\Auth::user()->customers->count() > 0)
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>{{ \Auth::user()->customers->count() }}</h3>

                                    <p>My Potential Customers</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{ url('admin/contacts?status_name=Customer') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    @endif

                @endif
            </div>

            @if(user_can("list_tasks"))
                <div class="row">
                    <section class="col-lg-12 connectedSortable ui-sortable">
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="ion ion-clipboard"></i>

                                <h3 class="box-title">To do list</h3>

                            </div>

                            <div class="box-body">
                                <ul class="todo-list">
                                    @foreach(\Auth::user()->tasks as $task)
                                        <li>
                                            <span class="handle ui-sortable-handle">
                                                <i class="fa fa-ellipsis-v"></i>
                                                <i class="fa fa-ellipsis-v"></i>
                                            </span>
                                            <span class="text">{{ $task->name }}</span>


                                                @if($task->getStatus->name == 'Not Started')
                                                    <small class="label label-warning">not started</small>
                                                @elseif($task->getStatus->name == 'Started')
                                                    <small class="label label-info">started</small>
                                                @elseif($task->getStatus->name == 'Completed')
                                                    <small class="label label-success">completed</small>
                                                @else
                                                    <small class="label label-danger">cancelled</small>
                                                @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </section>
                </div>
            @endif

        @endif

    </section>



@stop