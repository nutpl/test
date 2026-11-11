    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>จัดการข้อมูล</title>

        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>

    <?php include 'admin--dashboard.php'; ?>

    <!-- =======================
        MAIN CONTENT
    ======================= -->
    <div class="container min-vh-100 d-flex justify-content-center align-items-center pt-5">
        <div class="col-md-6 col-lg-5">

            <h2 class="text-center mb-4">จัดการข้อมูลการ์ด</h2>

            <!-- จัดการข้อมูลการ์ด -->
            <div class="card p-4 mb-4 text-center shadow-sm">
                <h5 class="mb-3">เพิ่ม / แก้ไข / ลบ การ์ด</h5>
                <a href="inser.php" class="btn btn-primary w-100">
                    ไปยังหน้าจัดการข้อมูลการ์ด
                </a>
            </div>

            <!-- จัดการประเภทการ์ดเกม -->
            <div class="card p-4 mb-4 text-center shadow-sm">
                <h5 class="mb-3">เพิ่ม / แก้ไข / ลบ ประเภทการ์ดเกม</h5>
                <a href="insergameCard_type.php" class="btn btn-success w-100">
                    ไปยังหน้าจัดการข้อมูลประเภทการ์ดเกม
                </a>
            </div>

            <!-- จัดการประเภทการ์ด -->
            <div class="card p-4 text-center shadow-sm">
                <h5 class="mb-3">เพิ่ม / แก้ไข / ลบ ประเภทการ์ด</h5>
                <a href="inserCard_type.php" class="btn w-100 text-white" style="background-color: #722f37; border-color: #722f37;">
                    ไปยังหน้าจัดการข้อมูลประเภทการ์ด
                </a>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    </body>
    </html>
