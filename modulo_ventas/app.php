<?php
// app.php
namespace MiApp;

require_once '../conexion.php';

class Ventas
{
    private $conn;

    public function __construct()
    {
        global $conn; // Utilizamos la conexión global declarada en conexion.php
        $this->conn = $conn;
    }

    public function obtenerProductosDisponibles()
    {
        // Consulta SQL para obtener todos los productos
        $sql = "SELECT * FROM `productos` WHERE `Stock` >0";
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
            return "Error";
        }
    }
    public function realizarCompra($detallesCompra)
{
    $detallesCompra = json_decode($detallesCompra, true); // Decodificar la cadena JSON

    if (empty($detallesCompra)) {
        return "Error: No se han proporcionado detalles de la compra.";
    }

    $ventas = new Ventas();

    foreach ($detallesCompra as $detalle) {
        if (!isset($detalle['id_producto']) || !isset($detalle['precio']) || !isset($detalle['cantidad'])) {
            return "Error: Los detalles de la compra son inválidos.";
        }

        $idProducto = $detalle['id_producto'];
        $precio = $detalle['precio'];
        $cantidad = $detalle['cantidad'];

        // Realizar una consulta para obtener la cantidad en stock del producto
        $sql = "SELECT `Stock` FROM productos WHERE ID = $idProducto";
        $result = $this->conn->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $stockActual = $row['Stock'];

            // Verificar si hay suficiente stock para realizar la compra
            if ($stockActual < $cantidad) {
                return "Error: No hay suficiente stock para el producto con ID $idProducto.";
            }

            // Realizar la inserción del registro en la tabla 'ventas'
            $sql = "INSERT INTO `ventas` (`ID`, `ID_Producto`, `Precio`, `Cantidad`, `Fecha_Venta`) VALUES (NULL, $idProducto, $precio, $cantidad, CURRENT_TIMESTAMP)";
            $result = $this->conn->query($sql);

            if ($result) {
                // Actualizar la cantidad de stock del producto en la tabla 'productos'
                $nuevoStock = $stockActual - $cantidad;
                $sql = "UPDATE `productos` SET `Stock` = $nuevoStock WHERE `ID` = $idProducto";
                $result = $this->conn->query($sql);
            } else {
                return "Error al realizar la compra.";
            }
        } else {
            return "Error al obtener los detalles del producto con ID $idProducto.";
        }
    }

    return true; // La compra se realizó correctamente
}
}

// Función para ejecutar las acciones según el parámetro 'action' recibido por POST o GET
function ejecutar()
{
    $ventas = new Ventas();

    if (isset($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
        switch ($action) {
            case 'obtenerProductosDisponibles':
                $productosList = $ventas->obtenerProductosDisponibles();
                echo json_encode($productosList);
                break;
            case 'obtenerProducto':
                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    $producto = $ventas->obtenerProducto($id);
                    echo json_encode($producto);
                } else {
                    echo "Error";
                }
                break;
            case 'realizarCompra':
                if (isset($_POST['detalles'])) {
                    $detalles = $_POST['detalles'];
                    $compra = $ventas->realizarCompra($detalles);
                    echo json_encode($compra);
                } else {
                    echo "Error";
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
