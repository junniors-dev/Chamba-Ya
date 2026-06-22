<?php

    require_once __DIR__ . '/../core/db/database.php';

    class UserModel{
        private $conn;

        public function __construct(){
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function createUser($fotoPerfil, $nombres, $apellidos, $descripcionPerfil, $telefono, $correo, $password, $direccionDomicilio, $codigoPostal, $fechaRegistro, $estado, $idDistrito){
            $sql = "INSERT INTO usuario (fotoPerfil, nombres, apellidos, descripcionPerfil, telefono, correo, password, direccionDomicilio, codigoPostal, fechaRegistro, estado, idDistrito) VALUES (:fotoPerfil, :nombres, :apellidos, :descripcionPerfil, :telefono, :correo, :password, :direccionDomicilio, :codigoPostal, :fechaRegistro, :estado, :idDistrito)";
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $date = date('Y-m-d H:i:s');

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fotoPerfil', $fotoPerfil);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':descripcionPerfil', $descripcionPerfil);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':direccionDomicilio', $direccionDomicilio);
            $stmt->bindParam(':codigoPostal', $codigoPostal);
            $stmt->bindParam(':fechaRegistro', $date);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':idDistrito', $idDistrito);

            return $stmt->execute();
        }

        public function getUserByEmail($correo){
            $sql = "SELECT * FROM usuario WHERE correo = :correo";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function updateUser($id, $fotoPerfil, $nombres, $apellidos, $descripcion, $direccionDomicilio, $codigoPostal, $estado){
            $sql = "UPDATE usuario SET fotoPerfil = :fotoPerfil, nombres = :nombres, apellidos = :apellidos, descripcion = :descripcion, direccionDomicilio = :direccionDomicilio, codigoPostal = :codigoPostal, estado = :estado WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':fotoPerfil', $fotoPerfil);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':direccionDomicilio', $direccionDomicilio);
            $stmt->bindParam(':codigoPostal', $codigoPostal);
            $stmt->bindParam(':estado', $estado);

            return $stmt->execute();

        }

        public function updatePassword($id, $newPassword){
            $sql = "UPDATE usuario SET password = :password WHERE id = :id";
            $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':password', $password_hash);
            
            return $stmt->execute();
        }

        public function deleteUser($id){
            $sql = "DELETE FROM usuario WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        }

        //VALIDACIONES PARA REGISTRO

        public function emailExists($correo){
            $sql = "SELECT COUNT(*) FROM usuario WHERE correo = :correo";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        }

        //VALIDACIONES PARA FORMULARIO DE DATOS DEL USUARIO
        public function getDepartamentos(){
            $sql = "SELECT idDepartamento, nombre FROM departamento";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        //Función para tener la ubicación de una persona (Departamento, Provincia y Distrito) todo solo con el idDistrito
        public function getFullLocationByIdDistrito($idDistrito){
            $sql = "SELECT d.idDistrito, d.nombre as distrito,
                        p.idProvincia, p.nombre as provincia,
                        dep.idDepartamento, dep.nombre as departamento
                    FROM distrito d 
                    INNER JOIN provincia p ON d.idProvincia = p.idProvincia 
                    INNER JOIN departamento dep ON p.idDepartamento = dep.idDepartamento 
                    WHERE d.idDistrito = :idDistrito";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':idDistrito', $idDistrito);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function updateUserProfileData($id, $nombres, $apellidos, $correo, $telefono, $direccionDomicilio, $codigoPostal, $idDistrito = null, $fotoPerfil = null){
            if($fotoPerfil){
                $sql = "UPDATE usuario SET nombres = :nombres, apellidos = :apellidos, correo = :correo, telefono = :telefono, direccionDomicilio = :direccionDomicilio, codigoPostal = :codigoPostal, idDistrito = :idDistrito, fotoPerfil = :fotoPerfil WHERE idUsuario = :id";
            } else {
                $sql = "UPDATE usuario SET nombres = :nombres, apellidos = :apellidos, correo = :correo, telefono = :telefono, direccionDomicilio = :direccionDomicilio, codigoPostal = :codigoPostal, idDistrito = :idDistrito WHERE idUsuario = :id";
            }
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccionDomicilio', $direccionDomicilio);
            $stmt->bindParam(':codigoPostal', $codigoPostal);
            $stmt->bindParam(':idDistrito', $idDistrito);
            
            if($fotoPerfil){
                $stmt->bindParam(':fotoPerfil', $fotoPerfil);
            }
            
            return $stmt->execute();
        }
    }
?>