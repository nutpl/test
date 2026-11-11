<?php
require './connect.php';

/* =====================
   SEARCH
===================== */
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$searchSql = "";
$searchParam = "";

if ($search != '') {
    $searchSql = "WHERE Card_type_ID LIKE '%$search%' 
                  OR Card_type_name LIKE '%$search%'";
    $searchParam = "&search=".$search;
}

/* =====================
   PAGINATION
===================== */
$limit = 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = ($page < 1) ? 1 : $page;
$start = ($page - 1) * $limit;

$totalResult = mysqli_query($conn,
    "SELECT COUNT(*) as total FROM card_type $searchSql");
$totalRow  = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];
$totalPage = ceil($totalData / $limit);

/* =====================
   INSERT / UPDATE
===================== */
if (isset($_POST['action'])) {

    $Card_type_ID   = mysqli_real_escape_string($conn, $_POST['Card_type_ID']);
    $Card_type_name = mysqli_real_escape_string($conn, $_POST['Card_type_name']);
    $old_id         = mysqli_real_escape_string($conn, $_POST['old_id'] ?? '');

    if ($_POST['action'] == 'insert') {

        $check = mysqli_query($conn,
            "SELECT * FROM card_type WHERE Card_type_ID='$Card_type_ID'");
        if (mysqli_num_rows($check) > 0) {
            echo "<script>alert('รหัสซ้ำ');window.location.href='inserCard_type.php';</script>";
            exit;
        }

        mysqli_query($conn,
            "INSERT INTO card_type (Card_type_ID, Card_type_name)
             VALUES ('$Card_type_ID','$Card_type_name')");
    }

    if ($_POST['action'] == 'update') {

        if ($Card_type_ID != $old_id) {
            $check = mysqli_query($conn,
                "SELECT * FROM card_type WHERE Card_type_ID='$Card_type_ID'");
            if (mysqli_num_rows($check) > 0) {
                echo "<script>alert('รหัสซ้ำ');window.location.href='inserCard_type.php';</script>";
                exit;
            }
        }

        mysqli_query($conn,
            "UPDATE card_type
             SET Card_type_ID='$Card_type_ID',
                 Card_type_name='$Card_type_name'
             WHERE Card_type_ID='$old_id'");
    }

    echo "<script>alert('บันทึกสำเร็จ');window.location.href='inserCard_type.php';</script>";
    exit;
}

/* =====================
   DELETE
===================== */
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn,
        "DELETE FROM card_type WHERE Card_type_ID='$id'");
    header("Location: inserCard_type.php");
    exit;
}

/* =====================
   SELECT DATA
===================== */
$list = mysqli_query($conn,
    "SELECT * FROM card_type
     $searchSql
     ORDER BY Card_type_ID ASC
     LIMIT $start, $limit");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการประเภทการ์ด</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background-color:#f8f9fa;
}
.card{
    border-radius:12px;
}
</style>
</head>
<body>

<?php include 'admin--dashboard.php'; ?>

<div class="container mt-5" style="max-width:900px;">

<button class="btn btn-success mb-3"
        data-bs-toggle="modal"
        data-bs-target="#cardTypeModal">
+ เพิ่มข้อมูล
</button>

<div class="card p-4 shadow-sm">

<div class="d-flex justify-content-between align-items-center mb-3">
<h4 class="mb-0">รายการประเภทการ์ด</h4>

<form method="get" class="d-flex">
<input type="text"
       name="search"
       class="form-control me-2"
       style="width:180px;"
       placeholder="ค้นหา..."
       value="<?= htmlspecialchars($search) ?>">
<button class="btn btn-primary btn-sm">ค้นหา</button>
<a href="inserCard_type.php" class="btn btn-secondary btn-sm ms-2">ล้าง</a>
</form>
</div>

<table class="table table-bordered table-sm text-center align-middle">
<thead class="table-dark">
<tr>
<th width="30%">รหัสประเภทการ์ด</th>
<th width="40%">ชื่อประเภทการ์ด</th>
<th width="30%">จัดการ</th>
</tr>
</thead>
<tbody>

<?php if(mysqli_num_rows($list)==0){ ?>
<tr><td colspan="3">ไม่พบข้อมูล</td></tr>
<?php } ?>

<?php while($row=mysqli_fetch_assoc($list)){ ?>
<tr>
<td><?= $row['Card_type_ID'] ?></td>
<td><?= $row['Card_type_name'] ?></td>
<td>

<button class="btn btn-warning btn-sm"
onclick="editData('<?= $row['Card_type_ID'] ?>','<?= $row['Card_type_name'] ?>')">
แก้ไข
</button>

<a href="?delete=<?= $row['Card_type_ID'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('ยืนยันการลบ?')">
ลบ
</a>

</td>
</tr>
<?php } ?>

</tbody>
</table>

<nav>
<ul class="pagination justify-content-center">

<li class="page-item <?= ($page<=1)?'disabled':'' ?>">
<a class="page-link" href="?page=<?= $page-1 . $searchParam ?>">Previous</a>
</li>

<?php for($i=1;$i<=$totalPage;$i++): ?>
<li class="page-item <?= ($i==$page)?'active':'' ?>">
<a class="page-link" href="?page=<?= $i . $searchParam ?>"><?= $i ?></a>
</li>
<?php endfor; ?>

<li class="page-item <?= ($page>=$totalPage)?'disabled':'' ?>">
<a class="page-link" href="?page=<?= $page+1 . $searchParam ?>">Next</a>
</li>

</ul>
</nav>

</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="cardTypeModal">
<div class="modal-dialog" style="max-width:420px;">
<div class="modal-content">

<form method="post">

<div class="modal-header">
<h5 class="modal-title" id="modalTitle">เพิ่มประเภทการ์ด</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" name="old_id" id="old_id">

<div class="mb-3">
<label class="form-label">รหัสประเภท</label>
<input type="text" name="Card_type_ID" id="Card_type_ID" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">ชื่อประเภท</label>
<input type="text" name="Card_type_name" id="Card_type_name" class="form-control" required>
</div>

</div>

<div class="modal-footer">
<button type="submit"
        name="action"
        value="insert"
        id="submitBtn"
        class="btn btn-success w-100">
บันทึก
</button>
</div>

</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function editData(id,name){
document.getElementById('Card_type_ID').value=id;
document.getElementById('Card_type_name').value=name;
document.getElementById('old_id').value=id;

document.getElementById('submitBtn').value="update";
document.getElementById('submitBtn').innerText="แก้ไข";
document.getElementById('modalTitle').innerText="แก้ไขประเภทการ์ด";

new bootstrap.Modal(document.getElementById('cardTypeModal')).show();
}
</script>

</body>
</html>
