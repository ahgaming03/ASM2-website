<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("layout_head.php") ?>
    <title>Cart</title>

</head>

<body>
    <?php include("header.php"); ?>

    <div class="container">

        <div class="cart-box" id="cart">
            <h2>Shopping cart</h2>


            <?php
            if (empty($_SESSION["user_id"])) {
                ?>

                <p>Your cart is empty</p>
                <a href="login.php" class="btn btn-warning" role="button">Sign in to your account</a>
                <a href="signup.php" class="btn btn-secondary">Sign up now</a>

            <?php } else { ?>
                <div class="text-end">Price</div>
                <hr>
                <?php
                // Connect to mySQL database
                include("database.php");

                $user_id = $_SESSION["user_id"];

                $stmt = $conn->prepare(
                    "SELECT p.id, ol.order_id, p.name AS pName, b.name AS bName, p.price, ol.quantity, p.image_url
            FROM ordersline AS ol
            INNER JOIN products AS p
            ON ol.product_id = p.id
            INNER JOIN brands AS b
            ON p.brand_id = b.id
            RIGHT JOIN orders AS o
            ON ol.order_id = o.id
            WHERE  o.user_id = $user_id AND o.status = 0"
                );

                // Execute query
                $stmt->execute();

                // Get query result as a result object
                $result = $stmt->get_result();

                // Fetch result rows as an associative array
                while ($row = $result->fetch_assoc()) {
                    ?>

                    <div class="row" id="product-<?= $row['id'] ?>">
                        <div class="col-md-2">
                            <img src="<?= $row['image_url'] ?>" class="img-fluid rounded-start" alt="<?= $row['pName'] ?>"
                                height="180" width="180">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <?= $row['pName'] ?>
                                </h4>
                                <p>Brand:
                                    <?= $row['bName'] ?>
                                </p>
                                <div>
                                    <input type="number" id="quantity-<?= $row['id'] ?>" min="1" value="<?= $row['quantity'] ?>"
                                        width="10px"
                                        onchange="updateQuantity(<?= $row['id'] ?>, <?= $row['order_id'] ?>, this.value)">
                                    <button type="button" class="btn btn-danger"
                                        onclick="removeProduct(<?= $row['id'] ?>, <?= $row['order_id'] ?>)">Remove</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-end" id="price-<?= $row['id'] ?>"><?= "$" . $row['price'] ?></div>
                        </div>
                        <hr>
                    </div>

                    <?php
                }
                // Select the column you want to calculate the total of
                $sql = "SELECT id, total, total_items FROM orders WHERE user_id = $user_id AND status = 0";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output the total
                    $row = $result->fetch_assoc();
                    ?>

                    <div class="text-end">
                        <span>Subtotal</span>
                        <span id="total-items">
                            <?= "(" . $row['total_items'], ($row['total_items'] > 1) ? " items):" : " item):" ?>
                        </span>
                        <span id="subtotal">
                            <?= "$" . $row['total'] ?>
                        </span>
                        <button type="button" class="btn btn-primary" id="checkOut" data-bs-toggle="modal" data-bs-target="#check-out"
                            onclick="checkOut(<?= $row['id'] ?>)">Check out</button>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade " id="check-out" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Order success</h1>
                                </div>
                                <div class="modal-body">
                                    <h2>Thanks for your order!</h2>
                                    <span>
                                        <?= "Order ID: " . $row['id'] ?>
                                    </span>
                                </div>
                                <div class="modal-footer">
                                    <a href="index.php" role="button" class="btn btn-primary">Continue shopping</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                } else {
                    echo "YOUR CART IS EMPTY!!";
                }
                // Close mySQL connection
                $conn->close();
            }
            ?>
        </div>
    </div>

    <?php include("footer.php"); ?>

    <script src="js/script.js"></script>
</body>

</html>