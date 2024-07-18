<?php include("../seotamsin-server/auth-control.php");?>
<!doctype html>
<html lang="en" class="dark-theme">

<head>
    <?php include("inc/header.php");
    $site_title="Soyağacı";
    ?>

    <title><?=$config["title"]?> - <?=$site_title?></title>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <?php include("inc/sidebar.php");include("inc/topsidebar.php");?>
        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--end row-->

                <div class="row">
                    <div class="col-xl-12 max-auto">
                        <div class="card">
                            <div class="card-body">
                                <h5><?=$site_title?> Sorgu</h5>
                                <p style="margin-bottom:3rem;">T.C. Girmeniz Zorunludur!</p>
                                <div class="col-xl-12 max-auto" style="text-align:center;">
                                    <div class="col-xl-12" style="display:inline-block">
                                        <div class="input-group mb-3"> <span class="input-group-text"
                                                id="basic-addon1">TC:</span>
                                            <input type="text" id="tc" data-mask="99999999999" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <button id="search" class="btn btn-info" style="color:#fff;">Sorgula</button>
                                        <a href="" button id="submit" href="/" class="btn btn-danger" style="color:#fff;">Temizle</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 max-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap5">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="example2" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Yakınlık</th>
                                                            <th>T.C.</th>
                                                            <th>Ad</th>
                                                            <th>Soyad</th>
                                                            <th>Doğum Tarihi</th>
                                                            <th>Anne Adı</th>
                                                            <th>Anne TC</th>
                                                            <th>Baba Adı</th>
                                                            <th>Baba TC</th>
                                                            <th>İl</th>
                                                            <th>İlçe</th>
                                                            <th>Uyruk</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="veri">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php include("inc/footer.php");?>
        </div>





        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <!--plugins-->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
        <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
        <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
        <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script src="assets/plugins/chartjs/js/chart.js"></script>
        <script src="assets/js/index.js"></script>
        <!--app JS-->
        <script src="assets/js/app.js"></script>
        <script>
        new PerfectScrollbar(".app-container")
        </script>
        <script src="assets/toas.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

        <script>
        $('#search').click(function() {

            $.Toast.showToast({
                "title": "Sorgulanıyor...",
                "icon": "loading",
                "duration": 120000
            });
            $.ajax({
                type: 'POST',
                url: '/seotamsin-api/api-soyagaci.php',
                data: {
                    'tc': $('#tc').val()
                },
                error: function(donen_hata_degeri) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                success: function(data) {
                    $.Toast.hideToast();
                    json = JSON.parse(data);
                    if (json.status == "true") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sonuç Bulundu',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        var table = $('#example2').DataTable().destroy();
                        $("#veri").html(json.data);
                        var table = $('#example2').DataTable({
                            lengthChange: false,
                                buttons: [
                                    'copy',
                                    'excel',
                                    {
                                        extend: 'pdf',
                                        orientation: 'landscape' // PDF'nin yatay olarak görüntülenmesini sağlar
                                    },
                                    'print'
                                ]
                            });

                    table.buttons().container()
                        .appendTo('#example2_wrapper .col-md-6:eq(0)');
                    } else if (json.status == "nodata") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Veri Bulunamadı',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else if (json.status == "limit") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Bu sorgu için günlük limite ulaştınız. (Limit:'+json.limit+')',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else if (json.status == "format") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Hatalı Format',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else if (json.status == "empty") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Tüm Alanları Doldurun!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else if (json.status == "premium+") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Premium+ Üye Olmalısın!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else if (json.status == "cooldown") {
                        Swal.fire({
                            icon: 'warning',
                            title:'Sorgu için kalan saniye:'+json.saniye,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Sunucu Hatası!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }



                }
            });
        });
        </script>
</body>

</html>