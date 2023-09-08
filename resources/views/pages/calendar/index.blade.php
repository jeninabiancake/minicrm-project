@extends('layout.app')


@section('title', ' | Calendar')

@section('styles')
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ url('theme/bower_components/fullcalendar/dist/fullcalendar.min.css') }}">
    <style>
        .external-event {
            cursor: pointer !important;
        }
        
    </style>
@endsection

@section('content')

    <section class="content-header">
        <h1>
            Calendar
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Calendar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h4 class="box-title">Tasks</h4>
                    </div>
                    <div class="box-body">
                        <!-- the events -->
                        <div id="external-events">
                            @if($countPending)
                                <div class="external-event bg-light-blue">Pending tasks ({{$countPending}})</div>
                            @endif

                            @if($countInProgress)
                                <div class="external-event bg-yellow">In progress tasks ({{$countInProgress}})</div>
                            @endif

                            @if($countFinished)
                                <div class="external-event bg-green">Finished tasks ({{$countFinished}})</div>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.col -->

            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-body no-padding">
                        <!-- THE CALENDAR -->
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('pages.calendar.includes.event_modal')
@endsection

@section('scripts')

    <!-- fullCalendar -->
    <script src="{{ url('theme/bower_components/moment/moment.js') }}"></script>
    <script src="{{ url('theme/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>

    <?php echo $events_js_script ?>

    <script type="text/javascript" src="{{ url('theme/views/calendar/fullcalendar.js') }}"></script>

@endsection