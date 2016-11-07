@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Edit Booking {{ $booking->id }}</h1>

    {!! Form::model($booking, [
            'method' => 'PATCH',
            'url' => ['booking' , $booking->id],
            'class' => 'form-horizontal',
            'files' => true
        ]) !!}

    <div class="form-group">
        {!! Form::label('full_name', 'Customer Name', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::label('customer_name', $booking->customer->fullName, ['class' => 'col-sm-9']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('cleaner', 'Cleaner', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::label('customer_name', $booking->cleaner->fullName, ['class' => 'col-sm-9']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('date', 'Original Date', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::label('customer_name', $booking->formatedDate, ['class' => 'col-sm-9']) !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('date') ? 'has-error' : ''}}">
        {!! Form::label('date', 'Date', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            <input type="datetime-local" class="form-control" name="book_date">
            {!! $errors->first('date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Re-Book', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
    {!! Form::close() !!}

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

</div>
@endsection