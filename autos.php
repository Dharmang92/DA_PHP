<!DOCTYPE html>
<html>

<head>
    <title>Dharmang Gajjar</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>

<?php
require_once "pdo.php";
$failure = false;
$success = false;

if (isset($_GET["name"])) {
    $name = $_GET["name"];
} else {
    die("Name parameter missing");
}

if (isset($_POST["add"])) {
    if (strlen($_POST["make"]) < 1) {
        $failure = "Make is required";
    } else {
        if (is_numeric($_POST["mileage"]) && is_numeric($_POST["year"])) {

            $stmt = $pdo->prepare("insert into autos(make, year, mileage) values(:mk, :yr, :mi)");
            $stmt->execute(
                array(
                    ":mk" => $_POST["make"],
                    ":yr" => $_POST["year"],
                    ":mi" => $_POST["mileage"],
                )
            );

            $success = "Record inserted";
        } else {
            $failure = "Mileage and year must be numeric";
        }
    }
}

if (isset($_POST["logout"])) {
    header('Location: index.php');
}
?>

<body>
    <div class="container">
        <h1>Tracking Autos for <?php echo htmlentities($name) ?> </h1>

        <?php
        if ($failure !== false) {
            echo ('<p style="color: red;">' . htmlentities($failure) . "</p>\n");
        }
        if ($success !== false) {
            echo ('<p style="color: green;">' . htmlentities($success) . "</p>\n");
        }
        ?>

        <form method="post">
            <p>Make:
                <input type="text" name="make" size="60" /></p>
            <p>Year:
                <input type="text" name="year" /></p>
            <p>Mileage:
                <input type="text" name="mileage" /></p>
            <input type="submit" value="Add" name="add">
            <input type="submit" name="logout" value="Logout">
        </form>

        <h2>Automobiles</h2>
        <ul>
            <?php
            require_once "pdo.php";
            $stmt = $pdo->query("select * from autos");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data = $row["year"] . " " . $row["make"] . " / " . $row["mileage"];
                echo "<li>" . htmlentities($data) . "</li>";
            }
            ?>
        </ul>
    </div>
</body>

</html>