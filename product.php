<?php

// Handle adding the product to the cart
if (isset($_POST['add_to_cart'])) {

    // Start session
    session_start();

    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    } else {
        // Connect to mySQL database
        include("database.php");

        $order_id = "";
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $user_id = $_SESSION['user_id'];

        // Check orders is exist
        $sql = "SELECT * FROM orders WHERE status = 0 AND user_id = $user_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {

            // Get orders id
            while ($row = $result->fetch_assoc()) {
                $order_id = $row['id'];
            }

            // Add product to cart
            $stmt = $conn->prepare("INSERT INTO ordersline (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $order_id, $product_id, $quantity);
            try {
                $stmt->execute();

                // Close MySQL connection
                $conn->close();

                // Go to cart
                header('Location: cart.php');
                exit();
            } catch (mysqli_sql_exception) {

                // Update quantity
                $stmt = $conn->prepare("UPDATE ordersline SET quantity=? + quantity WHERE product_id=? and order_id=?");
                $stmt->bind_param("iii", $quantity, $product_id, $order_id);
                $stmt->execute();

                // Close MySQL connection
                $conn->close();

                // Go to cart
                header('Location: cart.php');
                exit();
            }
        } else {

            // Create a cart
            $stmt = $conn->prepare("INSERT INTO orders (user_id) VALUES (?)");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Get orders id
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $order_id = $row['id'];
                }

                // Add product to cart
                $stmt = $conn->prepare("INSERT INTO ordersline (order_id, product_id, quantity) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $order_id, $product_id, $quantity);
                $stmt->execute();

                // Close MySQL connection
                $conn->close();

                // Go to cart
                header('Location: cart.php');
                exit();
            }
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("layout_head.php") ?>
    <title>
        Product
    </title>

</head>

<body>
    <!-- header -->
    <?php include("header.php"); ?>

    <?php
    // Connect to mySQL database
    include("database.php");

    // Retrieve the product information from the database
    $id = $_GET['id'];
    $sql = "SELECT id, name, price, image_url, description  FROM products WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Display the product information
        $row = $result->fetch_assoc();
        ?>

        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <div class="image">
                        <img src="<?= $row['image_url'] ?> " alt="<?= $row['name'] ?> " width="500px" max-height="100%">
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <h1>
                        <?= $row['name'] ?>
                    </h1>
                    <p>
                        <?= $row['description'] ?>
                    </p>
                    <p>Price:
                        <?= '$' . $row['price'] ?>
                    </p>

                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <input type="number" name="quantity" id="quantity" min="1" value="1">
                        <button type="submit" class="btn btn-success" name="add_to_cart">Add to cart</button>
                    </form>
                </div>
            </div>
        </div>

        <?php

    } else {
        echo "Product not found";
    }

    // Close mySQL connection
    $conn->close();
    ?>
    <!-- footer -->
    <?php include("footer.php"); ?>

</body>

</html>