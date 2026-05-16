<?php
    class Database {
        function opencon(): PDO{
            return new PDO(
                'mysql:host=localhost;
                dbname=vhan_appetite',
                username:'root',
                password: ''
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
        
    }
?>