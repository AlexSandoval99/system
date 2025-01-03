@if($errors->any())
    <div id="flash_message" class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{!! ucfirst($error) !!}</div>
        @endforeach
    </div>
@endif
@if(session('success'))
	<div id="flash_message" class="alert alert-success">
		{!! session('success') !!}
	</div>
@endif
