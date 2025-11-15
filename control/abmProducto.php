<?php

class abmProducto
{

    public function altaProducto($datos)
    {
        $id = null;
        $objProducto = new Producto();
        $objProducto->cargar(null, $datos['pronombre'], $datos['prodetalle'], $datos['procantstock']);
        if ($objProducto->insertar()) {

            $id = $objProducto->getIdproducto();
        }
        return $id;
    }

    //borrado logico 
    public function bajaProducto($idproducto)
    {
        $objProducto = new Producto();
        $objProducto->buscar($idproducto);
        $objProducto->setProcantstock(-1);
        return $objProducto->modificar();
    }
   
    //borrado fisico
    public function bajaProductoFisico($idproducto)
    {
        $objProducto = new Producto();
        $objProducto->buscar($idproducto);
        return $objProducto->eliminar();
    }

    public function modificacionProducto($datos)
    {
        $objProducto = new Producto();
        $id = null;
        $objProducto->buscar($datos['idproducto']);
        $objProducto->cargar($datos['idproducto'], $datos['pronombre'], $datos['prodetalle'], $datos['procantstock']);
        if ($objProducto->modificar()) {

            $id = $objProducto->getIdproducto();
        }
        return $id;
    }

    public function buscarProducto($idproducto)
    {
        $objProducto = new Producto();
        $colProductos = $objProducto->listar("idproducto = $idproducto");
        return $colProductos;
    }

    public function listarProductos($where = "procantstock >= 0")
    {
        $objProducto = new Producto();
        $colProductos = $objProducto->listar($where);
        return $colProductos;
    }
    public function listarProductosSinStock($where = "procantstock = -1")
    {
        $objProducto = new Producto();
        $colProductos = $objProducto->listar($where);
        return $colProductos;
    }
}
