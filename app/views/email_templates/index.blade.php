@extends('layouts.master')

@section('content')

<div class="headerbar">
	<h1>{{ trans('fi.email_templates') }}</h1>

	<div class="pull-right">
		<a class="btn btn-primary" href="{{ route('emailTemplates.create') }}"><i class="icon-plus icon-white"></i> {{ trans('fi.new') }}</a>
	</div>
	
	<div class="pull-right">
		{{ Pager::create($emailTemplates) }}
	</div>

</div>

<div class="table-content">

	@include('layouts._alerts')

	<table class="table table-striped">

		<thead>
			<tr>
				<th>{{ trans('fi.name') }}</th>
				<th>{{ trans('fi.subject') }}</th>
				<th>{{ trans('fi.options') }}</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($emailTemplates as $emailTemplate)
			<tr>
				<td>{{ $emailTemplate->name }}</td>
				<td>{{ $emailTemplate->subject }}</td>
				<td>
					<div class="options btn-group">
						<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"></i> {{ trans('fi.options') }}</a>
						<ul class="dropdown-menu">
							<li>
								<a href="{{ route('emailTemplates.edit', array($emailTemplate->id)) }}">
									<i class="icon-pencil"></i> {{ trans('fi.edit') }}
								</a>
							</li>
							<li>
								<a href="{{ route('emailTemplates.delete', array($emailTemplate->id)) }}" onclick="return confirm('{{ trans('fi.delete_record_warning') }}');">
									<i class="icon-trash"></i> {{ trans('fi.delete') }}
								</a>
							</li>
						</ul>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>

	</table>

</div>

@stop