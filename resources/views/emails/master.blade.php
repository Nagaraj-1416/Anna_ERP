@extends('emails.layout.default')
@section('content')
    {!! generateEmailContent($emailTemplate, $data) !!}
@stop