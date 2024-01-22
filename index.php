<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Localhost Management - dgknbzglu</title>
  </head>
  <body>
            

        <div class="container mt-5">
            <div class="row">
              <div class="col-sm-6" style="margin-bottom:20px">
                 <a target="_blank" href="/phpmyadmin" style="color:blue;text-decoration: none;">
                <div class="card" style="border-radius: 1.25rem;min-height: 150px;">
                  <div class="card-body d-flex align-items-center justify-content-center">
                   <h6 class="card-title">PHPMyAdmin</h6>
                  </div>
                </div>
                </a>
              </div>

              <div id="proje-ekle" class="col-sm-6" style="margin-bottom:20px">
                 <a target="_blank" style="color:blue;text-decoration: none;">
                <div class="card" style="border-radius: 1.25rem;min-height: 150px;">
                  <div class="card-body d-flex align-items-center justify-content-center">
                   <h6 class="card-title"></h6>
                   <button class="btn btn-success" onclick="yeniKlasorEkle()">
                    <i class="bi bi-plus-square"></i>
                   </button>
                  </div>
                </div>
                </a>
              </div>

              

              
        <?php
        $klasor = "C:\Wamp64\www";
        $icerik = scandir($klasor);
        $klasorler = array_diff($icerik, ["..", "."]);
        $gosterme = ["wamplangues", "wampthemes"];
        usort($klasorler, function ($a, $b) use ($klasor) {
            $aZaman = filectime($klasor . DIRECTORY_SEPARATOR . $a);
            $bZaman = filectime($klasor . DIRECTORY_SEPARATOR . $b);
            return $bZaman - $aZaman;
        });

        foreach ($klasorler as $klasorAdi) {
            $klasorYolu = $klasor . DIRECTORY_SEPARATOR . $klasorAdi;
            if (is_dir($klasorYolu) && !in_array($klasorAdi, $gosterme)) { ?>
              <div class="col-sm-3" style="margin-bottom:20px">
                 <a target="_blank" href="/<?= $klasorAdi ?>" style="color:#4a4a4a;text-decoration: none;">
                <div class="card" style="border-radius: 1.25rem;">
                  <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 100px;">
                   <h6 class="card-title"><?= $klasorAdi ?></h6>
                  </div></a>
                  <div class="card-footer text-center">
                    <button class="btn btn-danger btn-sm" onclick="sil('<?= $klasorAdi ?>')">
                        <i class="bi bi-x"></i>
                    </button>
                  </div>
                </div>
                
              </div>
<?php }
        }
        ?>
            
              
            </div>
        </div>

<script>
    function yeniKlasorEkle() {
    Swal.fire({
        title: 'Yeni Klasör Oluştur',
        input: 'text',
        inputLabel: 'Klasör Adı',
        showCancelButton: true,
        confirmButtonText: 'Oluştur',
        cancelButtonText: 'İptal',
        inputValidator: (value) => {
            if (!value) {
                return 'Klasör adı boş olamaz!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            var yeniKlasorAdi = result.value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "ekle.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var yeniKlasorCardHTML = `
                            <div class="col-sm-3" style="margin-bottom:20px">
    <a target="_blank" href="/${yeniKlasorAdi}" style="color:#4a4a4a;text-decoration: none;">
        <div class="card" style="border-radius: 1.25rem;">
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 100px;">
                <h6 class="card-title">${yeniKlasorAdi}</h6>
            </div>
    </a>

    
          <div class="card-footer text-center">
              <button class="btn btn-danger btn-sm" onclick="sil('${yeniKlasorAdi}')">
                  <i class="bi bi-x"></i>
              </button>
          </div>
        </div>
</div>
                        `;
                        var projeEkleCard = document.getElementById('proje-ekle');
                        projeEkleCard.insertAdjacentHTML('afterend', yeniKlasorCardHTML);

                        var yeniKlasorCard = projeEkleCard.nextElementSibling;
                        $(yeniKlasorCard).hide().fadeIn('slow');
                    } else {
                        Swal.fire({
                            title: 'Hata!',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                }
            };
            xhr.send("klasorAdi=" + encodeURIComponent(yeniKlasorAdi));
        }
    });
}



</script>
<script>
    function sil(klasorAdi) {
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu klasör ve içindekiler kalıcı olarak silinecektir!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, Sil!',
            cancelButtonText: 'Vazgeçtim'
        }).then((result) => {
            if (result.isConfirmed) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "sil.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            var silinenKart = document.querySelector('a[href="/' + response.klasorAdi + '"]').closest('.col-sm-3');
                            $(silinenKart).fadeOut('slow', function() {
                                $(silinenKart).remove();
                            });
                        } else {
                            Swal.fire({
                                title: 'Hata!',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    }
                };
                xhr.send("klasorAdi=" + encodeURIComponent(klasorAdi));
            }
        });
    }
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  </body>
</html>
