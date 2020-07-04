<?php
$host = "localhost";
if (!$conn = mysqli_connect($host, "s2010127", "pass", "s2010127")){
    die("データベース接続エラー.<br />");
}
mysqli_select_db($conn, "kisop");
mysqli_set_charset($conn, "utf8");

$userId;

if ($_SERVER["REQUEST_METHOD"] === 'GET') {
  if (isset($_GET["userId"])) {
    $userId = mysqli_escape_string($conn, $_GET["userId"]);
    $userId = str_replace("%", "\%", $userId);
  }
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
  $classId;
  $className;

  if (isset($_POST["userId"])) {
    $userId = mysqli_escape_string($conn, $_POST["userId"]);
    $userId = str_replace("%", "\%", $userId);
  }
  if (isset($_POST["classId"])) {
    $classId = mysqli_escape_string($conn, $_POST["classId"]);
    $classId = str_replace("%", "\%", $classId);
  }
  if (isset($_POST["className"])) {
    $className = mysqli_escape_string($conn, $_POST["className"]);
    $className = str_replace("%", "\%", $className);
  }

  $sqlClass = "INSERT INTO class values(\"$classId\", \"$className\")";
  $sqlClassTaking = "INSERT INTO class_taking values(\"$userId\", \"$classId\")";

  mysqli_query($conn, $sqlClass);
  mysqli_query($conn, $sqlClassTaking);
}

$sql = "SELECT * FROM class, class_taking WHERE class.class_id = class_taking.class_id";

$res = mysqli_query($conn, "$sql AND class_taking.user_id = $userId");

$classes = array();
while ($row = mysqli_fetch_array($res)) {
  $classes[] = array(
    "classId"=>$row["class_id"],
    "className"=>$row["class_name"],
  );
}

mysqli_free_result($res);

header("Access-Control-Allow-Origin: *");
echo json_encode($classes);
