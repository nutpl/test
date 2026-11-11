<?php
require './connect.php';

/* =====================
   MASTER DATA
===================== */
$typeQuery     = mysqli_query($conn, "SELECT * FROM cardgame_type");
$typecardQuery = mysqli_query($conn, "SELECT * FROM card_type");

/* =====================
   DELETE
===================== */
if (isset($_GET['delete'])) {

    $id = mysqli_real_escape_string($conn, $_GET['delete']);

    $q = mysqli_query($conn, "SELECT images_Card FROM card_information WHERE Card_ID='$id'");
    $row = mysqli_fetch_assoc($q);

    if (!empty($row['images_Card']) && file_exists($row['images_Card'])) {
        unlink($row['images_Card']);
    }

    mysqli_query($conn, "DELETE FROM card_information WHERE Card_ID='$id'");

    $_SESSION['alert'] = [
        'icon'  => 'success',
        'title' => 'Good job!',
        'text'  => 'ลบข้อมูลสำเร็จ'
    ];

    header("Location: inser.php");
    exit;
}

/* =====================
   INSERT / UPDATE
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $Card_ID      = mysqli_real_escape_string($conn, $_POST['Card_ID']);
    $old_Card_ID  = mysqli_real_escape_string($conn, $_POST['old_Card_ID']);
    $GameType_ID  = mysqli_real_escape_string($conn, $_POST['GameType_ID']);
    $Card_name    = mysqli_real_escape_string($conn, $_POST['Card_name']);
    $Card_type_ID = mysqli_real_escape_string($conn, $_POST['Card_type_ID']);
    $old_image    = $_POST['old_image'];

    $imagePath = $old_image;

    /* ===== Upload Image ===== */
    if (!empty($_FILES['images_Card']['name'])) {

        if (!is_dir('uploads_images')) {
            mkdir('uploads_images', 0777, true);
        }

        $ext = pathinfo($_FILES['images_Card']['name'], PATHINFO_EXTENSION);
        $newName = uniqid() . '.' . $ext;
        $imagePath = 'uploads_images/' . $newName;

        move_uploaded_file($_FILES['images_Card']['tmp_name'], $imagePath);

        if (!empty($old_image) && file_exists($old_image)) {
            unlink($old_image);
        }
    }

    /* ===== INSERT ===== */
    if ($_POST['action'] === 'insert') {

        $check = mysqli_query($conn, "SELECT * FROM card_information WHERE Card_ID='$Card_ID'");
        if (mysqli_num_rows($check) > 0) {
            $_SESSION['alert'] = [
                'icon'  => 'error',
                'title' => 'Oops...',
                'text'  => 'รหัสการ์ดซ้ำ'
            ];
            header("Location: inser.php");
            exit;
        }

        mysqli_query($conn, "
            INSERT INTO card_information
            (Card_ID, GameType_ID, Card_name, Card_type_ID, images_Card)
            VALUES
            ('$Card_ID','$GameType_ID','$Card_name','$Card_type_ID','$imagePath')
        ");

        $_SESSION['alert'] = [
            'icon'  => 'success',
            'title' => 'Good job!',
            'text'  => 'เพิ่มข้อมูลสำเร็จ'
        ];

        header("Location: inser.php");
        exit;
    }

    /* ===== UPDATE ===== */
    if ($_POST['action'] === 'update') {

        mysqli_query($conn, "
            UPDATE card_information SET
                Card_ID='$Card_ID',
                GameType_ID='$GameType_ID',
                Card_name='$Card_name',
                Card_type_ID='$Card_type_ID',
                images_Card='$imagePath'
            WHERE Card_ID='$old_Card_ID'
        ");

        $_SESSION['alert'] = [
            'icon'  => 'success',
            'title' => 'Good job!',
            'text'  => 'แก้ไขข้อมูลสำเร็จ'
        ];

        header("Location: inser.php");
        exit;
    }
}

/* =====================
   SEARCH + PAGINATION
===================== */
$limit = 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = max(1, $page);
$start = ($page - 1) * $limit;

$where = "";
$searchParam = "";

if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where = "WHERE Card_ID LIKE '%$search%' OR Card_name LIKE '%$search%'";
    $searchParam = "&search=" . urlencode($search);
}

$count = mysqli_query($conn, "SELECT COUNT(*) total FROM card_information $where");
$totalData = mysqli_fetch_assoc($count)['total'];
$totalPage = max(1, ceil($totalData / $limit));

$cards = mysqli_query($conn, "
    SELECT * FROM card_information
    $where
    ORDER BY Card_ID ASC
    LIMIT $start, $limit
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการข้อมูลการ์ด</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-body-tertiary">

<?php include 'admin--dashboard.php'; ?>

<div class="container mt-5">

<h3 class="text-center mb-4">จัดการข้อมูลการ์ด</h3>

<button class="btn btn-success mb-3"
data-bs-toggle="modal"
data-bs-target="#cardModal"
onclick="clearForm()">
+ เพิ่มข้อมูล
</button>

<form method="get" class="row g-2 mb-3">
<div class="col-md-4">
<input type="text" name="search" class="form-control"
placeholder="ค้นหา"
value="<?= $_GET['search'] ?? '' ?>">
</div>
<div class="col-md-2">
<button class="btn btn-primary">ค้นหา</button>
<a href="inser.php" class="btn btn-secondary">รีเซ็ต</a>
</div>
</form>

<table class="table table-bordered text-center align-middle">
<thead class="table-info">
<tr>
<th>รหัสการ์ด</th>
<th>รหัสเกม</th>
<th>ชื่อการ์ด</th>
<th>ประเภท</th>
<th>รูป</th>
<th>จัดการ</th>
</tr>
</thead>
<tbody>

<?php if(mysqli_num_rows($cards)): while($c=mysqli_fetch_assoc($cards)): ?>
<tr>
<td><?= $c['Card_ID']; ?></td>
<td><?= $c['GameType_ID']; ?></td>
<td><?= $c['Card_name']; ?></td>
<td><?= $c['Card_type_ID']; ?></td>
<td>
<?php if($c['images_Card']): ?>
<img src="<?= $c['images_Card']; ?>" width="60">
<?php endif; ?>
</td>
<td>
<button class="btn btn-warning btn-sm"
onclick="editData(
'<?= $c['Card_ID']; ?>',
'<?= $c['GameType_ID']; ?>',
'<?= htmlspecialchars($c['Card_name'], ENT_QUOTES); ?>',
'<?= $c['Card_type_ID']; ?>',
'<?= $c['images_Card']; ?>'
)"
data-bs-toggle="modal"
data-bs-target="#cardModal">
แก้ไข
</button>

<a href="#"
onclick="confirmDelete('<?= $c['Card_ID']; ?>','<?= htmlspecialchars($c['Card_name'], ENT_QUOTES); ?>')"
class="btn btn-danger btn-sm">
ลบ
</a>
</td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="6">ไม่พบข้อมูล</td></tr>
<?php endif; ?>

</tbody>
</table>

<!-- PAGINATION -->
<nav>
<ul class="pagination justify-content-center">

<li class="page-item <?= ($page<=1)?'disabled':'' ?>">
<a class="page-link" href="?page=<?= max(1,$page-1).$searchParam ?>">Previous</a>
</li>

<?php for($i=1;$i<=$totalPage;$i++): ?>
<li class="page-item <?= ($i==$page)?'active':'' ?>">
<a class="page-link" href="?page=<?= $i.$searchParam ?>"><?= $i ?></a>
</li>
<?php endfor; ?>

<li class="page-item <?= ($page>=$totalPage)?'disabled':'' ?>">
<a class="page-link" href="?page=<?= min($totalPage,$page+1).$searchParam ?>">Next</a>
</li>

</ul>
</nav>

</div>

<!-- MODAL -->
<div class="modal fade" id="cardModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<form method="post" enctype="multipart/form-data">

<div class="modal-header">
<h5 class="modal-title">เพิ่ม / แก้ไข การ์ด</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" name="old_image" id="old_image">
<input type="hidden" name="old_Card_ID" id="old_Card_ID">

<div class="row g-3">

<div class="col-md-6">
<label>รหัสการ์ด</label>
<input type="text" name="Card_ID" id="Card_ID" class="form-control" required>
</div>

<div class="col-md-6">
<label>ประเภทเกม</label>
<select name="GameType_ID" id="GameType_ID" class="form-select" required>
<option value="">-- เลือก --</option>
<?php mysqli_data_seek($typeQuery,0); while($t=mysqli_fetch_assoc($typeQuery)): ?>
<option value="<?= $t['GameType_ID']; ?>"><?= $t['GameType_ID']; ?></option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-6">
<label>ชื่อการ์ด</label>
<input type="text" name="Card_name" id="Card_name" class="form-control" required>
</div>

<div class="col-md-6">
<label>ประเภทการ์ด</label>
<select name="Card_type_ID" id="Card_type_ID" class="form-select" required>
<option value="">-- เลือก --</option>
<?php mysqli_data_seek($typecardQuery,0); while($tc=mysqli_fetch_assoc($typecardQuery)): ?>
<option value="<?= $tc['Card_type_ID']; ?>"><?= $tc['Card_type_ID']; ?></option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-6">
<label>รูป</label>
<input type="file" name="images_Card" class="form-control">
<img id="previewImage" class="mt-2 d-none" width="100">
</div>

</div>
</div>

<div class="modal-footer">
<button class="btn btn-success" id="submitBtn" name="action" value="insert">บันทึก</button>
</div>

</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
function clearForm(){
Card_ID.value="";
GameType_ID.value="";
Card_name.value="";
Card_type_ID.value="";
old_image.value="";
old_Card_ID.value="";
submitBtn.value="insert";
submitBtn.innerText="บันทึก";
previewImage.classList.add("d-none");
}

function editData(id, game, name, type, image){
Card_ID.value=id;
GameType_ID.value=game;
Card_name.value=name;
Card_type_ID.value=type;
old_Card_ID.value=id;
old_image.value=image;
submitBtn.value="update";
submitBtn.innerText="แก้ไข";

if(image){
previewImage.src=image;
previewImage.classList.remove("d-none");
}
}

function confirmDelete(id, name){
Swal.fire({
title: 'ต้องการลบการ์ดที่ชื่อ "' + name + '" หรือไม่?',
icon: 'warning',
showCancelButton: true,
confirmButtonText: 'ลบ',
cancelButtonText: 'ยกเลิก',
confirmButtonColor: '#d33'
}).then((result)=>{
if(result.isConfirmed){
window.location='?delete='+id;
}
});
}
</script>

<?php if(isset($_SESSION['alert'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
Swal.fire({
icon: "<?= $_SESSION['alert']['icon'] ?>",
title: "<?= $_SESSION['alert']['title'] ?>",
text: "<?= $_SESSION['alert']['text'] ?>",
confirmButtonText: "OK"
});
});
</script>
<?php unset($_SESSION['alert']); endif; ?>

</body>
</html>
