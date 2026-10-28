<?php
require_once 'config.php'; require_admin();
$pageTitle='Administration'; $error=''; $message='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_csrf(); $action=$_POST['action']??'';
 try {
  if($action==='add_subject' || $action==='edit_subject'){
   $sem=(int)($_POST['semester_id']??0);$code=trim($_POST['subject_code']??'');$name=trim($_POST['subject_name']??'');
   if(!$sem||$code===''||$name==='') throw new RuntimeException('Complete all subject fields.');
   if($action==='add_subject'){$q=$pdo->prepare('INSERT INTO subjects(semester_id,subject_code,subject_name) VALUES(?,?,?)');$q->execute([$sem,$code,$name]);}
   else{$id=(int)($_POST['subject_id']??0);$q=$pdo->prepare('UPDATE subjects SET semester_id=?,subject_code=?,subject_name=? WHERE id=?');$q->execute([$sem,$code,$name,$id]);}
   $message='Subject saved successfully.';
  } elseif($action==='delete_subject'){
   $id=(int)($_POST['subject_id']??0);$q=$pdo->prepare('SELECT COUNT(*) FROM attendance WHERE subject_id=?');$q->execute([$id]);
   if((int)$q->fetchColumn()) throw new RuntimeException('This subject has attendance history. Edit its name instead of deleting it.');
   $q=$pdo->prepare('DELETE FROM subjects WHERE id=?');$q->execute([$id]);$message='Subject deleted.';
  } elseif($action==='change_role'){
   $id=(int)($_POST['user_id']??0);$role=$_POST['role']??'teacher';
   if($id===(int)$_SESSION['user_id']) throw new RuntimeException('You cannot change your own role.');
   if(!in_array($role,['admin','teacher'],true)) throw new RuntimeException('Invalid role.');
   $q=$pdo->prepare('UPDATE users SET role=? WHERE id=?');$q->execute([$role,$id]);$message='Account role updated.';
  }
 } catch(Throwable $ex){$error=$ex instanceof RuntimeException?$ex->getMessage():'Could not save. Check for duplicate subject codes.';}
}
$semesters=$pdo->query('SELECT * FROM semesters ORDER BY semester_no')->fetchAll();
$subjects=$pdo->query('SELECT su.*,se.title semester_title FROM subjects su JOIN semesters se ON se.id=su.semester_id ORDER BY se.semester_no,su.subject_code')->fetchAll();
$users=$pdo->query('SELECT id,name,email,role FROM users ORDER BY name')->fetchAll();
require 'partials/header.php';
?>
<div class="section-heading"><div><h2>Administration</h2><p>Manage subjects and staff access for the Computer Department.</p></div><span class="date-pill">Admin access</span></div>
<?php if($message): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<div class="content-grid admin-grid"><section class="panel"><div class="panel-heading"><div><h3>Add a subject</h3><p>Use official subject codes and titles.</p></div></div>
<form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="add_subject"><label>Semester<select name="semester_id" required><?php foreach($semesters as $s): ?><option value="<?= (int)$s['id'] ?>"><?= e($s['title']) ?></option><?php endforeach; ?></select></label><label>Subject code<input name="subject_code" maxlength="30" required placeholder="e.g. 28531"></label><label>Subject name<input name="subject_name" maxlength="150" required placeholder="Official subject title"></label><button class="btn primary">＋ Add subject</button></form></section>
<section class="panel"><div class="panel-heading"><div><h3>Staff accounts</h3><p>Assign trusted staff as Admin or Teacher.</p></div></div><div class="table-wrap"><table><thead><tr><th>Name / Email</th><th>Role</th><th>Change role</th></tr></thead><tbody><?php foreach($users as $u): ?><tr><td><b><?= e($u['name']) ?></b><small class="cell-sub"><?= e($u['email']) ?></small></td><td><?= e(ucfirst($u['role'])) ?></td><td><?php if((int)$u['id']!==(int)$_SESSION['user_id']): ?><form method="post" class="inline-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="change_role"><input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>"><select name="role"><option value="teacher" <?= $u['role']==='teacher'?'selected':'' ?>>Teacher</option><option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>Admin</option></select><button class="btn tiny-btn secondary">Save</button></form><?php else: ?><small>Current account</small><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div></section></div>
<section class="panel subject-panel"><div class="panel-heading"><div><h3>Subject catalogue</h3><p>Edit subject names and semester assignments.</p></div><span class="date-pill"><?= count($subjects) ?> subjects</span></div><div class="table-wrap"><table><thead><tr><th>Current semester</th><th>Subject details</th><th>Action</th></tr></thead><tbody><?php foreach($subjects as $su): ?><tr><td><?= e($su['semester_title']) ?></td><td><form method="post" class="subject-edit-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="edit_subject"><input type="hidden" name="subject_id" value="<?= (int)$su['id'] ?>"><select name="semester_id"><?php foreach($semesters as $sem): ?><option value="<?= (int)$sem['id'] ?>" <?= (int)$su['semester_id']===(int)$sem['id']?'selected':'' ?>><?= e($sem['title']) ?></option><?php endforeach; ?></select><input name="subject_code" value="<?= e($su['subject_code']) ?>" required><input name="subject_name" value="<?= e($su['subject_name']) ?>" required><button class="btn tiny-btn secondary">Update</button></form></td><td><form method="post" onsubmit="return confirm('Delete this subject?');"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete_subject"><input type="hidden" name="subject_id" value="<?= (int)$su['id'] ?>"><button class="btn tiny-btn danger-btn">Delete</button></form></td></tr><?php endforeach; ?></tbody></table></div></section>
<?php require 'partials/footer.php'; ?>
