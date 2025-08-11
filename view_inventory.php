<?php
include 'db.php';

$message = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

    $id = $conn->real_escape_string($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $quantity = (int)$_POST['quantity'];
    $category = $conn->real_escape_string($_POST['category']);
    $price = (float)$_POST['price'];
    $date = $conn->real_escape_string($_POST['date']);
    $purchased_by = $conn->real_escape_string($_POST['purchased_by']);
    $supplier = $conn->real_escape_string($_POST['supplier']);

    $update_sql = "UPDATE products SET 
        name='$name', 
        quantity=$quantity, 
        category='$category', 
        price=$price, 
        date='$date', 
        purchased_by='$purchased_by', 
        supplier='$supplier' 
        WHERE id='$id'";

    if ($conn->query($update_sql) === TRUE) {
        $message = "<p style='color:green; text-align:center;'>Record updated successfully!</p>";
    } else {
        $message = "<p style='color:red; text-align:center;'>Error updating record: " . $conn->error . "</p>";
    }
}


$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$edit_id = $_GET['edit_id'] ?? null;

?>
<!DOCTYPE html>
<html>
<head>
    <title>View Inventory</title>
    <link rel="stylesheet" href="style.css">
    <style>

        input[type="text"], input[type="number"], input[type="date"], select {
            width: 100%;
            box-sizing: border-box;
            padding: 4px;
        }
        form.inline-form {
            margin: 0;
        }
        td.actions {
            white-space: nowrap;
        }
        table {
            border-collapse: collapse;
            margin: auto;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: center;
        }
    </style>
</head>
<body class="homepage">

<header>
    <div class="container">
        <h1>Warehouse Inventory Management</h1>
        <nav>
            <a href="index.html">Home</a>
            <a href="add_product.php">Add Product</a>
            <a href="view_inventory.php">View Inventory</a>
            <a href="search_inventory.php">Search</a>
        </nav>
    </div>
</header>

<section>
    <div class="container">
        <h2>Inventory List</h2>

        <?php echo $message; ?>

        <table>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Category</th>
                <th>Buying Price</th>
                <th>Buying Date</th>
                <th>Purchased By</th>
                <th>Supplier</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($edit_id == $row['id']) {
                        ?>
                        <tr>
                            <form method="POST" class="inline-form" action="view_inventory.php">
                                <td><?php echo htmlspecialchars($row['id']); ?>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                </td>
                                <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required></td>
                                <td><input type="number" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" required></td>
                                <td>
                                    <select name="category" required>
                                        <?php
                                        $categories = ['Electronics', 'Furniture', 'Clothing', 'Food'];
                                        foreach ($categories as $cat) {
                                            $selected = ($row['category'] == $cat) ? "selected" : "";
                                            echo "<option value=\"$cat\" $selected>$cat</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required></td>
                                <td><input type="date" name="date" value="<?php echo htmlspecialchars($row['date']); ?>" required></td>
                                <td>
                                    <?php
                                    $purchasers = ['John', 'Sarah', 'Mike', 'Anna'];
                                    foreach ($purchasers as $purchaser) {
                                        $checked = ($row['purchased_by'] == $purchaser) ? "checked" : "";
                                        echo "<label><input type='radio' name='purchased_by' value='$purchaser' $checked required> $purchaser </label> ";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <select name="supplier" required>
                                        <?php
                                        $suppliers = ['ABC Suppliers', 'XYZ Traders', 'Global Goods', 'FastSupply Co'];
                                        foreach ($suppliers as $supplier) {
                                            $selected = ($row['supplier'] == $supplier) ? "selected" : "";
                                            echo "<option value=\"$supplier\" $selected>$supplier</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="actions">
                                    <button type="submit" name="update">Save</button>
                                    <a href="view_inventory.php" style="margin-left:8px;">Cancel</a>
                                </td>
                            </form>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <td><?php echo ($row["id"]); ?></td>
                            <td><?php echo ($row["name"]); ?></td>
                            <td><?php echo ($row["quantity"]); ?></td>
                            <td><?php echo ($row["category"]); ?></td>
                            <td><?php echo ($row["price"]); ?></td>
                            <td><?php echo ($row["date"]); ?></td>
                            <td><?php echo ($row["purchased_by"]); ?></td>
                            <td><?php echo ($row["supplier"]); ?></td>
                            <td class="actions">
                                <a href="view_inventory.php?edit_id=<?php echo urlencode($row['id']); ?>">Edit</a> |
                                <a href="delete_product.php?id=<?php echo urlencode($row['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
            } else {
                echo "<tr><td colspan='9'>No products found</td></tr>";
            }
            ?>
        </table>
    </div>
</section>

<footer>
    <div class="container" style="text-align:center; margin-top:20px;">
        &copy; <?php echo date("Y"); ?> Warehouse Inventory Management
    </div>
</footer>

</body>
</html>

<?php
$conn->close();
?>
