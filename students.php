<?php
require_once 'config.php'; require_login();
$pageTitle='Students'; $message=''; $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $name=trim($_POST['student_name']??''); $roll=trim($_POST['roll_no']??''); $reg=trim($_POST['registration_no']??''); $phone=trim($_POST['phone']??''); $session=trim($_POST['session_name']??''); $semester=(int)($_POST['semester_id']??0);
 if ($name==='' || $roll==='' || $semester<1) $error='Student name, roll number and semester are required.';
 else {
  try { $st=$pdo->prepare("INSERT INTO students(student_name,roll_no,registration_no,phone,session_name,semester_id) VALUES(?,?,?,?,?,?)"); $st->execute([$name,$roll,$reg?:null,$phone?:null,$session?:null,$semester]); $message='Student added successfully.'; }
  catch(PDOException $ex){$error='Roll number already exists or the selected semester is invalid.';}
 }
}
$semesters=$pdo->query('SELECT * FROM semesters ORDER BY semester_no')->fetchAll();
$filter=(int)($_GET['semester']??0); $q=trim($_GET['q']??'');
$sql="SELECT st.*, se.title semester_title FROM students st JOIN semesters se ON se.id=st.semester_id WHERE st.status='active'"; $params=[];
if($filter){$sql.=" AND st.semester_id=?";$params[]=$filter;}
if($q!==''){$sql.=" AND (st.student_name LIKE ? OR st.roll_no LIKE ? OR st.registration_no LIKE ?)";$params[]="%$q%";$params[]="%$q%";$params[]="%$q%";}
$sql.=' ORDER BY se.semester_no, st.roll_no';
$stmt=$pdo->prepare($sql);$stmt->execute($params);$students=$stmt->fetchAll();
require 'partials/header.php';
?>
<div class="section-heading"><div><h2>Student directory</h2><p>Add students and keep semester-wise records organized.</p></div><span class="date-pill"><?= count($students) ?> students shown</span></div>
<?php if($message): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<div class="content-grid students-layout"><section class="panel"><div class="panel-heading"><div><h3>All students</h3><p>Search by name, roll or registration number</p></div></div>
<form method="get" class="filter-row"><input name="q" value="<?= e($q) ?>" placeholder="Search students…"><select name="semester"><option value="0">All semesters</option><?php foreach($semesters as $s): ?><option value="<?= $s['id'] ?>" <?= $filter===$s['id']?'selected':'' ?>><?= e($s['title']) ?></option><?php endforeach; ?></select><button class="btn secondary" type="submit">Filter</button></form>
<div class="table-wrap"><table><thead><tr><th>Student</th><th>Roll / Reg.</th><th>Semester</th><th>Session</th></tr></thead><tbody><?php foreach($students as $st): ?><tr><td><div class="student-cell"><span class="avatar soft"><?= e(strtoupper(substr($st['student_name'],0,1))) ?></span><span><a class="student-name-link" href="student.php?id=<?= (int)$st['id'] ?>"><b><?= e($st['student_name']) ?></b></a><small class="cell-sub"><?= e($st['phone']??'No phone') ?></small></span></div></td><td><b><?= e($st['roll_no']) ?></b><small class="cell-sub"><?= e($st['registration_no']??'—') ?></small></td><td><?= e($st['semester_title']) ?></td><td><?= e($st['session_name']??'—') ?></td></tr><?php endforeach; ?></tbody></table></div>
<?php if(!$students): ?><div class="empty-state"><b>No students found</b><p>Add a student or change your search filters.</p></div><?php endif; ?></section>
<section class="panel form-panel"><div class="panel-heading"><div><h3>Add student</h3><p>Fields marked * are required.</p></div></div><form method="post" class="form-stack"><label>Student full name *<input name="student_name" required maxlength="150" placeholder="Enter student name"></label><label>Roll number *<input name="roll_no" required maxlength="40" placeholder="e.g. 240101"></label><label>Registration number<input name="registration_no" maxlength="60" placeholder="Board registration"></label><label>Phone number<input name="phone" maxlength="30" placeholder="01XXXXXXXXX"></label><label>Academic session<input name="session_name" maxlength="30" placeholder="2025-2026"></label><label>Semester *<select name="semester_id" required><option value="">Select semester</option><?php foreach($semesters as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['title']) ?></option><?php endforeach; ?></select></label><button class="btn primary full" type="submit">Save student <span>→</span></button></form></section></div>
<?php require 'partials/footer.php'; ?>
