<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>学群</title>
    <link rel="stylesheet" href="../style/db.css" />
</head>
<body>
    <table>
        <thead>
            <tr><td>学群id</td><td>学群名</td></tr>
        </thead>
        <tbody>
            <?php
            $host = "localhost";
            if (!$conn = mysqli_connect($host, "s2010127", "pass", "s2010127")){
                die("MySQL接続エラー.<br />");
            }
            mysqli_select_db($conn, "kisop");
            mysqli_set_charset($conn, "utf8");
            
            $sql = "SELECT * FROM dept";
            $res = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($res)) {
                print("<tr>");
                print("<td style='text-align: center;'>".$row["dept_id"]."</td>");
                print("<td>".$row["dept_name"]."</td>");
                print("</tr>\n");
            }
            mysqli_free_result($res);
            ?>
        </tbody>
    </table>
</body>
</html>
