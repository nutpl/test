<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
*{
  box-sizing:border-box;
  font-family: system-ui, -apple-system, "Segoe UI", Roboto;
}

body{
  margin:0;
  background:#ffffff;
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
}

.modal{
  width:420px;
  padding:30px 26px 34px;
  background:#fff;
  border-radius:20px;
}

.header{
  text-align:center;
  margin-bottom:22px;
}

.logo{
  font-weight:700;
  letter-spacing:3px;
  font-size:20px;
}

.title{
  margin-top:16px;
  font-size:22px;
  font-weight:600;
}

.field{
  margin-top:16px;
  position:relative;
}

input{
  width:100%;
  padding:16px 48px 16px 16px;
  border-radius:14px;
  border:1px solid #e5e5eb;
  background:#fafafa;
  font-size:16px;
  outline:none;
}

input:focus{
  border-color:#6c63ff;
  background:#fff;
}

.eye{
  position:absolute;
  right:16px;
  top:50%;
  transform:translateY(-50%);
  cursor:pointer;
  font-size:18px;
  color:#b5b5bd;
}

button{
  width:100%;
  margin-top:24px;
  padding:16px;
  border-radius:16px;
  border:none;
  background:#e9e9ee;
  color:#b5b5bd;
  font-size:16px;
  font-weight:600;
  cursor:not-allowed;
  transition:0.25s;
}

button.active{
  background:#6c63ff;
  color:#fff;
  cursor:pointer;
}

.footer{
  display:flex;
  justify-content:space-between;
  margin-top:18px;
  font-size:14px;
}

.footer a{
  color:#3b82f6;
  text-decoration:none;
}
</style>
</head>
<body>
    
</body>
</html>