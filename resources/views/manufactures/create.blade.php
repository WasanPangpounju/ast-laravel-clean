@extends('layouts.astmanufacturing')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="#">วัตถุดิบ</a>
                </li>
                <li class="breadcrumb-item active">สั่งผลิด</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> สั่งผลิด</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> สั่งผลิด</h2>

                <form method="post" action="{{-- route('manufactures.store') --}}" id="myForm">
                    @csrf

                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplierName">ลายผ้า * </label>
                                <select name="typetag_sack" class="form-control" id="typetag">
                                    <option value="p">3/1</option>
                                    <option value="plastic">4/8</option>
                                    <option value="p">3/1</option>
                                    <option value="plastic">4/8</option>
                                    <option value="p">3/1</option>
                                    <option value="plastic">4/8</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="supplierId" name="supplierId" value="{{ Auth::user()->id }}">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplierName">ริมผ้า * </label>
                                <div class="form-check">
                                    <input type="radio" name="option" value="option1" />
                                    <label class="form-check-label" for="nonfabric">
                                        ไม่ร้อยริมผ้า
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="option" value="option2" />
                                    <label class="form-check-label" for="fabric">
                                        ร้อยริมผ้า
                                    </label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="supplierId" name="supplierId" value="{{ Auth::user()->id }}">
                    </div>{{-- endrow --}}
                    <div class="hide" id="option2-content" style="display:none;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fabricleft">ริมซ้าย </label>
                                </div>
                            </div>
                        </div>{{-- endrow --}}
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="fabricleft">จับตะกรอที่
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricleft1" class="form-control" id="fabricleft1" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricleft2" class="form-control" id="fabricleft2" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricleft3" class="form-control" id="fabricleft3" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricleft4" class="form-control" id="fabricleft4" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricleft5" class="form-control" id="fabricleft5"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricleft6" class="form-control" id="fabricleft6"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="fabricleft">ร้อยตะกอละ
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricleftper" class="form-control" id="fabricleftper"
                                        required> 
                                </div>
                                
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>เส้น(จำนวนเส้น)</label>
                                </div>
                                
                            </div>
                        </div>{{-- endrow --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fabricright">ริมขวา </label>
                                </div>
                            </div>
                        </div>{{-- endrow --}}
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="fabricright">จับตะกรอที่
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricright1" class="form-control" id="fabricright1" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricright2" class="form-control" id="fabricright2" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricright3" class="form-control" id="fabricright3" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricright4" class="form-control" id="fabricright4" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricright5" class="form-control" id="fabricright5"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricright6" class="form-control" id="fabricright6"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="fabricright">ร้อยตะกอละ
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="fabricrightper" class="form-control" id="fabricrightper"
                                        required> 
                                </div>
                                
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>เส้น(จำนวนเส้น)</label>
                                </div>
                                
                            </div>
                        </div>{{-- endrow --}}
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="numberct">เบอร์ฟันหวี </label>
                                </div>
                            </div>
                        </div>{{-- endrow --}}
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    
                                    <input type="text" name="numberct1" class="form-control" id="numberct1" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="fabricright"> ÷ 2 ÷ 2.5</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" name="numberctans" class="form-control" id="numberctans" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="numberct">ช่อง </label>
                                </div>
                            </div>
                            
                        </div>{{-- endrow --}}
                    </div>
                    <script>
                        // Get the radio buttons and content elements
                        const radioButtons = document.querySelectorAll('input[name="option"]');
                        const option1Content = document.getElementById('option1-content');
                        const option2Content = document.getElementById('option2-content');

                        // Add event listener to each radio button
                        radioButtons.forEach((radioButton) => {
                            radioButton.addEventListener('change', (event) => {
                                // Hide all content elements
                                // option1Content.style.display = 'none';
                                option2Content.style.display = 'none';

                                // Show the selected content element
                                const selectedValue = event.target.value;
                                const selectedContent = document.getElementById(`${selectedValue}-content`);
                                selectedContent.style.display = 'block';
                            });
                        });
                    </script>
            </div>
        </div>
        <!--row-->



        <div class="line_btn">
            <button name="submit" value="cleanForm" class="btn b_order clean"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>"
                    width="15"> เคลียร์ข้อมูล</button>
            <button name="submit" value="checkdata" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
                ตรวจสอบ</button>
        </div>

        </form>

    </div>
    <!--box-from-->
    </div>
    <!--content-->
    </div> <!-- /.content-wrapper -->

    <script>
        var input, filter, ul, li, a, i, txtValue;

        ul = document.getElementById("supp");
        li = ul.getElementsByTagName("li");
        //alert(li.length);

        for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
        }
    </script>

    <script>
        function supplierFunction(id) {
            var input, filter, ul, li, a, i, txtValue;

            input = document.getElementById(id);
            filter = input.value.toUpperCase();
            ul = document.getElementById("supp");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                if (filter == "") {
                    for (i = 0; i < li.length; i++) {
                        li[i].style.display = "none";
                    }
                    break;
                }

                a = li[i].getElementsByTagName("a")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }


        function setsupplierFunction(t, t1) {
            document.getElementById("supplierName").value = t1;
            ul = document.getElementById(t);
            //ul.style.display = "none";  
            li = ul.getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

        }
    </script>

    <script>
        function weightConverter1(valNum, id) {
            //  document.getElementById("weight_kg_sum").innerHTML=valNum/2.2046;
            var p = (valNum * 2.2046).toFixed(4);
            document.getElementById(id).value = p;


            if (id ?? "weight_kg_package") {
                var net = 0;
                if (p > 0) {
                    var x = document.getElementById('weight_p_sum').value;
                    if (x > 0) {
                        net = (x - p).toFixed(4);

                        document.getElementById('weight_p_net').value = net;
                        document.getElementById('weight_kg_net').value = (net / 2.2046).toFixed(4);

                        var y = document.getElementById('spool').value;
                        var av = 0;
                        if (y > 0) {
                            av = (net / y).toFixed(4);
                        }
                        document.getElementById('average_p').value = av;
                        document.getElementById('average_kg').value = ((net / 2.2046) / y).toFixed(4);

                    }
                }
            }
        }

        function setCount(valNum, id) {
            //  document.getElementById("weight_kg_sum").innerHTML=valNum/2.2046;
            document.getElementById(id).value = valNum;
        }


        function weightConverter(valNum, id) {
            var k = (valNum / 2.2046).toFixed(4);
            document.getElementById(id).value = k;

            if (id ?? "weight_p_package") {
                var net = 0;
                if (k > 0) {
                    var x = document.getElementById('weight_p_sum').value;
                    if (x > 0) {
                        net = (x - valNum).toFixed(4);
                        //alert(net);

                        document.getElementById('weight_p_net').value = net;
                        document.getElementById('weight_kg_net').value = (net / 2.2046).toFixed(4);

                        var y = document.getElementById('spool').value;
                        var av = 0;
                        if (y > 0) {
                            av = (net / y).toFixed(4);
                        }
                        document.getElementById('average_p').value = av;
                        document.getElementById('average_kg').value = ((net / 2.2046) / y).toFixed(4);

                    }
                }


            }

        }
    </script>

    <script>
        var ul, li;

        ul = document.getElementsByClassName("yarntypeUL")[0];
        li = ul.getElementsByTagName("li");
        //alert(li.length);

        for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
        }

        function yarntypeFunction(id) {
            var input, filter, ul, li, a, i, txtValue;

            input = document.getElementById(id);
            filter = input.value.toUpperCase();
            //ul = document.getElementById("syt");
            ul = document.getElementsByClassName("yarntypeUL")[0];

            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                if (filter == "") {
                    for (i = 0; i < li.length; i++) {
                        li[i].style.display = "none";
                    }
                    break;
                }

                a = li[i].getElementsByTagName("a")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }


        function setyarntypeFunction(t, t1) {
            document.getElementById("yarnType").value = t1;
            //ul = document.getElementById(t);
            ul = document.getElementsByClassName("yarntypeUL")[0];

            //ul.style.display = "none";  
            li = ul.getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

        }


        function cleanformFunction() {
            $("#myForm").trigger("reset");
        }
    </script>
@endsection
