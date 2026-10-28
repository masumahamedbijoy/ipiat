<?php
require_once 'config.php'; require_login();
$pageTitle='Overview';
$totalStudents=(int)$pdo->query("SELECT COUNT(*) FROM students WHERE status='active'")->fetchColumn();
$totalSubjects=(int)$pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();
$totalSemesters=(int)$pdo->query("SELECT COUNT(*) FROM semesters")->fetchColumn();
$today=date('Y-m-d');
$todayStats=['present'=>0,'absent'=>0,'classes'=>0];
if (!is_weekend_off($today)) {
 $s=$pdo->prepare("SELECT SUM(status='present') present, SUM(status='absent') absent, COUNT(DISTINCT subject_id) classes FROM attendance WHERE attendance_date=?");
 $s->execute([$today]); $r=$s->fetch() ?: [];
 $todayStats['present']=(int)($r['present']??0); $todayStats['absent']=(int)($r['absent']??0); $todayStats['classes']=(int)($r['classes']??0);
}
$recent=$pdo->query("SELECT a.attendance_date, su.subject_name, se.title semester_title, SUM(a.status='present') present_count, SUM(a.status='absent') absent_count FROM attendance a JOIN subjects su ON su.id=a.subject_id JOIN semesters se ON se.id=su.semester_id GROUP BY a.attendance_date,su.id,se.id ORDER BY a.attendance_date DESC LIMIT 6")->fetchAll();
$rate=($todayStats['present']+$todayStats['absent']) ? round($todayStats['present']/($todayStats['present']+$todayStats['absent'])*100) : 0;
require 'partials/header.php';
?>
<section class="welcome-panel"><div><span class="eyebrow light">ACADEMIC YEAR · COMPUTER DEPARTMENT</span><h2>Good to see you, <?= e($_SESSION['name']) ?>.</h2><p>Keep every class accounted for. Your attendance workspace is ready.</p><a class="btn white" href="attendance.php">＋ Take attendance</a></div><div class="welcome-art"><div class="art-ring"></div><div class="art-card"><span>ATTENDANCE RATE</span><strong><?= $rate ?>%</strong><small>Today · recorded entries</small></div></div></section>
<div class="section-heading"><div><h2>Department overview</h2><p>A quick snapshot of your workspace.</p></div><span class="date-pill">◷ <?= date('D, d M Y') ?></span></div>
<div class="stats-grid">
<div class="stat-card"><div class="stat-icon blue">♙</div><span class="stat-label">Active students</span><strong><?= $totalStudents ?></strong><small>Across all semesters</small></div>
<div class="stat-card"><div class="stat-icon purple">▤</div><span class="stat-label">Subjects</span><strong><?= $totalSubjects ?></strong><small>Department curriculum</small></div>
<div class="stat-card"><div class="stat-icon amber">▦</div><span class="stat-label">Semesters</span><strong><?= $totalSemesters ?></strong><small>Four-year diploma</small></div>
<div class="stat-card"><div class="stat-icon green">✓</div><span class="stat-label">Present today</span><strong><?= $todayStats['present'] ?></strong><small><?= $todayStats['absent'] ?> absent · <?= $todayStats['classes'] ?> subjects recorded</small></div>
</div>
<div class="content-grid"><section class="panel"><div class="panel-heading"><div><h3>Recent attendance</h3><p>Latest saved subject sessions</p></div><a class="text-link" href="reports.php">View reports →</a></div>
<?php if(!$recent): ?><div class="empty-state"><span>▤</span><b>No attendance records yet</b><p>Take your first attendance to see it here.</p><a href="attendance.php" class="btn primary">Take attendance</a></div><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Date</th><th>Semester / Subject</th><th>Present</th><th>Absent</th></tr></thead><tbody><?php foreach($recent as $row): ?><tr><td><?= e(date('d M Y',strtotime($row['attendance_date']))) ?></td><td><b><?= e($row['subject_name']) ?></b><small class="cell-sub"><?= e($row['semester_title']) ?></small></td><td><span class="pill present"><?= (int)$row['present_count'] ?></span></td><td><span class="pill absent"><?= (int)$row['absent_count'] ?></span></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</section><section class="panel quick-panel"><div class="panel-heading"><div><h3>Quick actions</h3><p>Common tasks</p></div></div><a class="quick-action" href="students.php"><span class="quick-icon blue">＋</span><span><b>Add a student</b><small>Create a student profile</small></span><span class="chevron">→</span></a><a class="quick-action" href="attendance.php"><span class="quick-icon green">✓</span><span><b>Mark attendance</b><small>Record a class session</small></span><span class="chevron">→</span></a><a class="quick-action" href="reports.php"><span class="quick-icon purple">▤</span><span><b>Track a student</b><small>View attendance history</small></span><span class="chevron">→</span></a><div class="notice"><b>Institute schedule</b><p>Friday and Saturday are off days. Attendance cannot be recorded on these dates.</p></div></section></div>
<?php require 'partials/footer.php'; ?>
