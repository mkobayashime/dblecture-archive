<?php
$host = "localhost";
if (!$conn = mysqli_connect($host, "s2010127", "pass", "s2010127")){
    die("データベース接続エラー.<br />");
}
mysqli_select_db($conn, "kisop");
mysqli_set_charset($conn, "utf8");

$sql = "SELECT * FROM class, class_taking, task, task_status WHERE class.class_id = class_taking.class_id AND class_taking.class_id = task.class_id AND task.class_id = task_status.class_id AND task.task_id = task_status.task_id";

$userId;
$classId;

if ($_SERVER["REQUEST_METHOD"] === 'GET') {
  if (isset($_GET["userId"])) {
    $userId = $_GET["userId"];
    $userId = str_replace("%", "\%", $userId);

    $sql = "$sql AND class_taking.user_id = \"$userId\"";
  }
  if (isset($_GET["filterClassId"])) {
    $filterClassId = $_GET["filterClassId"];
    $filterClassId = str_replace("%", "\%", $filterClassId);

    $sql = "$sql AND class_taking.class_id = \"$filterClassId\"";
  }
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
  
  if (isset($_POST["userId"])) {
    $userId = $_POST["userId"];
    $userId = str_replace("%", "\%", $userId);
    
    $sql = "$sql AND class_taking.user_id = \"$userId\"";
  }
  if (isset($_POST["taskName"])) {
    $taskName = mysqli_escape_string($conn, $_POST["taskName"]);
    $taskName = str_replace("%", "\%", $taskName);
  }
  if (isset($_POST["taskClassId"])) {
    $taskClassId = $_POST["taskClassId"];
    $taskClassId = str_replace("%", "\%", $taskClassId);
  }
  if (isset($_POST["taskDeadline"])) {
    $taskDeadline = $_POST["taskDeadline"];
    $taskDeadline = str_replace("%", "\%", $taskDeadline);
  }
  if (isset($_POST["filterClassId"])) {
    if ($_POST["filterClassId"] !== '') {
      $filterClassId = $_POST["filterClassId"];
      $filterClassId = str_replace("%", "\%", $filterClassId);
      
      $sql = "$sql AND class_taking.class_id = \"$filterClassId\"";
    }
  }

  $taskId;
  $taskContent;

  if ($_POST["isPut"] === 'true') {
    if (isset($_POST["taskId"])) {
      $taskId = $_POST["taskId"];
      $taskId = str_replace("%", "\%", $taskId);
    }
    if (isset($_POST["taskContent"])) {
      $taskContent = $_POST["taskContent"];
      $taskContent = str_replace("%", "\%", $taskContent);
    }
    
    $updateTask = "UPDATE task SET task_name = \"$taskName\", task_content = \"$taskContent\" WHERE task.class_id = \"$taskClassId\" AND task.task_id = \"$taskId\"";
    
    mysqli_query($conn, $updateTask);
    
  } elseif ($_POST["isDelete"] === 'true') {
    if (isset($_POST["taskId"])) {
      $taskId = $_POST["taskId"];
      $taskId = str_replace("%", "\%", $taskId);
    }

    $deleteTask = "DELETE FROM task WHERE task.class_id = \"$taskClassId\" AND task.task_id = \"$taskId\"";

    mysqli_query($conn, $deleteTask);

  } else {
    $insertIntoTask = "INSERT INTO task(class_id, task_name, task_deadline, task_content) VALUES(\"$taskClassId\", \"$taskName\", \"$taskDeadline\", \"\")";
  
    mysqli_query($conn, $insertIntoTask);
  }

}

$sql = "$sql ORDER by task_status.submit_date, task.task_deadline";

$res = mysqli_query($conn, $sql);

$taskdata = array();
while ($row = mysqli_fetch_array($res)) {
  $taskdata[] = array(
    "classId"=>$row["class_id"],
    "className"=>$row["class_name"],
    "taskId"=>$row["task_id"],
    "name"=>$row["task_name"],
    "content"=>$row["task_content"],
    "deadline"=>$row["task_deadline"],
    "submitDate"=>$row["submit_date"],
  );
}

mysqli_free_result($res);

header("Access-Control-Allow-Origin: *");
echo json_encode($taskdata);
