
@extends( 'frontend.app' )


@section( 'content' )
	
<div class="row" >
	<div class="col-md-12 register-page">
		<div class="row">
			@if(Session::has('success'))
			<div class="col-md-12 register-content">
				<div class="alert alert-info alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>Pesan telah terkirim!</strong>
				  {!! Session::get('success') !!}
				</div>
			</div>
			@endif
			<div class="col-xs-12 col-md-8 col-md-offset-2 register-content">
				<div class="panel register-panel">
						<h3 class="text-center">Hubungi Kami</h3>
						<p class="text-center">
							Jika anda memiliki pertanyaan, keluhan atau apapun, baik anda pasien ataupun dokter, silahkan kirimkan pesan dengan mengisi formulir dibawah ini. Kami mau mendengar dari anda.
						</p>
						<hr>
						{!! BootForm::open()->action(route('contact-us.store')) !!}
						<div class="row">
							<div class="col-md-4">
								{!! BootstrapForm::text('name','Nama(*)') !!}				
							</div>
							<div class="col-md-4">
								{!! BootstrapForm::text('email','Email(*)') !!}
							</div>
							<div class="col-md-4">
								{!! BootstrapForm::text('website','Website') !!}
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								{!! BootstrapForm::text('title','Judul(*)') !!}
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								{!! BootstrapForm::textarea('description','Pesan(*)',null, ['rows' => '2']) !!}
							</div>
						</div>
					    <div class="row">
					        <div class="col-md-12">
					            <button type="submit" class="btn btn-register btn-block">KIRIM</button>
					        </div>
					    </div>
						{!! BootForm::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection