@extends('layout')

@section('page-title', 'Calendar')

@section('calendar')

<div class="container">
		<br />
		<h1 class="text-center text-primary"><u>How to Use Fullcalendar in Laravel 8</u></h1>
		<br />

		<div id="calendar"></div>

</div>

@endsection

@section('scripts')
<script>

		$(document).ready(function () {
			
				$.ajaxSetup({
						headers:{
								'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
						}
				});
				console.log("calendar reach in calendar!");
				var calendar = $('#calendar').fullCalendar({
						editable:true,
						header:{
								left:'prev,next today',
								center:'title',
								right:'month,agendaWeek,agendaDay'
						},
						events:'/agency/calendar',
						selectable:true,
						selectHelper: true,
						select:function(start, end, allDay)
						{
								var title = prompt('Event Title:');

								if(title)
								{
										var start = $.fullCalendar.formatDate(start, 'Y-MM-DD HH:mm:ss');

										var end = $.fullCalendar.formatDate(end, 'Y-MM-DD HH:mm:ss');

										$.ajax({
												url:"/agency/action",
												type:"POST",
												data:{
														title: title,
														start: start,
														end: end,
														type: 'add'
												},
												success:function(data)
												{
														calendar.fullCalendar('refetchEvents');
														alert("Event Created Successfully");
												}
										})
								}
						},
						editable:true,
						eventResize: function(event, delta)
						{
								var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
								var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
								var title = event.title;
								var id = event.id;
								$.ajax({
										url:"/agency/action",
										type:"POST",
										data:{
												title: title,
												start: start,
												end: end,
												id: id,
												type: 'update'
										},
										success:function(response)
										{
												calendar.fullCalendar('refetchEvents');
												alert("Event Updated Successfully");
										}
								})
						},
						eventDrop: function(event, delta)
						{
								var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
								var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
								var title = event.title;
								var id = event.id;
								$.ajax({
										url:"/agency/action",
										type:"POST",
										data:{
												title: title,
												start: start,
												end: end,
												id: id,
												type: 'update'
										},
										success:function(response)
										{
												calendar.fullCalendar('refetchEvents');
												alert("Event Updated Successfully");
										}
								})
						},

						eventClick:function(event)
						{
								if(confirm("Are you sure you want to remove it?"))
								{
										var id = event.id;
										$.ajax({
												url:"/agency/action",
												type:"POST",
												data:{
														id:id,
														type:"delete"
												},
												success:function(response)
												{
														calendar.fullCalendar('refetchEvents');
														alert("Event Deleted Successfully");
												}
										})
								}
						}
				});

		});
	
</script>
@endsection