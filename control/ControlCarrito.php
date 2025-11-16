<?php
class ControlCarrito
{

    /**
     * Busca una compra del usuario que NO tenga estados asociados.
     * Esa compra se considera el "Carrito Activo".
     */
    public function buscarCarritoActivo($idUsuario)
    {
        $abmCompra = new ABMCompra();
        $abmCompraEstado = new ABMCompraEstado();

        // Buscar todas las compras del usuario
        $colCompras = $abmCompra->buscar(['idusuario' => $idUsuario]);

        foreach ($colCompras as $objCompra) {
            // Verificar si la compra tiene algún estado
            $listaEstados = $abmCompraEstado->buscar([
                'idcompra' => $objCompra->getIdcompra()
            ]);

            // Si la lista de estados está vacía, es nuestro carrito abierto
            if (count($listaEstados) == 0) {
                return $objCompra;
            }
        }
        return null;
    }

    /**
     * Crea una nueva compra sin estado asociado.
     */
    public function crearCarrito($idUsuario)
    {
        $abmCompra = new ABMCompra();

        // Solo creamos el registro en la tabla COMPRA
        // La fecha se setea automáticamente en el alta de ABMCompra o en la BD
        if ($abmCompra->alta(['idusuario' => $idUsuario])) {

            // Retornamos el carrito recién creado buscándolo con la nueva lógica
            return $this->buscarCarritoActivo($idUsuario);
        }
        return null;
    }

    public function agregarProducto($idUsuario, $idProducto, $cantidad)
    {
        $resp = ['exito' => false, 'msg' => ''];

        // Obtener o crear carrito (Compra sin estado)
        $objCompra = $this->buscarCarritoActivo($idUsuario);
        if ($objCompra == null) {
            $objCompra = $this->crearCarrito($idUsuario);
        }

        if ($objCompra) {
            $abmItem = new ABMCompraItem();
            $abmProd = new abmProducto();

            // Verificar Stock del Producto
            $listaProd = $abmProd->buscarProducto($idProducto);

            if (count($listaProd) == 0) {
                return ['exito' => false, 'msg' => 'Producto no existe'];
            }

            $objProducto = $listaProd[0];
            $stockActual = $objProducto->getProcantstock();

            if ($stockActual < $cantidad) {
                return ['exito' => false, 'msg' => 'No hay suficiente stock disponible.'];
            }

            // Verificar si el item ya existe en el carrito para sumar cantidad
            $items = $abmItem->buscar([
                'idcompra' => $objCompra->getIdcompra(),
                'idproducto' => $idProducto
            ]);

            if (count($items) > 0) {

                $objItem = $items[0];
                $nuevaCantidad = $objItem->getCicantidad() + $cantidad;

                if ($stockActual < $nuevaCantidad) {
                    return ['exito' => false, 'msg' => 'Stock insuficiente (ya tienes ' . $objItem->getCicantidad() . ' en carrito).'];
                }

                $paramUpdate = [
                    'idcompraitem' => $objItem->getIdcompraitem(),
                    'cicantidad' => $nuevaCantidad,
                    'idcompra' => $objCompra->getIdcompra(),
                    'idproducto' => $idProducto
                ];
                if ($abmItem->modificacion($paramUpdate)) {
                    $resp['exito'] = true;
                    $resp['msg'] = 'Cantidad actualizada';
                }
            } else {

                $paramNew = [
                    'idcompra' => $objCompra->getIdcompra(),
                    'idproducto' => $idProducto,
                    'cicantidad' => $cantidad
                ];
                if ($abmItem->alta($paramNew)) {
                    $resp['exito'] = true;
                    $resp['msg'] = 'Producto agregado';
                }
            }
        } else {
            $resp['msg'] = 'No se pudo crear el carrito.';
        }

        return $resp;
    }

    public function quitarProducto($idCompraItem)
    {
        $abmItem = new ABMCompraItem();
        return $abmItem->baja(['idcompraitem' => $idCompraItem]);
    }

    /**
     * Establece una cantidad específica para un producto en el carrito.
     */

    public function modificarCantidad($idUsuario, $idProducto, $nuevaCantidad)
    {
        // 1. Buscar el carrito activo
        $objCompra = $this->buscarCarritoActivo($idUsuario);

        if ($objCompra == null) {
            return ['exito' => false, 'msg' => 'No hay carrito activo.'];
        }

        $abmItem = new ABMCompraItem();
        $abmProd = new abmProducto();

        // 2. Buscar el producto y verificar stock
        $listaProd = $abmProd->buscarProducto($idProducto);
        if (count($listaProd) == 0) {
            return ['exito' => false, 'msg' => 'Producto no encontrado.'];
        }
        $objProducto = $listaProd[0];

        if ($objProducto->getProcantstock() < $nuevaCantidad) {
            return ['exito' => false, 'msg' => 'Stock insuficiente. Solo quedan ' . $objProducto->getProcantstock() . ' unidades.'];
        }

        // 3. Buscar el item en el carrito
        $items = $abmItem->buscar([
            'idcompra' => $objCompra->getIdcompra(),
            'idproducto' => $idProducto
        ]);

        if (count($items) > 0) {
            $objItem = $items[0];

            // 4. Modificar la cantidad
            $paramUpdate = [
                'idcompraitem' => $objItem->getIdcompraitem(),
                'cicantidad' => $nuevaCantidad,
                'idcompra' => $objCompra->getIdcompra(),
                'idproducto' => $idProducto
            ];

            if ($abmItem->modificacion($paramUpdate)) {
                return ['exito' => true, 'msg' => 'Cantidad actualizada.'];
            } else {
                return ['exito' => false, 'msg' => 'Error al actualizar la base de datos.'];
            }
        } else {
            return ['exito' => false, 'msg' => 'El producto no está en el carrito.'];
        }
    }
}
