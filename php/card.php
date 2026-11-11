<?php 
require './connect.php';

/* =====================
   PAGINATION
===================== */
$limit = 9; // จำนวนการ์ดต่อหน้า (3 ต่อแถว x 2 แถว)
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = ($page < 1) ? 1 : $page;
$start = ($page - 1) * $limit;

/* นับจำนวนข้อมูล */
$countQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM card_information");
$totalRow   = mysqli_fetch_assoc($countQuery);
$totalData  = $totalRow['total'];

$totalPage = ceil($totalData / $limit);
$totalPage = ($totalPage < 1) ? 1 : $totalPage;

/* ดึงข้อมูลตามหน้า */
$card = mysqli_query($conn, "
    SELECT * FROM card_information
    ORDER BY Card_ID ASC
    LIMIT $start, $limit
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Card Gallery</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../fontawesome-free-7.1.0-web/fontawesome-free-7.1.0-web/css/fontawesome.min.css" rel="stylesheet">
<link href="../fontawesome-free-7.1.0-web/fontawesome-free-7.1.0-web/css/brands.min.css" rel="stylesheet">
<link href="../fontawesome-free-7.1.0-web/fontawesome-free-7.1.0-web/css/solid.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include './admin--dashboard.php'; ?>

<div class="container mt-4">

    <div class="row">

        <?php if(mysqli_num_rows($card) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($card)): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">

                    <?php if (!empty($row['images_Card'])): ?>
                        <img src="<?= $row['images_Card']; ?>" 
                             class="card-img-top">
                    <?php endif; ?>

                    <div class="card-body text-center">
                        <h6 class="card-title">
                            <?= $row['Card_name'] ?? 'ไม่มีชื่อการ์ด'; ?>
                        </h6>
                    </div>

                </div>
            </div>
        <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center text-danger">
                ไม่พบข้อมูลการ์ด
            </div>
        <?php endif; ?>

    </div>

    <!-- PAGINATION (แสดงตลอด) -->
    <nav aria-label="...">
      <ul class="pagination justify-content-center">

        <!-- Previous -->
        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= max(1, $page - 1) ?>">
            Previous
          </a>
        </li>

        <!-- Page number -->
        <?php for($i = 1; $i <= $totalPage; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>">
            <?= $i ?>
          </a>
        </li>
        <?php endfor; ?>

        <!-- Next -->
        <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= min($totalPage, $page + 1) ?>">
            Next
          </a>
        </li>

      </ul>
    </nav>

</div>

</body>
</html>
