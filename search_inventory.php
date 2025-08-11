<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Search Inventory</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<header>
    <h1>Warehouse Inventory Management</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="add_product.php">Add Products</a>
        <a href="search_inventory.php">Search Inventory</a>
        <a href="view_inventory.php">View Inventory</a>
    </nav>
</header>

<main>
    <section>
        <h2>Search Inventory</h2>
        <form action="" method="POST">
            <label>Search by Product ID:</label>
            <input type="text" name="product_id" placeholder="Enter Product ID" />

            <label>Search by Category:</label>
            <input type="text" name="category" placeholder="Enter Category" />

            <label>Search by Supplier:</label>
            <input type="text" name="supplier_name" placeholder="Enter Supplier Name" />

            <button type="submit" name="search">Search</button>
        </form>
    </section>

    <?php
    if (isset($_POST['search'])) {
        // Get and escape inputs to prevent SQL injection
        $product_id = $conn->real_escape_string($_POST['product_id']);
        $category = $conn->real_escape_string($_POST['category']);
        $supplier_name = $conn->real_escape_string($_POST['supplier_name']);

        // Build query dynamically based on inputs
        $query = "SELECT * FROM products WHERE 1=1";
        if (!empty($product_id)) {
            $query .= " AND id = '$product_id'";
        }
        if (!empty($category)) {
            $query .= " AND category = '$category'";
        }
        if (!empty($supplier_name)) {
            $query .= " AND supplier = '$supplier_name'";
        }

        $result = mysqli_query($conn, $query);

        echo "<section><h2>Search Results</h2>";
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table border='1' style='margin:auto;'>";
            echo "<tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Purchased By</th>
                    <th>Supplier</th>
                  </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>" . ($row['id']) . "</td>
                        <td>" . ($row['name']) . "</td>
                        <td>" .  ($row['quantity']) . "</td>
                        <td>" .  ($row['category']) . "</td>
                        <td>" .  ($row['price']) . "</td>
                        <td>" .  ($row['date']) . "</td>
                        <td>" .  ($row['purchased_by']) . "</td>
                        <td>" .  ($row['supplier']) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No results found.</p>";
        }
        echo "</section>";
    }
    ?>
</main>

<footer>
    <p>&copy; 2025 Warehouse Inventory Management</p>
</footer>

</body>
</html>
