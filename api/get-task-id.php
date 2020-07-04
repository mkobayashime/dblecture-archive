<?php
$host = "localhost";
if (!$conn = mysqli_connect($host, "s2010127", "pass", "s2010127")){
    die("データベース接続エラー.<br />");
}
mysqli_select_db($conn, "kisop");
mysqli_set_charset($conn, "utf8");

if ($_SERVER["REQUEST_METHOD"] === 'GET') {
  $taskClassId;
  $taskName;

  if (isset($_GET["taskClassId"])) {
    $taskClassId = $_GET["taskClassId"];
    $taskClassId = str_replace("%", "\%", $taskClassId);
  }
  if (isset($_GET["taskName"])) {
    $taskName = $_GET["taskName"];
    $taskName = str_replace("%", "\%", $taskName);
  }

  $sql = "SELECT task_id FROM task WHERE class_id = \"$taskClassId\" AND task_name = \"$taskName\"";

  $res = mysqli_query($conn, $sql);
   
  $taskdata = array();
  while ($row = mysqli_fetch_array($res)) {
    $taskdata[] = array(
      "taskId"=>$row["task_id"]
    );
  }

  echo json_encode($taskdata[0]);
}