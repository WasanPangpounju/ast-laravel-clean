

<?php $__env->startSection('content'); ?>
  <!-- Content Wrapper. Contains page content -->
 
  <div class="content-wrapper">
   <div class="">
       <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
        </ol>
   </div>
    <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
           <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> หน้าหลัก</h1>
        </div>
      </div>
    </div>
    <!-- Main content -->
    <div class="content">
       
      
    </div>

  </div>
  <!-- /.content-wrapper -->


                    <?php echo e(__('You are logged in!')); ?>

                    <?php echo e(Auth::user()->name); ?>

: 
                    <?php echo e(Auth::user()->user_type); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/home.blade.php ENDPATH**/ ?>