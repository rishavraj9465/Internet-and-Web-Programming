<!-- ============================================================
     Assignment 3: AJAX-Based Product Search System
     Author  : RISHAV RAJ  |  23MEI10002
     Covers  : PHP-MySQL Connectivity, AJAX, MySQL Queries
     File    : assignment3_mysql.php  (index.html + search.php combined)
     ============================================================ -->

<!-- ======================================================
     PART A: index.html — Search Interface (Frontend)
     ====================================================== -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AJAX Product Search | Rishav Raj 23MEI10002</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #f0f4f8; padding: 30px; }
    .container { max-width: 800px; margin: auto; background: #fff;
                 padding: 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,.1); }
    h2 { color: #1a5276; margin-bottom: 6px; }
    .subtitle { color: #666; font-size: 13px; margin-bottom: 20px; }
    #searchInput { width: 100%; padding: 12px 16px; font-size: 15px;
                   border: 2px solid #1a5276; border-radius: 6px; outline: none; }
    #searchInput:focus { border-color: #2980b9; }
    #status { font-size: 12px; color: #999; margin: 8px 0; height: 16px; }
    #resultsTable { width: 100%; border-collapse: collapse; margin-top: 10px; display: none; }
    #resultsTable th { background: #1a5276; color: #fff; padding: 10px 14px; text-align: left; }
    #resultsTable td { padding: 9px 14px; border-bottom: 1px solid #e0e0e0; }
    #resultsTable tr:hover td { background: #eaf4fb; }
    .no-result { text-align: center; color: #999; padding: 20px; font-style: italic; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 10px;
             font-size: 11px; font-weight: bold; }
    .cat-electronics { background: #d6eaf8; color: #1a5276; }
    .cat-clothing    { background: #d5f5e3; color: #1e8449; }
    .cat-books       { background: #fef9e7; color: #b7950b; }
    .cat-furniture   { background: #f9ebea; color: #922b21; }
  </style>
</head>
<body>
<div class="container">
  <h2>AJAX Product Search System</h2>
  <div class="subtitle">Author: Rishav Raj | 23MEI10002 &nbsp;|&nbsp; Type to search products live</div>
  <input type="text" id="searchInput" placeholder="Search products by name..." oninput="liveSearch(this.value)">
  <div id="status"></div>
  <table id="resultsTable">
    <thead>
      <tr><th>#</th><th>Product Name</th><th>Category</th><th>Price (Rs.)</th></tr>
    </thead>
    <tbody id="resultsBody"></tbody>
  </table>
</div>

<script>
let timer = null;

function liveSearch(query) {
  clearTimeout(timer);
  const status = document.getElementById('status');
  const table  = document.getElementById('resultsTable');
  const body   = document.getElementById('resultsBody');

  if (query.trim() === '') {
    table.style.display = 'none';
    status.textContent = '';
    body.innerHTML = '';
    return;
  }

  status.textContent = 'Searching...';

  // Debounce: wait 300ms after user stops typing
  timer = setTimeout(() => {
    const xhr = new XMLHttpRequest();
    // ── AJAX Request ──────────────────────────────────────────────
    xhr.open('GET', 'search.php?query=' + encodeURIComponent(query), true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        const data = JSON.parse(xhr.responseText);
        body.innerHTML = '';
        if (data.length === 0) {
          table.style.display = 'table';
          body.innerHTML = '<tr><td colspan="4" class="no-result">No products found.</td></tr>';
          status.textContent = '0 results found.';
        } else {
          table.style.display = 'table';
          data.forEach((item, idx) => {
            const catClass = 'cat-' + item.category.toLowerCase();
            body.innerHTML += `<tr>
              <td>${idx + 1}</td>
              <td>${item.name}</td>
              <td><span class="badge ${catClass}">${item.category}</span></td>
              <td>Rs. ${parseFloat(item.price).toFixed(2)}</td>
            </tr>`;
          });
          status.textContent = data.length + ' result(s) found.';
        }
      }
    };
    xhr.send();
  }, 300);
}
</script>
</body>
</html>

<?php
/* ===========================================================
   PART B: search.php — Server-Side AJAX Handler
   (In production, save this as a separate search.php file)
   ===========================================================

<?php
// ============================================================
// search.php — AJAX Handler for Product Search
// Author : RISHAV RAJ | 23MEI10002
// ============================================================

header('Content-Type: application/json');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'product_db');

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (empty($query)) {
    echo json_encode([]);
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed."]);
    exit;
}

// Use prepared statement with LIKE for safe search
$searchTerm = "%" . $query . "%";
$stmt = $conn->prepare(
    "SELECT id, name, category, price
       FROM products
      WHERE name LIKE ? OR category LIKE ?
      ORDER BY name ASC
      LIMIT 20"
);
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($products);
?>

   =========================================================== */
?>
