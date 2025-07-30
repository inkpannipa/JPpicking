<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Check Picking | Hanshin Neji JP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-box {
            background-color: #f8f9fa;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .pin-display-wrapper {
            position: relative;
        }

        .pin-display {
            font-size: 2rem;
            letter-spacing: 10px;
            background-color: #e9ecef;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            min-height: 3rem;
        }

        .toggle-visibility {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .keypad button {
            width: 100%;
            padding: 1rem;
            font-size: 1.5rem;
            border: none;
            border-radius: 0.5rem;
            background-color: #dee2e6;
            transition: background 0.2s;
        }

        .keypad button:hover {
            background-color: #ced4da;
        }

        .btn-login {
            background-color: #0d6efd;
            color: white;
            font-size: 1.1rem;
            padding: 0.75rem;
            border-radius: 50px;
            border: none;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
        }

        .icon-top {
            font-size: 3rem;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <div class="container vh-100 d-flex justify-content-center align-items-center" style="margin-top: 150px;">
        <div class="col-md-4">
            <div class="login-box p-4 border rounded shadow">
                <div class="text-center mb-3">
                    <h5 class="mb-3">Check Picking</h5>
                    <span>Excelファイル（.csv、.xls、.xlsx）<br>をアップロードしてください</span>
                </div>
                <form action="upload_excel.php" method="post" enctype="multipart/form-data">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Upload File</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="excelFile" name="excel_file"
                                accept=".csv,.xls,.xlsx" required>
                            <label class="custom-file-label" for="excelFile" id="fileLabel">Choose file</label>
                        </div>
                    </div>
                    <div class="text-center mt-5 mb-5">
                        <button type="submit" class="btn btn-primary">📤 アップロード</button>
                    </div>
                </form>
                <div class="text-center">
                    <a href="check_barcode.php" class="btn btn-success">
                        🔍 チェック開始
                    </a>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <script>
        document.getElementById('excelFile').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            let fileName = file.name;
            const extension = fileName.split('.').pop();
            const baseName = fileName.substring(0, fileName.lastIndexOf('.'));

            if (baseName.length > 5) {
                fileName = baseName.substring(0, 5) + '...' + '.' + extension;
            }

            document.getElementById('fileLabel').textContent = fileName;
        });
    </script>
</body>

</html>