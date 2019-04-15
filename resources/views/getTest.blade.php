@extends('layouts.mirror')

@section('title', 'Monthly Results')

@section('head')	

@endsection

@section('content')

	<form action="{{ route('postTest') }}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
        	<label for="exampleInputFile">File Upload</label>
        	<input type="file" name="file" class="form-control" id="exampleInputFile">
    	</div>
    	<button type="submit" class="btn btn-primary">Submit</button>
	</form>

@endsection