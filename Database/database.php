<?php
    class Database {
        function opencon(): PDO{
            return new PDO(
                'mysql:host=localhost;
                dbname=vhan_appetite',
                'root',
                ''
            );
        }

        function getTodaySales(){
            $con = $this->opencon();
            $stmt = $con->query("SELECT SUM(Total_amount) AS total FROM sales_report ");
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

                return $result['total'] ?? 0;

        }

        function getOrder(){
            $con = $this->opencon();
            return $con->query("SELECT COUNT(*) AS transaction FROM orders")->fetchColumn();
        }

        function getAllEmployee(){
            $con = $this->opencon();
            return $con->query("SELECT COUNT(*) AS emp FROM employee")->fetchColumn();
        }
        

        function viewCategory(){
            $con = $this->opencon();
            return $con->query('SELECT Category_ID, Category_Name FROM category ORDER BY Category_ID ASC')->fetchAll();
        }

        function AddCategory($category){
            $con =  $this->opencon();
            try{
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO category(Category_Name) VALUES(?)');
                $stmt->execute([$category]);
                $insertCategory = $con->lastInsertId();
                $con->commit();
                return $insertCategory;
            } catch(PDOEXCEPTION $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
                throw $e;
            }
        }

        function GetAttendance(){
            $con = $this->opencon();
            return $con->query("
                SELECT
                    CONCAT(employee.Emp_FN,' ',employee.Emp_LN) AS Staff,
                    TIME_FORMAT(attendance.Time_In, '%l : %i %p') AS Time_In,
                    TIME_FORMAT(attendance.Time_Out, '%l : %i %p') AS logout,
                    DATE_FORMAT(attendance.Attendance_Date, '%M %d %Y') AS Date
                FROM attendance 
                JOIN employee 
                    ON attendance.Employee_ID = employee.Employee_ID
                WHERE attendance.Attendance_ID IN (
                    SELECT MAX(Attendance_ID)
                    FROM attendance
                    GROUP BY Employee_ID, Attendance_Date
                )
                ORDER BY attendance.Attendance_Date ASC
            ")->fetchAll(PDO::FETCH_ASSOC);
        }

        function deleteCategory($categoryID){
            $con = $this->opencon();
            try{
            $check = $con->prepare("SELECT COUNT(*) FROM product_category WHERE Category_ID = ?");
                $check->execute([$categoryID]);

                if($check->fetchColumn() > 0){
                    return "used"; // tell system it's not allowed
                }

                $con->beginTransaction();

                $stmtCategory = $con->prepare("DELETE FROM category WHERE Category_ID = ?");
                $stmtCategory->execute([$categoryID]);

                $stmtproductCat = $con->prepare("DELETE FROM product_category WHERE Category_ID = ?");
                $stmtproductCat->execute([$categoryID]);

                $con->commit();
                return "deleted";
                } catch(PDOEXCEPTION $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
                echo "Error " . $e->getMessage();
            }
        }


        function staffLogin(){
            $con = $this->opencon();
            return $query = $con->query("SELECT employee.Owner_ID,
                employee.Employee_ID,
                CONCAT(employee.Emp_FN,' ',employee.Emp_LN) AS username,
                employee.Password,
                employee.Email,
                employee.Phone_Number FROM employee
                ")->fetchAll(PDO::FETCH_ASSOC);
        }


        function getBurger_PriceList(){
            $con = $this->opencon();
            return $con->query("SELECT product_category.Category_ID,
                product.Product_ID,
                product.Product_Name,
                product_price.Product_Price,
                product.Stock,
                product.Status
                FROM product_category
                JOIN category ON product_category.Category_ID = category.Category_ID
                JOIN product ON product_category.Product_ID = product.Product_ID
                JOIN product_price ON product.Product_ID = product_price.Product_ID
                HAVING product_category.Category_ID = 1 
            ")->fetchAll(PDO::FETCH_ASSOC);
        }

        function getHotdog_PriceList(){
            $con = $this->opencon();
            return $con->query("
                SELECT product_category.Category_ID,
                product.Product_ID,
                product.Product_Name,
                product_price.Product_Price,
                product.Stock,
                product.Status              
                FROM product_category
                JOIN category ON product_category.Category_ID = category.Category_ID
                JOIN product ON product_category.Product_ID = product.Product_ID
                JOIN product_price ON product.Product_ID = product_price.Product_ID
                HAVING product_category.Category_ID = 2 
            ")->fetchAll(PDO::FETCH_ASSOC);
        }

        function getCorndog_Pricelist(){
            $con = $this->opencon();
            return $con->query("
                SELECT product_category.Category_ID,
                product.Product_ID,
                product.Product_Name,
                product_price.Product_Price,
                product.Stock,
                product.Status
                FROM product_category
                JOIN category ON product_category.Category_ID = category.Category_ID
                JOIN product ON product_category.Product_ID = product.Product_ID
                JOIN product_price ON product.Product_ID = product_price.Product_ID
                HAVING product_category.Category_ID = 3
            ")->fetchAll();
        }

        function getBeverage_Pricelist(){
            $con = $this->opencon();
            return $con->query("
                SELECT product_category.Category_ID,
                product.Product_ID,
                product.Product_Name,
                product_price.Product_Price,
                product.Stock,
                product.Status
                FROM product_category
                JOIN category ON product_category.Category_ID = category.Category_ID
                JOIN product ON product_category.Product_ID = product.Product_ID
                JOIN product_price ON product.Product_ID = product_price.Product_ID
                HAVING product_category.Category_ID = 4 
            ")->fetchAll();
        }

        function getTotalOrders($employeeID){
        $con = $this->opencon();
            try{
                $stmt = $con->prepare("SELECT COUNT(*) AS TotalOrder FROM orders WHERE Employee_ID = ?");
                $stmt->execute([$employeeID]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['TotalOrder'];
                } catch(PDOException $e){
                throw $e;
            }
        }

        function totalProduct(){
            $con = $this->opencon();
            return $con->query("SELECT COUNT(*) AS totalproduct 
                                FROM `product_category` 
                                WHERE product_category.Category_ID = 1")->fetchAll();
        }

        function placeOrder($employeeID, $cart, $paymentMethodID = 1){
            $con = $this->opencon();

            if(empty($cart)){
                throw new Exception('Cart is empty');
            }

            try{
                $con->beginTransaction();

                $totalQuantity = 0;
                $totalAmount = 0;

                $checkStock = $con->prepare("SELECT Stock FROM product WHERE Product_ID = ? FOR UPDATE");

                foreach($cart as $item){
                    $quantity = (int)$item['quantity'];
                    $price = (float)$item['price'];
                    $subtotal = $quantity * $price;

                    $checkStock->execute([$item['id']]);
                    $stockResult = $checkStock->fetch(PDO::FETCH_ASSOC);

                    if(!$stockResult || (int)$stockResult['Stock'] < $quantity){
                        throw new Exception('Insufficient stock for one or more items.');
                    }

                    $totalQuantity += $quantity;
                    $totalAmount += $subtotal;
                }

                $insertOrder = $con->prepare("INSERT INTO orders (Employee_ID, Order_Quantity) VALUES (?, ?)");
                $insertOrder->execute([$employeeID, $totalQuantity]);
                $orderID = $con->lastInsertId();

                $insertOrderItem = $con->prepare("INSERT INTO order_item (Order_ID, Product_ID, Order_Subtotal) VALUES (?, ?, ?)");
                $updateStock = $con->prepare("UPDATE product SET Stock = Stock - ? WHERE Product_ID = ?");

                foreach($cart as $item){
                    $quantity = (int)$item['quantity'];
                    $price = (float)$item['price'];
                    $subtotal = $quantity * $price;

                    for($i = 0; $i < $quantity; $i++){
                        $insertOrderItem->execute([$orderID, $item['id'], $price]);
                    }

                    $updateStock->execute([$quantity, $item['id']]);
                }

                $insertPayment = $con->prepare("INSERT INTO payment (Payment_Method_ID, Order_ID, Payment_Amount) VALUES (?, ?, ?)");
                $insertPayment->execute([$paymentMethodID, $orderID, $totalAmount]);

                $insertSalesReport = $con->prepare("INSERT INTO sales_report (Employee_ID, Order_ID, Total_amount) VALUES (?, ?, ?)");
                $insertSalesReport->execute([$employeeID, $orderID, $totalAmount]);

                $con->commit();
                return $orderID;
            } catch(PDOException $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
                throw $e;
            } catch(Exception $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
                throw $e;
            }
        }

        function getCompletedOrdersByEmployee($employeeID){
            $con = $this->opencon();
            $stmt = $con->prepare("SELECT
                orders.Order_ID,
                orders.Order_Quantity,
                sales_report.Total_amount,
                DATE_FORMAT(sales_report.SR_Date, '%M %d %Y %l:%i %p') AS Completed_Date,
                payment_method.Payment_Method
            FROM sales_report
            JOIN orders ON sales_report.Order_ID = orders.Order_ID
            LEFT JOIN payment ON orders.Order_ID = payment.Order_ID
            LEFT JOIN payment_method ON payment.Payment_Method_ID = payment_method.Payment_Method_ID
            WHERE sales_report.Employee_ID = ?
            ORDER BY sales_report.SR_Date DESC");
            $stmt->execute([$employeeID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function getCompletedOrderItems($orderID){
            $con = $this->opencon();
            $stmt = $con->prepare("SELECT
                product.Product_Name,
                COUNT(order_item.Order_Item_ID) AS Qty,
                SUM(order_item.Order_Subtotal) AS Item_Total
            FROM order_item
            JOIN product ON order_item.Product_ID = product.Product_ID
            WHERE order_item.Order_ID = ?
            GROUP BY order_item.Product_ID, product.Product_Name
            ORDER BY product.Product_Name ASC");
            $stmt->execute([$orderID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        function fetchStaff_Income(){
            $con = $this->opencon();
        }


        
    }
?>