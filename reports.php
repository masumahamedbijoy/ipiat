<?php
require_once 'config.php'; require_login();
$pageTitle='Attendance reports';
$semesters=$pdo->query('SELECT * FROM semesters ORDER BY semester_no')->fetchAll();
$q=trim($_GET['q']??'');$semesterId=(int)($_GET['semester_id']??0);
$sql="SELECT st.id,st.student_name,st.roll_no,se.title semester_title,COUNT(a.id) total_classes,SUM(a.status='present') present_count,SUM(a.status='absent') absent_count FROM students st JOIN semesters se ON se.id=st.semester_id LEFT JOIN attendance a ON a.student_id=st.id WHERE st.status='active'";$params=[];
if($semesterId){$sql.=' AND st.semester_id=?';$params[]=$semesterId;}
if($q!==''){$sql.=' AND (st.student_name LIKE ? OR st.roll_no LIKE ?)';$params[]="%$q%";$params[]="%$q%";}
$sql.=' GROUP BY st.id,st.student_name,st.roll_no,se.title ORDER BY se.title,st.roll_no';
$st=$pdo->prepare($sql);$st->execute($params);$rows=$st->fetchAll();
require 'partials/header.php';
?>
<div class="section-heading"><div><h2>Attendance tracking</h2><p>Find any student and review their recorded attendance.</p></div><span class="date-pill">Live database report</span></div>
<section class="panel"><div class="panel-heading"><div><h3>Student attendance summary</h3><p>Percentage is based on saved attendance records for all subjects.</p></div></div>
<form method="get" class="filter-row"><input name="q" value="<?= e($q) ?>" placeholder="Search by student name or roll…"><select name="semester_id"><option value="0">All semesters</option><?php foreach($semesters as $s): ?><option value="<?= $s['id'] ?>" <?= $semesterId===(int)$s['id']?'selected':'' ?>><?= e($s['title']) ?></option><?php endforeach; ?></select><button class="btn secondary">Search</button></form>
<div class="table-wrap"><table><thead><tr><th>Student</th><th>Semester</th><th>Total records</th><th>Present</th><th>Absent</th><th>Attendance rate</th></tr></thead><tbody><?php foreach($rows as $r): $total=(int)$r['total_classes'];$present=(int)($r['present_count']??0);$absent=(int)($r['absent_count']??0);$percent=$total?round($present/$total*100):0; ?><tr><td><b><?= e($r['student_name']) ?></b><small class="cell-sub">Roll: <?= e($r['roll_no']) ?></small></td><td><?= e($r['semester_title']) ?></td><td><?= $total ?></td><td><span class="pill present"><?= $present ?></span></td><td><span class="pill absent"><?= $absent ?></span></td><td><div class="rate-cell"><div class="progress"><span style="width:<?= $percent ?>%"></span></div><b><?= $percent ?>%</b></div></td></tr><?php endforeach; ?></tbody></table></div>
<?php if(!$rows): ?><div class="empty-state"><b>No matching students</b><p>Try another search or add students first.</p></div><?php endif; ?></section>
<?php require 'partials/footer.php'; ?>
