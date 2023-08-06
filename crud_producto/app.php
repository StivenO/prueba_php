<?php
// app.php
namespace MiApp;

require_once '../conexion.php';

class Producto
{
    private $conn;

    public function __construct()
    {
        global $conn; // Utilizamos la conexión global declarada en conexion.php
        $this->conn = $conn;
    }

    public function obtenerProductos()
    {
        // Consulta SQL para obtener todos los productos
        $sql = "SELECT * FROM productos";
        $result = $this->conn->query($sql);

        $productosList = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productosList[] = $row;
            }
        }

        return $productosList;
    }
    public function obtenerProducto($id)
    {
        // Escapar el ID para evitar inyección de SQL
        $id = $this->conn->real_escape_string($id);

        // Consulta SQL para obtener un producto por su ID
        $sql = "SELECT * FROM productos WHERE ID = $id";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            return $producto;
        } else {
            return null;
        }
    }

    public function actualizarProducto($id, $nombre, $referencia, $precio, $peso, $categoria, $stock)
    {
        // Escapar los datos para evitar inyección de SQL
        $id = $this->conn->real_escape_string($id);
        $nombre = $this->conn->real_escape_string($nombre);
        $referencia = $this->conn->real_escape_string($referencia);
        $precio = $this->conn->real_escape_string($precio);
        $peso = $this->conn->real_escape_string($peso);
        $categoria = $this->conn->real_escape_string($categoria);
        $stock = $this->conn->real_escape_string($stock);

        if ($precio < 0 || $peso < 0 || $stock < 0) {
            echo "error";
            exit;
        }
        // Consulta SQL para actualizar el producto
        $sql = "UPDATE productos SET Nombre='$nombre', Referencia='$referencia', Precio=$precio, Peso=$peso, Categoria='$categoria', Stock=$stock WHERE ID=$id";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function agregarProducto($nombre, $referencia, $precio, $peso, $categoria, $stock)
    {
        $fechaCreacion = date("Y-m-d"); // Fecha actual
        // Escapar los datos para evitar inyección de SQL
        $nombre = $this->conn->real_escape_string($nombre);
        $referencia = $this->conn->real_escape_string($referencia);
        $precio = $this->conn->real_escape_string($precio);
        $peso = $this->conn->real_escape_string($peso);
        $categoria = $this->conn->real_escape_string($categoria);
        $stock = $this->conn->real_escape_string($stock);

        if ($precio < 0 || $peso < 0 || $stock < 0) {
            echo "error";
            exit;
        }
        // Consulta SQL para insertar el nuevo producto
        $sql = "INSERT INTO productos (Nombre, Referencia, Precio, Peso, Categoria, Stock, FechaCreacion)
            VALUES ('$nombre', '$referencia', $precio, $peso, '$categoria', $stock, '$fechaCreacion')";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminarProducto($id)
    {
        // Escapar el ID para evitar inyección de SQL
        $id = $this->conn->real_escape_string($id);

        // Consulta SQL para eliminar el producto por su ID
        $sql = "DELETE FROM productos WHERE ID=$id";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}

// Función para ejecutar las acciones según el parámetro 'action' recibido por POST o GET
function ejecutar()
{
    $productos = new Producto();

    if (isset($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
        switch ($action) {
            case 'obtenerProductos':
                $productosList = $productos->obtenerProductos();
                echo json_encode($productosList);
                break;
            case 'obtenerProducto':
                if (isset($_REQUEST['id'])) {
                    $id = $_REQUEST['id'];
                    $producto = $productos->obtenerProducto($id);
                    echo json_encode($producto);
                } else {
                    echo "Error: Falta el parámetro 'id'.";
                }
                break;
            case 'agregar':
                    // Obtener los datos del formulario
                    $nombre = $_POST['nombre'];
                    $referencia = $_POST['referencia'];
                    $precio = $_POST['precio'];
                    $peso = $_POST['peso'];
                    $categoria = $_POST['categoria'];
                    $stock = $_POST['stock'];

                    // Llamar a la función agregarProducto()
                    $resultado = $productos->agregarProducto($nombre, $referencia, $precio, $peso, $categoria, $stock);

                    // Devolver el resultado como respuesta
                    echo $resultado;
                    exit; // Terminar la ejecución del script después de enviar la respuesta
                break;
            case 'actualizar':
                if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['referencia']) && isset($_POST['precio']) && isset($_POST['peso']) && isset($_POST['categoria']) && isset($_POST['stock'])) {
                    $id = $_POST['id'];
                    $nombre = $_POST['nombre'];
                    $referencia = $_POST['referencia'];
                    $precio = $_POST['precio'];
                    $peso = $_POST['peso'];
                    $categoria = $_POST['categoria'];
                    $stock = $_POST['stock'];

                    $resultado = $productos->actualizarProducto($id, $nombre, $referencia, $precio, $peso, $categoria, $stock);
                    echo $resultado ? "success" : "error";
                } else {
                    echo "Error: Faltan datos del producto a actualizar.";
                }
                break;
            case 'eliminar':
                if (isset($_GET['action']) && $_GET['action'] === 'eliminar') {
                    // Crear una instancia de la clase Producto
                    $productos = new Producto();

                    // Obtener el ID del producto a eliminar
                    $id = $_GET['id'];

                    // Llamar a la función eliminarProducto()
                    $resultado = $productos->eliminarProducto($id);

                    // Devolver el resultado como respuesta
                    echo $resultado;
                    exit; // Terminar la ejecución del script después de enviar la respuesta
                }
                break;
            default:
                echo "Error: Acción no válida.";
                break;
        }
    } else {
        echo "Error: Falta el parámetro 'action'.";
    }
}

// Llamar a la función ejecutar para procesar las acciones
ejecutar();
