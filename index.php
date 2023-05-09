<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("layout_head.php") ?>
    <title>Jackarry</title>
</head>

<body>
    <!-- Header page -->
    <?php include("header.php"); ?>

    <br>
    <div class="container-xxl">


        <?php
        // Connect to mySQL database
        include("database.php");

        if (isset($_POST['search'])) {
            $input = $_POST['input'];

            echo "<h1>Search for: '".$input."'</h1>";

            if (!empty($input)) {
                $sql = "SELECT id, name, price, image_url FROM products WHERE name LIKE '%$input%'";

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                echo "<div class='row row-cols-1 row-cols-md-3 g-4 mb-5'>";
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        ?>
                        <div class="col">
                            <div class="card h-100">
                                <a href="product.php?id=<?= $row['id'] ?>">
                                    <img src="<?= $row['image_url'] ?>" class="card-img" alt="<?= $row['name'] ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= $row['name'] ?>
                                    </h5>
                                </div>
                                <div class="card-footer">
                                    <small class="text-body-secondary">
                                        <?= '$' . $row['price'] ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                echo '</div>';
            } else {
                echo "<h3 class='text-center'>Not found</h3>";
            }
            echo "<hr>";
        }
        ?>



        <h1>Product</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            // Prepare SQL query
            $stmt = $conn->prepare("SELECT id, name, price, image_url FROM products");

            // Execute query
            $stmt->execute();

            // Get query result as a result object
            $result = $stmt->get_result();

            // Fetch result rows as an associative array
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col">
                    <div class="card h-100">
                        <a href="product.php?id=<?= $row['id'] ?>">
                            <img src="<?= $row['image_url'] ?>" class="card-img" alt="<?= $row['name'] ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= $row['name'] ?>
                            </h5>
                        </div>
                        <div class="card-footer">
                            <small class="text-body-secondary">
                                <?= '$'. $row['price'] ?>
                            </small>
                        </div>
                    </div>
                </div>
                <?php
            }

            // Close mySQL connection
            $conn->close();
            ?>

        </div>
    </div>

    <!-- footer -->
    <?php include("footer.php"); ?>
</body>

</html>
