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
        
    }
?>