@extends('layouts.ast')

@section('content')
	<div class="Info_Left">
		<div class="Inner_info">
			<figure class="logo_company">
				<img src="<?php echo asset('assets/images/logo-c.png'); ?>" width="130">
			</figure>
			<div class="Detail_Info">
				<p>ยินดีต้อนรับสู่ระบบ</p>
				<h1>ระบบบริหารจัดการโรงงานเอเซียเท็กซ์ไทล์จำกัด</h1>
				<p class="txt-eng">AST Management</p>
			</div>
			<div class="Detail_bottom">
				บริษัท เอเซียเท็กซ์ไทล์ จำกัด<br> ASIA TEXTILE CO., LTD.
			</div>
		</div><!--Inner_info-->
	</div><!--Info_Left-->
	<div class="Form_login">
		<div class="login-page inner_login">
			<div class="login-box">
				<div class="title-login">
					<h2>Sign in <img src="<?php echo asset('assets/images/user-solid.png'); ?>" width="30"></h2>
					<p style="font-weight: normal">Don't have an account? <a href="{{ route('register') }}">Sign up</a>
					</p>
				</div>


				<form method="POST" action="{{ route('login') }} ">
                        @csrf
					<div class="form-group">
						<label for="email">{{ __('User Name or Email') }}<span class="txt-red">*</span></label>
						<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="User Name or Email " name="email" value="{{ old('email') }}" required autocomplete="email" autofocus >

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
					<div class="form-group">
						<label for="password">{{ __('Password') }} <span class="txt-red">*</span></label>
						<input id="password" type="password" class="form-control" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>

                                @if (Route::has('password.request'))
					<p>
                                    <a href="{{ route('password.request') }}">
                                        {{ __('Forgot password ?') }}
                                    </a> </p>
                                @endif

					<div class="clr">
						<button type="submit" class="btn-login btn-block">Sign in <img src="<?php echo asset('assets/images/right-to-bracket-solid.png'); ?>" width="15"></button>


					</div>
				</form>

			</div><!--login-box-->
		</div><!--login-page-->
	</div><!--Form_login-->

@endsection
