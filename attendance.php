<?php
require_once 'config.php'; require_login();
$pageTitle='Take attendance'; $error=''; $message='';
$semesters=$pdo->query('SELECT * FROM semesters ORDER BY semester_no')->fetchAll();
$semesterId=(int)($_REQUEST['semester_id']??0); $subjectId=(int)($_REQUEST['subject_id']??0); $date=$_REQUEST['attendance_date']??date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/',$date) || !strtotime($date)) $date=date('Y-m-d');
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_attendance'])) {
 if (is_weekend_off($date)) $error='Friday and Saturday are institute off days. Attendance was not saved.';
 elseif (!$semesterId || !$subjectId) $error='Please select a semester and subject.';
 else {
  $check=$pdo->prepare('SELECT COUNT(*) FROM subjects WHERE id=? AND semester_id=?'); $check->execute([$subjectId,$semesterId]);
  if (!(int)$check->fetchColumn()) $error='The selected subject does not belong to this semester.';
  else {
   $studentsStmt=$pdo->prepare("SELECT id FROM students WHERE semester_id=? AND status='active'");$studentsStmt->execute([$semesterId]);$validIds=array_map('intval',$studentsStmt->fetchAll(PDO::FETCH_COLUMN));
   if (!$validIds) $error='There are no active students in this semester.';
   else {
    try {
     $pdo->beginTransaction();
     $save=$pdo->prepare("INSERT INTO attendance(student_id,subject_id,attendance_date,status,marked_by) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE status=VALUES(status),marked_by=VALUES(marked_by),updated_at=CURRENT_TIMESTAMP");
     foreach($validIds as $sid){$status=(($_POST['status'][$sid]??'absent')==='present')?'present':'absent';$save->execute([$sid,$subjectId,$date,$status,$_SESSION['user_id']]);}
     $pdo->commit();$message='Attendance saved successfully for '.count($validIds).' students.';
    } catch(Throwable $ex){if($pdo->inTransaction())$pdo->rollBack();$error='Could not save attendance. Please check the database and try again.';}
   }
  }
 }
}
$subjects=[];
if($semesterId){$st=$pdo->prepare('SELECT * FROM subjects WHERE semester_id=? ORDER BY subject_code');$st->execute([$semesterId]);$subjects=$st->fetchAll();}
$students=[];
if($semesterId){$st=$pdo->prepare("SELECT id,student_name,roll_no FROM students WHERE semester_id=? AND status='active' ORDER BY roll_no");$st->execute([$semesterId]);$students=$st->fetchAll();}
$existing=[];
if($subjectId && $date){$st=$pdo->prepare('SELECT student_id,status FROM attendance WHERE subject_id=? AND attendance_date=?');$st->execute([$subjectId,$date]);foreach($st->fetchAll() as $a)$existing[(int)$a['student_id']]=$a['status'];}
$off=is_weekend_off($date);
require 'partials/header.php';
?>
<div class="section-heading"><div><h2>Record a class session</h2><p>Select the class details, mark each student, then save to the database.</p></div><span class="date-pill">✓ Database connected</span></div>
<?php if($message): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<section class="panel"><div class="panel-heading"><div><h3>Class details</h3><p>Choose a semester, subject and date.</p></div></div>
<form method="get" class="attendance-filters"><label>Semester<select name="semester_id" required onchange="this.form.submit()"><option value="">Select semester</option><?php foreach($semesters as $s): ?><option value="<?= $s['id'] ?>" <?= $semesterId===(int)$s['id']?'selected':'' ?>><?= e($s['title']) ?></option><?php endforeach; ?></select></label><label>Subject<select name="subject_id" required><option value="">Select subject</option><?php foreach($subjects as $s): ?><option value="<?= $s['id'] ?>" <?= $subjectId===(int)$s['id']?'selected':'' ?>><?= e($s['subject_code'].' — '.$s['subject_name']) ?></option><?php endforeach; ?></select></label><label>Class date<input type="date" name="attendance_date" value="<?= e($date) ?>" required></label><button class="btn secondary align-end" type="submit">Load students</button></form>
<?php if($off): ?><div class="alert error">Institute is closed on Friday and Saturday. Choose a working day to take attendance.</div><?php endif; ?>
</section>
<section class="panel attendance-list"><div class="panel-heading"><div><h3>Student attendance <span class="count-badge"><?= count($students) ?></span></h3><p><?= $semesterId ? 'Active students in selected semester' : 'Select a semester to load students' ?></p></div><?php if($students): ?><div class="bulk-actions"><button type="button" class="btn tiny-btn secondary" data-mark-all="present">All present</button><button type="button" class="btn tiny-btn secondary" data-mark-all="absent">All absent</button></div><?php endif; ?></div>
<?php if($students && $subjectId): ?>
<form method="post"><input type="hidden" name="semester_id" value="<?= $semesterId ?>"><input type="hidden" name="subject_id" value="<?= $subjectId ?>"><input type="hidden" name="attendance_date" value="<?= e($date) ?>"><input type="hidden" name="save_attendance" value="1">
<div class="table-wrap"><table class="attendance-table"><thead><tr><th>Student</th><th>Roll number</th><th class="center">Present</th><th class="center">Absent</th></tr></thead><tbody><?php foreach($students as $st): $current=$existing[(int)$st['id']]??'present'; ?><tr><td><div class="student-cell"><span class="avatar soft"><?= e(strtoupper(substr($st['student_name'],0,1))) ?></span><b><?= e($st['student_name']) ?></b></div></td><td><?= e($st['roll_no']) ?></td><td class="center"><label class="radio-pill present-radio"><input type="radio" name="status[<?= $st['id'] ?>]" value="present" <?= $current==='present'?'checked':'' ?>><span>Present</span></label></td><td class="center"><label class="radio-pill absent-radio"><input type="radio" name="status[<?= $st['id'] ?>]" value="absent" <?= $current==='absent'?'checked':'' ?>><span>Absent</span></label></td></tr><?php endforeach; ?></tbody></table></div>
<div class="save-bar"><span><b><?= count($students) ?> students</b><small>Unsaved changes are lost if you leave this page.</small></span><button class="btn primary" type="submit" <?= $off?'disabled':'' ?>>Save attendance <span>→</span></button></div></form>
<?php elseif($semesterId && !$students): ?><div class="empty-state"><b>No students in this semester</b><p>Add student profiles first, then return to attendance.</p><a class="btn primary" href="students.php">Add students</a></div>
<?php else: ?><div class="empty-state"><span>✓</span><b>Your class list will appear here</b><p>Select semester, subject and date above, then click Load students.</p></div><?php endif; ?></section>
<?php require 'partials/footer.php'; ?>
