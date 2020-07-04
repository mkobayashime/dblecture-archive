<?php
$host = "localhost";
if (!$conn = mysqli_connect($host, "s2010127", "pass", "s2010127")){
    die("データベース接続エラー.<br />");
}
mysqli_select_db($conn, "kisop");
mysqli_set_charset($conn, "utf8");

$sql = "SELECT * FROM user, belonging, dept WHERE user.user_id = belonging.user_id AND belonging.dept_id = dept.dept_id";

$userId;
if (isset($_GET["userId"])) {
  $userId = $_GET["userId"];
  $userId = str_replace("%", "\%", $userId);
}

$res = mysqli_query($conn, "$sql AND user.user_id = $userId");

$userdata = array();
while ($row = mysqli_fetch_array($res)) {
  $userdata[] = array(
    "userName"=>$row["user_name"],
    "deptName"=>$row["dept_name"],
  );
}

mysqli_free_result($res);

header("Access-Control-Allow-Origin: *");
echo json_encode($userdata[0]);