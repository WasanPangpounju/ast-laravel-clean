<?php $__env->startSection('content'); ?>
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
					<p style="font-weight: normal">Don't have an account? <a href="<?php echo e(route('register')); ?>">Sign up</a>
					</p>
				</div>


				<form method="POST" action="<?php echo e(route('login')); ?> ">
                        <?php echo csrf_field(); ?>
					<div class="form-group">
						<label for="email"><?php echo e(__('User Name or Email')); ?><span class="txt-red">*</span></label>
						<input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="User Name or Email " name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus >

                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>
					<div class="form-group">
						<label for="password"><?php echo e(__('Password')); ?> <span class="txt-red">*</span></label>
						<input id="password" type="password" class="form-control" <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password">

                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

                                <?php if(Route::has('password.request')): ?>
					<p>
                                    <a href="<?php echo e(route('password.request')); ?>">
                                        <?php echo e(__('Forgot password ?')); ?>

                                    </a> </p>
                                <?php endif; ?>

					<div class="clr">
						<button type="submit" class="btn-login btn-block">Sign in <img src="<?php echo asset('assets/images/right-to-bracket-solid.png'); ?>" width="15"></button>


					</div>
				</form>

			</div><!--login-box-->
		</div><!--login-page-->
	</div><!--Form_login-->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.ast', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/auth/login.blade.php ENDPATH**/ ?>