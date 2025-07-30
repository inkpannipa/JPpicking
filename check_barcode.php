<?php require_once("include/connect.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Check Picking | Hanshin Neji JP</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background: #f0f8ff;
            color: #333;
        }

        h2,
        h3 {
            color: #007bff;
            text-align: center;
        }

        #reader video,
        #partReader video {
            width: 100% !important;
            height: 180px !important;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        form {
            margin-top: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 16px;
            font-size: 18px;
            border: 2px solid #cce5ff;
            border-radius: 8px;
            margin-bottom: 12px;
            background: #ffffff;
            margin-left: -15px;

        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-left: 3px;
        }

        button:hover {
            background: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #007bff;
            color: white;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .highlight {
            color: #007bff;
            font-weight: 600;
        }

        .card {
            background: white;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        #partBarcode {
            margin-top: 12px;
            width: 310px;
        }

        #partCheckResult {
            margin-top: 8px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .hidden {
            display: none;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- スキャンセクション -->
    <div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
        <h2 id="scanTitle" class="mb-4 text-center">注文番号のバーコードをスキャン</h2>

        <div id="readerContainer" class="mb-4 w-100 d-flex justify-content-center">
            <div id="reader" style="width: 300px; max-width: 100%;"></div>
        </div>



        <form method="POST" id="barcodeForm" class="w-100 d-flex flex-column align-items-center">
            <div class="input-group mb-3" style="max-width: 500px;">
                <input type="text" class="form-control" name="barcode" id="barcode" placeholder="注文番号を入力またはスキャン..."
                    autofocus required>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">🔍 検索</button>
                </div>
            </div>
        </form>

        <audio id="successSound" src="assets/sound/beep.mp3"></audio>
        <audio id="errorSound" src="assets/sound/error.mp3"></audio>

        <?php
        $orderParts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['barcode'])):
            $barcode = preg_replace('/[\x00-\x1F\x7F\s]+/u', '', trim($_POST['barcode']));
            $stmt = $conn->prepare("SELECT * FROM order_parts WHERE order_no = ?");
            $stmt->bind_param("s", $barcode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                $today = date('Ymd');

                // Play success sound and hide input elements with JS (will trigger below)
                echo "<script>window.onload = () => {
                document.getElementById('successSound').play();
                document.getElementById('scanTitle').style.display = 'none';
                document.getElementById('readerContainer').style.display = 'none';
                document.getElementById('barcodeForm').style.display = 'none';
            };</script>";

                while ($row = $result->fetch_assoc()):
                    // If due date expired, delete and skip display
                    if ($row['duedate'] < $today):
                        $deleteStmt = $conn->prepare("DELETE FROM order_parts WHERE order_no = ?");
                        $deleteStmt->bind_param("s", $barcode);
                        $deleteStmt->execute();
                        $deleteStmt->close();
                        echo "<p style='color:red;'>🗑️ 有効期限切れの注文を削除しました: " . htmlspecialchars($barcode) . " (期限: " . htmlspecialchars($row['duedate']) . ")</p>";
                        continue;
                    endif;

                    // Update status_check timestamp
                    $updateStmt = $conn->prepare("UPDATE order_parts SET status_check = CURRENT_TIMESTAMP WHERE order_no = ?");
                    $updateStmt->bind_param("s", $barcode);
                    $updateStmt->execute();
                    $updateStmt->close();
                    ?>
                    <div class="container">
                        <div class="card info-card">
                            <h3>📦 注文番号: <span class="highlight"><?= htmlspecialchars($barcode) ?></span></h3>
                            <p>👤 顧客: <strong><?= htmlspecialchars($row['customer']) ?></strong></p>
                            <p>📌 製品: <strong><?= htmlspecialchars($row['nameproduct']) ?></strong></p>

                            <p>🔧 メイン: <strong><?= htmlspecialchars($row['nameproduct1']) ?></strong>
                            <p>📦 MAIN: <strong id="part-main"><?= htmlspecialchars($row['main']) ?></strong>
                                <?= $row['mainstatus'] === 'Y' ? '✅' : '' ?></p>
                            <p>📦 NT: <strong id="part-nt"><?= htmlspecialchars($row['nt']) ?></strong>
                                <?= $row['ntstatus'] === 'Y' ? '✅' : '' ?></p>
                            <p>📦 W: <strong id="part-w"><?= htmlspecialchars($row['w']) ?></strong>
                                <?= $row['wstatus'] === 'Y' ? '✅' : '' ?></p>
                            <p>📦 SW: <strong id="part-sw"><?= htmlspecialchars($row['sw']) ?></strong>
                                <?= $row['swstatus'] === 'Y' ? '✅' : '' ?></p>
                            <p>📦 TW: <strong id="part-tw"><?= htmlspecialchars($row['tw']) ?></strong>
                                <?= $row['twstatus'] === 'Y' ? '✅' : '' ?></p>
                            <p>📦 CS: <strong id="part-cs"><?= htmlspecialchars($row['cs']) ?></strong>
                                <?= $row['csstatus'] === 'Y' ? '✅' : '' ?></p>
                        </div>
                    </div>

                    <table id="resultTable" style="display:none;">
                        <thead>
                            <tr>
                                <th>注文番号</th>
                                <th>メイン</th>
                                <th>メイン数量</th>
                                <th>NT</th>
                                <th>W</th>
                                <th>SW</th>
                                <th>TW</th>
                                <th>CS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= htmlspecialchars($row['order_no']) ?></td>
                                <td><?= htmlspecialchars($row['main']) ?></td>
                                <td><?= htmlspecialchars($row['qtymain']) ?></td>
                                <td><?= htmlspecialchars($row['nt']) ?></td>
                                <td><?= htmlspecialchars($row['w']) ?></td>
                                <td><?= htmlspecialchars($row['sw']) ?></td>
                                <td><?= htmlspecialchars($row['tw']) ?></td>
                                <td><?= htmlspecialchars($row['cs']) ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <?php
                    $orderParts[] = $row['main'];
                    $orderParts[] = $row['nt'];
                    $orderParts[] = $row['w'];
                    $orderParts[] = $row['sw'];
                    $orderParts[] = $row['tw'];
                    $orderParts[] = $row['cs'];
                endwhile;

                echo '<h3>🔍 内部パーツをスキャンしてください</h3>';
                echo '<div id="partReader"></div>';
                echo '<input type="text" id="partBarcode" name="partBarcode" />';
                echo '<p id="partCheckResult"></p>';
                echo ' <button onclick="history.back()" title="back" style="
    margin-top: 10px;
    border: none;
    background: red;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
">
            &#8592;
        </button>';

                echo <<<JS
<script>
  setTimeout(() => {
    const input = document.getElementById('partBarcode');
    if(input){
      input.focus();
    }
  }, 100);
</script>
JS;

            else:
                // Play error sound and reset form with JS
                echo "<script>window.onload = () => {
                document.getElementById('errorSound').play();
                const barcodeInput = document.getElementById('barcode');
                barcodeInput.value = '';
                barcodeInput.focus();

                // Restart camera scanning (assuming html5QrCode is defined globally)
                if(typeof Html5Qrcode !== 'undefined') {
                    Html5Qrcode.getCameras().then(devices => {
                        if (devices.length) {
                            html5QrCode.start(
                                { facingMode: 'environment' },
                                { fps: 10, qrbox: { width: 250, height: 100 } },
                                (decodedText) => {
                                    html5QrCode.stop().then(() => {
                                        barcodeInput.value = decodedText.trim();
                                        document.getElementById('barcodeForm').submit();
                                    });
                                }
                            );
                        }
                    });
                }
            };</script>";
                echo "<p style='color:red;'>❌ 注文番号が見つかりません: <code>" . htmlspecialchars($barcode) . "</code></p>";
            endif;
            $stmt->close();
        endif;
        $conn->close();
        ?>
    </div>



    <script>
        const hasScanned = <?php echo isset($_POST['barcode']) ? 'true' : 'false'; ?>;
        const orderParts = <?php echo json_encode($orderParts); ?>;
        let html5QrCode = new Html5Qrcode("reader");

        if (!hasScanned) {
            Html5Qrcode.getCameras().then(devices => {
                if (devices.length) {
                    html5QrCode.start(
                        { facingMode: "environment" },
                        { fps: 10, qrbox: { width: 250, height: 100 } },
                        (decodedText) => {
                            html5QrCode.stop().then(() => {
                                document.getElementById("barcode").value = decodedText.trim();
                                document.getElementById("barcodeForm").submit();
                            });
                        }
                    );
                }
            });
        }

        function updateStatusInDB(partType, status, value) {
            fetch("update_status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    order_no: "<?php echo isset($barcode) ? $barcode : ''; ?>",
                    column: partType,
                    status: status,
                    value: value
                })
            })
                .then(res => res.text())
                .then(msg => console.log("📦 Updated DB:", msg))
                .catch(err => console.error("❌ Update failed:", err));
        }

        function checkScannedPart(scannedPart) {
            const resultEl = document.getElementById('partCheckResult');
            console.log("✅ Checking part:", scannedPart);

            const allParts = ['main', 'nt', 'w', 'sw', 'tw', 'cs'];
            let matched = false;
            let matchedColumn = null;

            allParts.forEach(p => {
                const el = document.getElementById(`part-${p}`);
                if (el && el.textContent.includes(scannedPart)) {
                    matched = true;
                    matchedColumn = p;
                    if (!el.innerHTML.includes('✅')) {
                        el.innerHTML += ' ✅';
                    }
                }
            });

            if (matched && matchedColumn) {
                resultEl.textContent = `✅  Order No : ${scannedPart}`;
                resultEl.style.color = "green";
                document.getElementById('successSound').play();

                updateStatusInDB(matchedColumn, 'Y', scannedPart);
            } else {
                resultEl.textContent = `❌ Not found Order No : ${scannedPart} `;
                resultEl.style.color = "red";
                document.getElementById('errorSound').play();

                updateStatusInDB('unknown', 'N', scannedPart);
            }

            document.getElementById('partBarcode').value = '';
            document.getElementById('partBarcode').focus();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const partInput = document.getElementById('partBarcode');
            if (partInput) {
                partInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        checkScannedPart(partInput.value.trim());
                    }
                });
            }

            if (hasScanned) {
                const partQrScanner = new Html5Qrcode("partReader");
                Html5Qrcode.getCameras().then(devices => {
                    if (devices.length) {
                        partQrScanner.start(
                            { facingMode: "environment" },
                            { fps: 10, qrbox: { width: 250, height: 100 } },
                            (decodedText) => {
                                checkScannedPart(decodedText.trim());
                                partQrScanner.stop().then(() => {
                                    setTimeout(() => partQrScanner.start(
                                        { facingMode: "environment" },
                                        { fps: 10, qrbox: { width: 250, height: 100 } },
                                        (d) => { checkScannedPart(d.trim()); }
                                    ), 800);
                                });
                            }
                        );
                    }
                });
            }
        });
    </script>

</body>

</html>