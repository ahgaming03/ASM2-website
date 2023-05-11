
function updateCartTotal() {
    var cart = document.getElementById("cart");
    var rows = cart.getElementsByClassName("row");
    var subtotal = 0;
    var total_items = 0;
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var price = parseFloat(row.getElementsByClassName("text-end")[0].textContent.replace("$", ""));
        var quantity = parseInt(row.getElementsByTagName("input")[0].value);
        subtotal += price * quantity;
        total_items += quantity;
    }
    document.getElementById("subtotal").textContent = "$" + subtotal.toFixed(2);

    var txt = (total_items > 1) ? " items):" : " item):";
    document.getElementById("total-items").textContent = "(" + total_items + txt;

    // Remove button check out when cart have not product
    if(total_items == 0){
        document.getElementById('checkOut').remove();
    }
}

function updateQuantity(product_id, order_id, quantity) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_quantity.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
            updateCartTotal();
        }
    };
    xhr.send("product_id=" + product_id + "&order_id=" + order_id + "&quantity=" + quantity);
}

function removeProduct(product_id, order_id) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "remove_product.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
            var product = document.getElementById("product-" + product_id);
            product.parentNode.removeChild(product);
            updateCartTotal();
        }
    };
    xhr.send("product_id=" + product_id + "&order_id=" + order_id);
}

function checkOut(id) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "check_out.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
            updateCartTotal();
        }
    };
    xhr.send("id=" + id);
}
