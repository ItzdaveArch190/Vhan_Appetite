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
        
            function insertAttendance($employeeID, $date, $timeIn, $timeOut){
                $con = $this->opencon();
                try{
                    $stmt = $con->prepare("INSERT INTO attendance (Employee_ID, Attendance_Date, Time_In, Time_Out) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$employeeID, $date, $timeIn, $timeOut]);
                    return $con->lastInsertId();
                } catch(PDOException $e){
                    throw $e;
                }
            }
        
            function fetchPreviousAttendance($employeeID){
                $con = $this->opencon();
                try{
                    $stmt = $con->prepare("SELECT 
                        TIME_FORMAT(Time_In, '%h:%i %p') AS Time_in,
                        TIME_FORMAT(Time_Out, '%h:%i %p') AS Time_out,
                        DATE_FORMAT(Attendance_Date, '%M %d %Y') AS Attendance_Date
                        FROM attendance
                        WHERE Employee_ID = ?
                        ORDER BY Attendance_Date DESC
                        LIMIT 30");
                    $stmt->execute([$employeeID]);
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch(PDOException $e){
                    return [];
                }
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
            return $con->query("SELECT employee.Owner_ID,
                employee.Employee_ID,
                CONCAT(employee.Emp_FN,' ',employee.Emp_LN) AS username,
                employee.Password,
                employee.Email,
                employee.Phone_Number FROM employee
                ")->fetchAll(PDO::FETCH_ASSOC);
                
        }

        function getEmployeesList(){
            $con = $this->opencon();
            try{
                return $con->query("SELECT employee.Employee_ID, employee.Owner_ID, employee.Emp_FN, employee.Emp_LN, employee.Email, employee.Phone_Number, employee.Password, CONCAT(employee.Emp_FN,' ',employee.Emp_LN) AS username FROM employee ORDER BY employee.Employee_ID DESC")->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                return [];
            }
        }

        function getAdminUsersList(){
            $con = $this->opencon();
            try{
                return $con->query("SELECT id_user, first_name, middle_name, last_name, email, account_type, created_at FROM tbl_user WHERE LOWER(account_type) = 'admin' ORDER BY id_user DESC")->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                return [];
            }
        }

        function AddEmployee($ownerID, $firstName, $lastName, $email, $phoneNumber, $password){
            $con = $this->opencon();
            try{
                $con->beginTransaction();
                $stmt = $con->prepare("INSERT INTO employee (Owner_ID, Emp_FN, Emp_LN, Password, Email, Phone_Number) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$ownerID, $firstName, $lastName, $password, $email, $phoneNumber]);
                $insertID = $con->lastInsertId();
                $con->commit();
                return $insertID;
            } catch(PDOException $e){
                if($con->inTransaction()) $con->rollBack();
                throw $e;
            }
        }

        function AddAdminUser($firstName, $lastName, $email, $password){
            $con = $this->opencon();
            try{
                $con->beginTransaction();
                $stmt = $con->prepare("INSERT INTO tbl_user (first_name, last_name, email, password, account_type) VALUES (?, ?, ?, ?, 'admin')");
                $stmt->execute([$firstName, $lastName, $email, $password]);
                $insertID = $con->lastInsertId();
                $con->commit();
                return $insertID;
            } catch(PDOException $e){
                if($con->inTransaction()) $con->rollBack();
                throw $e;
            }
        }

        function loginUser($email, $password){
            $con = $this->opencon();
            try{
                $stmt2 = $con->prepare("SELECT Owner_ID, Owner_FN, Owner_LN, Email, Password FROM owner WHERE Email = ? LIMIT 1");
                $stmt2->execute([$email]);
                $owner = $stmt2->fetch(PDO::FETCH_ASSOC);
                if($owner){
                    if (
                        !empty($owner['Password']) &&
                        (password_verify($password, $owner['Password']) || $password === $owner['Password'])){
                        return [
                            'id_user' => $owner['Owner_ID'],
                            'first_name' => $owner['Owner_FN'],
                            'last_name' => $owner['Owner_LN'],
                            'email' => $owner['Email'],
                            'account_type' => 'admin'
                        ];
                    }
                }

                // legacy fallback for any admin accounts that may still exist in tbl_user
                $stmt = $con->prepare("SELECT * FROM tbl_user WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if($user){
                    if(!empty($user['password']) && password_verify($password, $user['password'])) return $user;
                    if($password === $user['password']) return $user;
                }

                return false;
            } catch(PDOException $e){
                return false;
            }
        }

        function getPaymentMethodId($methodName){
            $con = $this->opencon();
            try{
                $stmt = $con->prepare("SELECT Payment_Method_ID FROM payment_method WHERE Payment_Method = ? LIMIT 1");
                $stmt->execute([$methodName]);
                $res = $stmt->fetch(PDO::FETCH_ASSOC);
                if($res) return (int)$res['Payment_Method_ID'];
                // fallback to Cash id if exists
                $stmt2 = $con->query("SELECT Payment_Method_ID FROM payment_method WHERE Payment_Method = 'Cash' LIMIT 1");
                $r2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                return $r2 ? (int)$r2['Payment_Method_ID'] : 1;
            } catch(PDOException $e){
                return 1;
            }
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


        function fetchStaff_Income($employeeID){
            $con = $this->opencon();
            try{
                $stmt = $con->prepare("SELECT SUM(Total_amount) AS total FROM sales_report WHERE Employee_ID = ?");
                $stmt->execute([$employeeID]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['total'] ?? 0;
            } catch(PDOException $e){
                throw $e;
            }
        }

        function AddProduct($name, $price, $stock, $status, $categoryID){
            $con = $this->opencon();
            try{
                $con->beginTransaction();

                $stmt = $con->prepare("INSERT INTO product (Product_Name, Stock, Status) VALUES (?, ?, ?)");
                $stmt->execute([$name, $stock, $status]);
                $productID = $con->lastInsertId();

                $stmtPrice = $con->prepare("INSERT INTO product_price (Product_ID, Product_Price) VALUES (?, ?)");
                $stmtPrice->execute([$productID, $price]);

                $stmtCat = $con->prepare("INSERT INTO product_category (Product_ID, Category_ID) VALUES (?, ?)");
                $stmtCat->execute([$productID, $categoryID]);

                $con->commit();
                return $productID;
            } catch(PDOEXCEPTION $e){
                if($con->inTransaction()) $con->rollBack();
                throw $e;
            }
        }

        function getAllProducts(){
            $con = $this->opencon();
            try{
                $sql = "SELECT
                    p.Product_ID,
                    p.Product_Name,
                    MAX(pp.Product_Price) AS Product_Price,
                    p.Stock,
                    p.Status,
                    c.Category_Name,
                    MAX(pc.Category_ID) AS Category_ID
                    FROM product p
                    LEFT JOIN product_category pc ON p.Product_ID = pc.Product_ID
                    LEFT JOIN category c ON pc.Category_ID = c.Category_ID
                    LEFT JOIN product_price pp ON p.Product_ID = pp.Product_ID
                    GROUP BY p.Product_ID
                    ORDER BY p.Product_ID DESC";
                $stmt = $con->query($sql);
                if ($stmt === false) return [];
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e){
                return [];
            }
        }

        function getProductsCount($search = '', $category = null){
            $con = $this->opencon();
            $params = [];
            $sql = "SELECT COUNT(DISTINCT p.Product_ID) FROM product p
                    LEFT JOIN product_category pc ON p.Product_ID = pc.Product_ID
                    LEFT JOIN category c ON pc.Category_ID = c.Category_ID
                    LEFT JOIN product_price pp ON p.Product_ID = pp.Product_ID
                    WHERE 1=1";
            if($search){
                $sql .= " AND LOWER(p.Product_Name) LIKE ?";
                $params[] = '%' . strtolower($search) . '%';
            }
            if($category){
                $sql .= " AND pc.Category_ID = ?";
                $params[] = $category;
            }
            $stmt = $con->prepare($sql);
            $stmt->execute($params);
            return (int)$stmt->fetchColumn();
        }

        function getProductsPage($limit = 24, $offset = 0, $search = '', $category = null){
            $con = $this->opencon();
            $params = [];
            $sql = "SELECT
                    p.Product_ID,
                    p.Product_Name,
                    MAX(pp.Product_Price) AS Product_Price,
                    p.Stock,
                    p.Status,
                    c.Category_Name,
                    MAX(pc.Category_ID) AS Category_ID
                    FROM product p
                    LEFT JOIN product_category pc ON p.Product_ID = pc.Product_ID
                    LEFT JOIN category c ON pc.Category_ID = c.Category_ID
                    LEFT JOIN product_price pp ON p.Product_ID = pp.Product_ID
                    WHERE 1=1";
            if($search){
                $sql .= " AND LOWER(p.Product_Name) LIKE ?";
                $params[] = '%' . strtolower($search) . '%';
            }
            if($category){
                $sql .= " AND pc.Category_ID = ?";
                $params[] = $category;
            }
            $sql .= " GROUP BY p.Product_ID ORDER BY p.Product_ID DESC LIMIT :limit OFFSET :offset";
            $stmt = $con->prepare($sql);
            $index = 1;
            foreach($params as $value){
                $stmt->bindValue($index, $value);
                $index++;
            }
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function UpdateProduct($productID, $name, $price, $stock, $status, $categoryID){
            $con = $this->opencon();
            try{
                $con->beginTransaction();

                $stmt = $con->prepare("UPDATE product SET Product_Name = ?, Stock = ?, Status = ? WHERE Product_ID = ?");
                $stmt->execute([$name, $stock, $status, $productID]);

                // update price
                $stmtPrice = $con->prepare("INSERT INTO product_price (Product_ID, Product_Price) VALUES (?, ?)");
                $stmtPrice->execute([$productID, $price]);

                // update category mapping
                $stmtDel = $con->prepare("DELETE FROM product_category WHERE Product_ID = ?");
                $stmtDel->execute([$productID]);
                $stmtCat = $con->prepare("INSERT INTO product_category (Product_ID, Category_ID) VALUES (?, ?)");
                $stmtCat->execute([$productID, $categoryID]);

                $con->commit();
                return true;
            } catch (PDOException $e){
                if($con->inTransaction()) $con->rollBack();
                throw $e;
            }
        }

        function DeleteProduct($productID){
            $con = $this->opencon();
            try{
                $con->beginTransaction();
                $stmt1 = $con->prepare("DELETE FROM product_category WHERE Product_ID = ?");
                $stmt1->execute([$productID]);
                $stmt2 = $con->prepare("DELETE FROM product_price WHERE Product_ID = ?");
                $stmt2->execute([$productID]);
                $stmt3 = $con->prepare("DELETE FROM product WHERE Product_ID = ?");
                $stmt3->execute([$productID]);
                $con->commit();
                return true;
            } catch (PDOException $e){
                if($con->inTransaction()) $con->rollBack();
                throw $e;
            }
        }

        function UpdateCategory($categoryID, $name){
            $con = $this->opencon();
            try{
                $stmt = $con->prepare("UPDATE category SET Category_Name = ? WHERE Category_ID = ?");
                $stmt->execute([$name, $categoryID]);
                return true;
            } catch(PDOException $e){
                throw $e;
            }
        }

        function getSalesReportByDay($limit = 31){
            $con = $this->opencon();
            try{
                $stmt = $con->prepare("SELECT
                    DATE(sales_report.SR_Date) AS Report_Date,
                    COUNT(DISTINCT sales_report.Order_ID) AS Orders_Count,
                    COUNT(order_item.Order_Item_ID) AS Items_Sold,
                    SUM(sales_report.Total_amount) AS Total_Income
                FROM sales_report
                LEFT JOIN orders ON sales_report.Order_ID = orders.Order_ID
                LEFT JOIN order_item ON orders.Order_ID = order_item.Order_ID
                GROUP BY DATE(sales_report.SR_Date)
                ORDER BY Report_Date DESC
                LIMIT ?");
                $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                return [];
            }
        }

        function getTopSoldItems($limit = 10){
            $con = $this->opencon();
            try{
                $stmt = $con->prepare("SELECT
                    product.Product_Name,
                    COUNT(order_item.Order_Item_ID) AS Items_Sold,
                    SUM(order_item.Order_Subtotal) AS Item_Income
                FROM order_item
                JOIN product ON order_item.Product_ID = product.Product_ID
                GROUP BY product.Product_ID, product.Product_Name
                ORDER BY Items_Sold DESC, Item_Income DESC
                LIMIT ?");
                $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                return [];
            }
        }

        function getPayrollRecords($sortBy = 'latest', $status = 'unpaid'){
            $con = $this->opencon();
            try{
                $con->exec("CREATE TABLE IF NOT EXISTS payroll_payment (
                    Payroll_Payment_ID int(11) NOT NULL AUTO_INCREMENT,
                    Employee_ID int(11) NOT NULL,
                    Salary_Amount decimal(10,2) NOT NULL,
                    Paid_At timestamp NOT NULL DEFAULT current_timestamp(),
                    PRIMARY KEY (Payroll_Payment_ID),
                    KEY Employee_ID (Employee_ID)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

                $orderBy = "latest_attendance.Attendance_Date DESC, employee.Employee_ID DESC";
                if($sortBy === 'oldest'){
                    $orderBy = "latest_attendance.Attendance_Date ASC, employee.Employee_ID ASC";
                }

                // validate status filter
                $status = strtolower(trim($status));
                if(!in_array($status, ['all','paid','unpaid'])) $status = 'unpaid';

                $whereClause = '';
                if($status === 'paid'){
                    $whereClause = ' WHERE payroll_status.Paid_At IS NOT NULL';
                } elseif($status === 'unpaid'){
                    $whereClause = ' WHERE payroll_status.Paid_At IS NULL';
                }

                $stmt = $con->query("SELECT
                    employee.Employee_ID,
                    employee.Emp_FN,
                    employee.Emp_LN,
                    employee.Email,
                    employee.Phone_Number,
                    salary.Salary_Amount,
                    salary_bonus.BonusName,
                    latest_attendance.Attendance_Date,
                    latest_attendance.Time_In,
                    latest_attendance.Time_Out,
                    payroll_status.Paid_At AS Payroll_Paid_At
                FROM employee
                LEFT JOIN salary ON salary.Employee_ID = employee.Employee_ID
                LEFT JOIN salary_bonus ON salary_bonus.Salary_ID = salary.Salary_ID
                LEFT JOIN (
                    SELECT a1.Employee_ID, a1.Attendance_Date, a1.Time_In, a1.Time_Out
                    FROM attendance a1
                    INNER JOIN (
                        SELECT Employee_ID, MAX(Attendance_Date) AS Attendance_Date
                        FROM attendance
                        GROUP BY Employee_ID
                    ) a2 ON a1.Employee_ID = a2.Employee_ID AND a1.Attendance_Date = a2.Attendance_Date
                ) latest_attendance ON latest_attendance.Employee_ID = employee.Employee_ID
                LEFT JOIN (
                    SELECT pp1.Employee_ID, pp1.Paid_At
                    FROM payroll_payment pp1
                    INNER JOIN (
                        SELECT Employee_ID, MAX(Paid_At) AS Paid_At
                        FROM payroll_payment
                        GROUP BY Employee_ID
                    ) pp2 ON pp1.Employee_ID = pp2.Employee_ID AND pp1.Paid_At = pp2.Paid_At
                ) payroll_status ON payroll_status.Employee_ID = employee.Employee_ID"
                . $whereClause
                . "\n                ORDER BY {$orderBy}");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                return [];
            }
        }

        function markPayrollPaid($employeeID, $salaryAmount){
            $con = $this->opencon();
            try{
                $con->exec("CREATE TABLE IF NOT EXISTS payroll_payment (
                    Payroll_Payment_ID int(11) NOT NULL AUTO_INCREMENT,
                    Employee_ID int(11) NOT NULL,
                    Salary_Amount decimal(10,2) NOT NULL,
                    Paid_At timestamp NOT NULL DEFAULT current_timestamp(),
                    PRIMARY KEY (Payroll_Payment_ID),
                    KEY Employee_ID (Employee_ID)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

                $stmt = $con->prepare("INSERT INTO payroll_payment (Employee_ID, Salary_Amount) VALUES (?, ?)");
                $stmt->execute([$employeeID, $salaryAmount]);
                return $con->lastInsertId();
            } catch(PDOException $e){
                throw $e;
            }
        }

        function getEmployeesForAdmin(){
            $con = $this->opencon();
            try{
                $stmt = $con->query("SELECT employee.Employee_ID, employee.Owner_ID, employee.Emp_FN, employee.Emp_LN, employee.Email, employee.Phone_Number, employee.Password, CONCAT(employee.Emp_FN,' ',employee.Emp_LN) AS username FROM employee ORDER BY employee.Employee_ID DESC");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                return [];
            }
        }

        function getLatestAttendanceByEmployee(){
            $con = $this->opencon();
            try{
                $stmt = $con->query("SELECT a1.Employee_ID, a1.Attendance_Date, a1.Time_In, a1.Time_Out
                    FROM attendance a1
                    INNER JOIN (
                        SELECT Employee_ID, MAX(Attendance_Date) AS Attendance_Date
                        FROM attendance
                        GROUP BY Employee_ID
                    ) a2 ON a1.Employee_ID = a2.Employee_ID AND a1.Attendance_Date = a2.Attendance_Date");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                return [];
            }
        }
        
        
    }
?>