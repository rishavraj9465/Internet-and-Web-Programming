<?php
// ============================================================
// Lab Programme 10: AJAX-Based Data Fetching System
// Author : Rishav Raj | 23MEI10002
// Subject: Internet and Web Programming (CSE4001)
// ============================================================
// This file combines:
//   index.html  (search interface)
//   search.php  (AJAX JSON handler)
//   db_setup.sql (MySQL schema)
// ============================================================

/* ── db_setup.sql (run first) ────────────────────────────
   CREATE DATABASE IF NOT EXISTS ajax_lab;
   USE ajax_lab;
   CREATE TABLE IF NOT EXISTS students (
       id     INT AUTO_INCREMENT PRIMARY KEY,
       name   VARCHAR(100),
       roll   VARCHAR(20),
       branch VARCHAR(50),
       cgpa   DECIMAL(4,2)
   );
   INSERT INTO students VALUES
   (1,'Rishav Raj','23MEI10002','MEI',8.7),
   (2,'Aarav Sharma','23MEI10003','MEI',9.1),
   (3,'Priya Mehta','23MEI10004','CSE',8.3),
   (4,'Rohit Kumar','23MEI10005','ECE',7.8),
   (5,'Sneha Patel','23MEI10006','IT', 8.9);
   ─────────────────────────────────────────────────────── */

// ═══════════════════════════════════════════════════════════
// SAVE THIS BLOCK AS: search.php
// ═══════════════════════════════════════════════════════════
if (isset($_GET['query'])) {
    header('Content-Type: application/json');
    $conn  = new mysqli("localhost","root","","ajax_lab");
    $term  = "%" . trim($_GET['query']) . "%";
    $stmt  = $conn->prepare(
        "SELECT id,name,roll,branch,cgpa FROM students
          WHERE name LIKE ? OR branch LIKE ?
          ORDER BY name LIMIT 10"
    );
    $stmt->bind_param("ss",$term,$term);
    $stmt->execute();
    $res   = $stmt->get_result();
    $data  = [];
    while ($row = $res->fetch_assoc()) $data[] = $row;
    echo json_encode($data);
    $conn->close();
    exit;
}
?>
<!-- ═══════════════════════════════════════════════════════
     SAVE THIS BLOCK AS: index.html
     ═══════════════════════════════════════════════════════ -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AJAX Student Search | Rishav Raj 23MEI10002</title>
  <style>
    body{font-family:Arial,sans-serif;background:#f4f4f4;padding:30px}
    .box{max-width:700px;margin:auto;background:#fff;padding:25px;
         border-radius:8px;box-shadow:0 2px 12px rgba(0,0,0,.1)}
    h2{margin-bottom:5px}
    .sub{color:#666;font-size:13px;margin-bottom:18px}
    input{width:100%;padding:10px 14px;font-size:15px;
          border:1.5px solid #ccc;border-radius:5px;outline:none}
    input:focus{border-color:#333}
    #status{font-size:12px;color:#888;margin:6px 0;height:16px}
    table{width:100%;border-collapse:collapse;margin-top:8px;display:none}
    th{background:#222;color:#fff;padding:9px 12px;text-align:left}
    td{padding:8px 12px;border-bottom:1px solid #eee}
    tr:hover td{background:#f9f9f9}
  </style>
</head>
<body>
<div class="box">
  <h2>AJAX Student Search</h2>
  <div class="sub">Rishav Raj | 23MEI10002 — Internet & Web Programming (CSE4001)</div>
  <input type="text" id="q" placeholder="Type name or branch..."
         oninput="liveSearch(this.value)">
  <div id="status"></div>
  <table id="tbl">
    <thead><tr><th>#</th><th>Name</th><th>Roll</th><th>Branch</th><th>CGPA</th></tr></thead>
    <tbody id="tbody"></tbody>
  </table>
</div>
<script>
var t=null;
function liveSearch(q){
  clearTimeout(t);
  var st=document.getElementById('status');
  if(q.trim()===''){
    document.getElementById('tbl').style.display='none';
    document.getElementById('tbody').innerHTML='';
    st.textContent=''; return;
  }
  st.textContent='Searching...';
  t=setTimeout(function(){
    var xhr=new XMLHttpRequest();
    xhr.open('GET','search.php?query='+encodeURIComponent(q),true);
    xhr.onreadystatechange=function(){
      if(xhr.readyState===4&&xhr.status===200){
        var d=JSON.parse(xhr.responseText);
        var b=document.getElementById('tbody');
        var tbl=document.getElementById('tbl');
        b.innerHTML='';
        tbl.style.display=d.length?'table':'none';
        d.forEach(function(r,i){
          b.innerHTML+='<tr><td>'+(i+1)+'</td><td>'+r.name+
          '</td><td>'+r.roll+'</td><td>'+r.branch+
          '</td><td>'+parseFloat(r.cgpa).toFixed(2)+'</td></tr>';
        });
        st.textContent=d.length+' result(s) found.';
      }
    };
    xhr.send();
  },300);
}
</script>
</body>
</html>
