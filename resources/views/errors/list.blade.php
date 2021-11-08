@if (session('error'))
    <div class="col-sm-12">
        <div class="alert  alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
    </div>
@endif

@if($errors->any())

	<div class="col-sm-12">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
	
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
             </button>
		
        <strong>Whoops!</strong> There were some problems with your input.
		<br/>
		<br/>
		<ul class="error-list">
            @foreach ($errors->all() as $error)
            @php
             
            if(str_contains($error,'jpeg')){
            $error='The image must be a file of type: jpeg, png, jpg, gif, svg';
            }
            if(str_contains($error,'2048')){
            $error='The image must be a file of size lesser than 2MB';
            }

            if(str_contains($error,'failed')){
            $error='Failed to upload';
            }
            if(str_contains($error,'searched product'))
            $error='Invalid Product';

            @endphp
           
                <li class="error-list-each">{{ $error }}</li>
            @endforeach
        </ul>
		
    </div>
	

	
@endif
