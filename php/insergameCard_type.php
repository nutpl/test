<?php
require './connect.php';

/* =====================
   SEARCH + PAGINATION
===================== */
$search = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, $_GET['search'])
    : '';

$where = '';
if ($search != '') {
    $where = "WHERE GameType_ID LIKE '%$search%'
              OR CardGame_name LIKE '%$search%'";
}

$limit = 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = max(1, $page);
$start = ($page - 1) * $limit;

/* นับจำนวนทั้งหมด */
$countSql = "SELECT COUNT(*) AS total FROM cardgame_type $where";
$countRs  = mysqli_query($conn, $countSql);
$totalRow = mysqli_fetch_assoc($countRs)['total'];
$totalPage = max(1, ceil($totalRow / $limit));

/* ดึงข้อมูล */
$list = mysqli_query($conn,
    "SELECT * FROM cardgame_type
     $where
     ORDER BY GameType_ID
     LIMIT $start,$limit"
);

/* =====================
   INSERT / UPDATE
===================== */
if (isset($_POST['action'])) {

    $old_id        = $_POST['old_id'] ?? '';
    $GameType_ID   = mysqli_real_escape_string($conn, $_POST['GameType_ID']);
    $GameType_name = mysqli_real_escape_string($conn, $_POST['GameType_name']);

    /* INSERT */
    if ($_POST['action'] == 'insert') {

        $check = mysqli_query($conn,
            "SELECT GameType_ID FROM cardgame_type
             WHERE GameType_ID='$GameType_ID'"
        );

        if (mysqli_num_rows($check) > 0) {
            echo "<script>alert('รหัสซ้ำ');location.href='insergameCard_type.php';</script>";
            exit;
        }

        mysqli_query($conn,
            "INSERT INTO cardgame_type (GameType_ID, CardGame_name)
             VALUES ('$GameType_ID','$GameType_name')"
        );
    }

    /* UPDATE */
    if ($_POST['action'] == 'update') {

        if ($old_id != $GameType_ID) {
            $check = mysqli_query($conn,
                "SELECT GameType_ID FROM cardgame_type
                 WHERE GameType_ID='$GameType_ID'"
            );

            if (mysqli_num_rows($check) > 0) {
                echo "<script>alert('รหัสซ้ำ');location.href='insergameCard_type.php';</script>";
                exit;
            }
        }

        mysqli_query($conn,
            "UPDATE cardgame_type
             SET GameType_ID='$GameType_ID',
                 CardGame_name='$GameType_name'
             WHERE GameType_ID='$old_id'"
        );
    }

    echo "<script>alert('บันทึกสำเร็จ');location.href='insergameCard_type.php';</script>";
    exit;
}

/* =====================
   DELETE
===================== */
if (isset($_GET['delete'])) {

    $id = mysqli_real_escape_string($conn, $_GET['delete']);

    $check = mysqli_query($conn,
        "SELECT COUNT(*) AS total
         FROM card_information
         WHERE GameType_ID='$id'"
    );

    $row = mysqli_fetch_assoc($check);

    if ($row['total'] > 0) {
        echo "<script>alert('ไม่สามารถลบได้ มีการใช้งานอยู่');location.href='insergameCard_type.php';</script>";
        exit;
    }

    mysqli_query($conn,
        "DELETE FROM cardgame_type WHERE GameType_ID='$id'"
    );

    header("Location: insergameCard_type.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการประเภทการ์ดเกม</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
}
</style>
</head>

<body>

<?php include 'admin--dashboard.php'; ?>

<div class="container mt-5">
<div class="mx-auto" style="max-width:900px;">

<button class="btn btn-success mb-3"
    data-bs-toggle="modal"
    data-bs-target="#gameTypeModal">
    + เพิ่มข้อมูล
</button>

<div class="card shadow">
<div class="card-body">

<h4 class="text-center mb-3">จัดการประเภทการ์ดเกม</h4>

<!-- ===== SEARCH ===== -->
<form method="get" class="d-flex mb-3 justify-content-end">
    <input type="text"
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        class="form-control form-control-sm me-2"
        style="max-width:250px"
        placeholder="ค้นหารหัส หรือ ชื่อประเภทเกม">

    <button class="btn btn-sm btn-primary">ค้นหา</button>

    <a href="insergameCard_type.php"
        class="btn btn-sm btn-secondary ms-2">
        ล้าง
    </a>
</form>


<table class="table table-bordered text-center align-middle">
<thead class="table-dark">
<tr>
    <th width="150">รหัส</th>
    <th>ชื่อประเภทเกม</th>
    <th width="200">จัดการ</th>
</tr>
</thead>

<tbody>

<?php if (mysqli_num_rows($list) == 0): ?>
<tr>
    <td colspan="3">ไม่พบข้อมูล</td>
</tr>
<?php endif; ?>

<?php while ($row = mysqli_fetch_assoc($list)): ?>
<tr>
    <td><?= $row['GameType_ID'] ?></td>
    <td><?= $row['CardGame_name'] ?></td>
    <td>
        <button class="btn btn-warning btn-sm"
            onclick="editData(
            '<?= $row['GameType_ID'] ?>',
            '<?= $row['CardGame_name'] ?>')">
            แก้ไข
        </button>

        <a href="?delete=<?= $row['GameType_ID'] ?>"
            class="btn btn-danger btn-sm"
            onclick="return confirm('ต้องการลบประเภทการ์ดเกมที่ชื่อ <?= $row['CardGame_name'] ?> หรือไม่?')">
            ลบ
        </a>
    </td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

<!-- ===== PAGINATION ===== -->
<nav>
<ul class="pagination justify-content-center">

<li class="page-item <?= ($page<=1)?'disabled':'' ?>">
    <a class="page-link"
       href="?page=<?= $page-1 ?>&search=<?= $search ?>">
       Previous
    </a>
</li>

<?php for($i=1;$i<=$totalPage;$i++): ?>
<li class="page-item <?= ($i==$page)?'active':'' ?>">
    <a class="page-link"
       href="?page=<?= $i ?>&search=<?= $search ?>">
       <?= $i ?>
    </a>
</li>
<?php endfor; ?>

<li class="page-item <?= ($page>=$totalPage)?'disabled':'' ?>">
    <a class="page-link"
       href="?page=<?= $page+1 ?>&search=<?= $search ?>">
       Next
    </a>
</li>

</ul>
</nav>

</div>
</div>
</div>
</div>

<!-- ================= MODAL ================= -->
<div class="modal fade" id="gameTypeModal">
<div class="modal-dialog">
<div class="modal-content">

<form method="post">

<div class="modal-header">
    <h5 class="modal-title" id="modalTitle">เพิ่มประเภทการ์ดเกม</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" name="old_id" id="old_id">

<div class="mb-3">
    <label>รหัสประเภทเกม</label>
    <input type="text" name="GameType_ID" id="GameType_ID"
        class="form-control" required>
</div>

<div class="mb-3">
    <label>ชื่อประเภทเกม</label>
    <input type="text" name="GameType_name" id="GameType_name"
        class="form-control" required>
</div>

</div>

<div class="modal-footer">
    <button type="submit"
        name="action"
        value="insert"
        id="submitBtn"
        class="btn btn-success">
        บันทึก
    </button>
</div>

</form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function editData(id, name){
    document.getElementById('GameType_ID').value = id;
    document.getElementById('GameType_name').value = name;
    document.getElementById('old_id').value = id;

    document.getElementById('submitBtn').value = 'update';
    document.getElementById('submitBtn').innerText = 'แก้ไข';
    document.getElementById('modalTitle').innerText = 'แก้ไขประเภทการ์ดเกม';

    new bootstrap.Modal(
        document.getElementById('gameTypeModal')
    ).show();
}
</script>

</body>
</html>
