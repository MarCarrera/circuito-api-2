<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header("Content-Type: application/json");
require 'db.php';

//$tokenValid = '2PZ9rCA1iyzBL1McJ431';

//if(isset($_POST['tokenValid']) && $_POST['tokenValid'] === $tokenValid) {
    if(isset($_POST['opcion'])){
    $opcion = $_POST['opcion'];

    switch ($opcion) {
        //CONSULTAR MOVIMIENTOS
        case '1':
          /*  $sql = "SELECT TOP (1000) [Id], [Folio], [ItemsCount], [CreatedDate], [StatusId], [User]
                    FROM [dbo].[Movements]";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode([]);
            }*/
        break;
        //CONSULTAR LISSONS
            case '2':
                // Asumiendo que recibes el valor a través de POST o GET
                $name = $_POST['name'] ?? null; // O $_GET['name'], dependiendo de cómo envíes el valor
            
                // Si se proporciona un valor para 'name'
                if ($name) {
                    // Consulta para filtrar los registros donde el nombre coincide con el valor proporcionado
                    $sql = "SELECT [Id], [Name] FROM [dbo].[Lissons] WHERE [Name] = :name";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name);
                } else {
                    // Consulta para obtener todos los registros si no se proporciona un valor
                    $sql = "SELECT TOP (1000) [Name] FROM [dbo].[Lissons]";
                    $stmt = $pdo->prepare($sql);
                }
            
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                if ($result) {
                    echo json_encode($result);
                } else {
                    echo json_encode([]);
                }
            
            break;
        //CONSULTAR ECONOMICS
        case '3':
            // Capturamos el valor original de 'name'
            $originalName = $_POST['name'] ?? null; 
        
            // Validar y modificar $name solo para la consulta
            $name = $originalName;
        
            // Verificar si $name es un número entre 0 y 9
            if (is_numeric($name) && $name >= 0 && $name <= 9) {
                // Agregar un '0' al principio si es un número de un solo dígito
                $name = str_pad($name, 2, '0', STR_PAD_LEFT);
            }
        
            // Si se proporciona un valor para 'name'
            if ($name) {
                // Consulta para filtrar los registros donde el nombre coincide con el valor modificado
                $sql = "SELECT [Id], [Name] FROM [dbo].[Economics] WHERE [Name] = :name";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name);
            } else {
                // Consulta para obtener todos los registros si no se proporciona un valor
                $sql = "SELECT TOP (1000) [Name] FROM [dbo].[Lissons]";
                $stmt = $pdo->prepare($sql);
            }
        
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Si se encuentra el resultado, devolver el valor original de 'name'
            if ($result) {
                // Reemplazar el nombre en el resultado con el valor original
                foreach ($result as &$row) {
                    $row['Name'] = $originalName;
                }
                echo json_encode($result);
            } else {
                echo json_encode([]);
            }
        break;
         //CONSULTAR STATUS ENUMERABLE POR ID
         case '4':
            $idStatus = $_POST['idStatus'] ?? null; 

                $sql = "SELECT [Id], [Name], [id_nave] FROM [dbo].[StatusEnumerable] WHERE Id = :idStatus";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':idStatus', $idStatus);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                if ($result) {
                    echo json_encode($result);
                } else {
                    echo json_encode([]);
                }
        break;
         //CONSULTAR MOVIMIENTOS
         case '5':
            $sql = "SELECT [Id], [Folio], [ItemsCount], [CreatedDate], [StatusId], [User]
                    FROM [dbo].[Movements]";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode([]);
            }
    break;
        //INGRESAR MOVIMIENTO
        case '6':
            $itemsCount = isset($_POST['itemsCount']) ? intval($_POST['itemsCount']) : null;
                $createdDate = $_POST['createdDate'] ?? null;
                $statusId = $_POST['statusId'] ?? null;
                $user = $_POST['user'] ?? null;

                // Verificar que todos los valores están presentes
                if ($itemsCount && $createdDate && $statusId && $user) {
                    try {
                        // Primero, obtener el total de registros
                        $sqlCount = "SELECT COUNT(*) AS TotalRegistros FROM Movements";
                        $stmtCount = $pdo->query($sqlCount);
                        $totalRegistros = $stmtCount->fetch(PDO::FETCH_ASSOC)['TotalRegistros'];

                        // Sumar 1 para obtener el nuevo folio
                        $nuevoFolio = $totalRegistros + 1;

                        // Consulta SQL con marcadores de parámetros
                        $sql = "INSERT INTO [dbo].[Movements] (Id, Folio, ItemsCount, CreatedDate, StatusId, [User]) 
                                VALUES (NEWID(), :folio, :itemsCount, :createdDate, :statusId, :user)";
                        $stmt = $pdo->prepare($sql);

                        // Asignar los valores a los marcadores de parámetros
                        $stmt->bindValue(':folio', $nuevoFolio, PDO::PARAM_INT);  // Vincular como entero
                        $stmt->bindValue(':itemsCount', $itemsCount, PDO::PARAM_INT);  // Vincular como entero
                        $stmt->bindValue(':createdDate', $createdDate, PDO::PARAM_STR); // Vincular como cadena
                        $stmt->bindValue(':statusId', $statusId, PDO::PARAM_STR);  // Vincular como cadena
                        $stmt->bindValue(':user', $user, PDO::PARAM_STR);  // Vincular como cadena

                        // Ejecutar la consulta
                        if ($stmt->execute()) {
                            echo json_encode('success');
                        } else {
                            echo json_encode('fail');
                        }
                    } catch (PDOException $e) {
                        // Captura y manejo de errores
                        echo json_encode(['error' => $e->getMessage()]);
                    }
                } else {
                    echo json_encode(['message' => 'Datos incompletos']);
                }
            break;
    }
    }else{
            echo json_encode('No se envio ninguna opcion');
        }

//}else{
   // echo json_encode('Token inválido');
//}

?>