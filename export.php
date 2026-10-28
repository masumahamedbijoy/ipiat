<?php
require_once 'config.php'; require_login();
$studentId=(int)($_GET['student_id']??0);$date=$_GET['date']??'';$semesterId=(int)($_GET['semester_id']??0);
$sql="SELECT st.student_name,st.roll_no,se.title semester_title,su.subject_code,su.subject_name,a.attendance_date,a.status,u.name marked_by FROM attendance a JOIN students st ON st.id=a.student_id JOIN subjects su ON su.id=a.subject_id JOIN semesters se ON se.id=st.semester_id JOIN users u ON u.id=a.marked_by WHERE 1=1";$params=[];
if($studentId){$sql.=' AND st.id=?';$params[]=$studentId;}
if($date && preg_match('/^\\d{4}-\\d{2}-\\d{2}$/',$date)){$sql.=' AND a.attendance_date=?';$params[]=$date;}
if($semesterId){$sql.=' AND st.semester_id=?';$params[]=$semesterId;}
$sql.=' ORDER BY a.attendance_date DESC,se.semester_no,st.roll_no,su.subject_code';
$q=$pdo->prepare($sql);$q->execute($params);
header('Content-Type: text/csv; charset=utf-8');header('Content-Disposition: attachment; filename="infra-attendance-'.date('Ymd-His').'.csv"');
$out=fopen('php://output','w');fwrite($out,"\\xEF\\xBB\\xBF");fputcsv($out,['Student name','Roll','Semester','Subject code','Subject name','Date','Status','Marked by']);
while($r=$q->fetch())fputcsv($out,[$r['student_name'],$r['roll_no'],$r['semester_title'],$r['subject_code'],$r['subject_name'],$r['attendance_date'],$r['status'],$r['marked_by']]);
fclose($out);exit;
