Realizar una consulta que permita conocer cuál es el producto que más stock tiene.
-> SELECT * FROM productos ORDER BY Stock DESC LIMIT 1;
Realizar una consulta que permita conocer cuál es el producto más vendido.
-> SELECT p.ID AS ID_Producto, p.Nombre AS Nombre_Producto, SUM(v.Cantidad) AS TotalVendido FROM productos p JOIN ventas v ON p.ID = v.ID_Producto GROUP BY p.ID, p.Nombre ORDER BY TotalVendido DESC LIMIT 1;

versiones utilizadas:
Apache/2.4.54 - PHP/8.2.0 mod_fcgid/2.3.10-dev
Servidor: MySQL (127.0.0.1 via TCP/IP)
phpMyAdmin: versión: 5.2.0

Nota: La base de datos se encuentra anexada aquí mismo con el nombre prueba_php.sql 
se trabajó en local por lo que debes cambiar los datos de conexión en caso de que tenga algún usuario o contraseña diferente.