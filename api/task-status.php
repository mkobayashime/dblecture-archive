<?php
$host = "localhost";
if (!$conn = mysqli_connect($host, "s2010127", "pass", "s2010127")){
    die("データベース接続エラー.<br />");
}
mysqli_select_db($conn, "kisop");
mysqli_set_charset($conn, "utf8");

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
  $userId;
  $taskClassId;
  $taskId;

  if (isset($_POST["userId"])) {
    $userId = $_POST["userId"];
    $userId = str_replace("%", "\%", $userId);
  }
  if (isset($_POST["taskClassId"])) {
    $taskClassId = $_POST["taskClassId"];
    $taskClassId = str_replace("%", "\%", $taskClassId);
  }
  if (isset($_POST["taskId"])) {
    $taskId = $_POST["taskId"];
    $taskId = str_replace("%", "\%", $taskId);
  }

  if ($_POST["method"] === 'done') {
    $datetime = date('Y').'-'.date('m').'-'.date('d').' '.date('H').':'.date('i').':'.date('s');

    $done = "UPDATE task_status SET submit_date = \"$datetime\" WHERE class_id = \"$taskClassId\" AND task_id = $taskId AND user_id = $userId";
    mysqli_query($conn, $done);
  } elseif ($_POST["method"] === 'undone') {
    $undone = "UPDATE task_status SET submit_date = NULL WHERE class_id = \"$taskClassId\" AND task_id = $taskId AND user_id = $userId";
    
    mysqli_query($conn, $undone);
  } elseif ($_POST["method"] === 'init') {
    $init = "INSERT INTO task_status values(\"$userId\", \"$taskClassId\", $taskId, NULL)";

    mysqli_query($conn, $init);
  }
}
